import client, { initZoom } from './client';

let mediaStream = null;
let currentTeacherId = null;
let videoContainerElement = null;

const createVideoElement = (userId) => {
  const videoId = `video-${userId}`;
  const wrapperId = `wrapper-${userId}`;

  // Check if wrapper and video already exist
  let videoWrapper = document.getElementById(wrapperId);
  if (!videoWrapper) {
    videoWrapper = document.createElement('div');
    videoWrapper.id = wrapperId;
    videoWrapper.className = 'video-wrapper flex-1 relative bg-black rounded-lg overflow-hidden'; // Adjust styling as needed
    // Add logic for dynamic sizing/layout if multiple videos are expected
    // For now, simple flex styling
    videoContainerElement.appendChild(videoWrapper);
  }

  let videoElement = document.getElementById(videoId);
  if (!videoElement) {
    videoElement = document.createElement('video');
    videoElement.id = videoId;
    videoElement.className = 'w-full h-full object-cover'; // Adjust styling
    videoWrapper.appendChild(videoElement);
  }
  return videoElement;
};

const destroyVideoElement = (userId) => {
  const videoId = `video-${userId}`;
  const wrapperId = `wrapper-${userId}`;
  const videoElement = document.getElementById(videoId);
  const videoWrapper = document.getElementById(wrapperId);

  if (videoElement) {
    videoElement.remove();
  }
  if (videoWrapper) {
    videoWrapper.remove();
  }
};

const renderParticipantVideo = async (participant) => {
  if (!mediaStream || !videoContainerElement) return;

  const userId = participant.userId;
  const isSelf = userId === client.getCurrentUserInfo().userId;

  // If this is the current user (self-view), and they are the teacher, skip rendering here
  // The Join.vue component might have its own logic for the teacher's self-view
  if (isSelf && userId === currentTeacherId) {
      return;
  }

  // Only render if video is on
  if (participant.bVideoOn) {
    const videoElement = createVideoElement(userId);
    try {
      await mediaStream.attachVideo(userId, videoElement, isSelf);
      console.log(`Attached video for userId: ${userId} (self: ${isSelf})`);
    } catch (error) {
      console.error(`Error attaching video for ${userId}:`, error);
    }
  } else {
    // If video is off, ensure it's detached and element removed
    await stopRenderParticipantVideo(userId);
  }
};

const stopRenderParticipantVideo = async (userId) => {
  if (!mediaStream) return;
  try {
    // Detach first
    await mediaStream.detachVideo(userId);
    console.log(`Detached video for userId: ${userId}`);
    // Then destroy the DOM element
    destroyVideoElement(userId);
  } catch (error) {
    console.error(`Error detaching/destroying video for ${userId}:`, error);
  }
};

export const startVideoSession = async (containerElement, sessionName, token, userName, teacherId) => {
  try {
    console.log('Attempting to start video session...');
    videoContainerElement = containerElement; // Store the container element
    currentTeacherId = teacherId; // Store teacher ID for special handling

    if (!videoContainerElement) {
      throw new Error('Video container element not provided or not found');
    }

    await initZoom();
    mediaStream = client.getMediaStream();

    await client.join(sessionName, token, userName, null);
    console.log('Joined Zoom session successfully!');

    await mediaStream.startAudio();
    // Only start video if it's not the teacher's self-view being managed elsewhere
    if (client.getCurrentUserInfo().userId !== currentTeacherId) {
        await mediaStream.startVideo();
    }
    console.log('Local audio and video started (if applicable).');

    // Helper function to update video state for a user
    const updateParticipantVideo = (userId) => {
      const user = client.getUser(userId);
      if (user) {
        renderParticipantVideo(user);
      }
    };

    // Set up listeners for all video-related events
    client.on('peer-video-state-change', (payload) => {
      console.log('Event: peer-video-state-change', payload);
      updateParticipantVideo(payload.userId);
    });

    client.on('user-added', (payload) => {
      console.log('Event: user-added', payload);
      payload.forEach(user => updateParticipantVideo(user.userId));
    });

    client.on('user-removed', (payload) => {
      console.log('Event: user-removed', payload);
      payload.forEach(user => stopRenderParticipantVideo(user.userId));
    });

    client.on('user-updated', (payload) => {
        console.log('Event: user-updated', payload);
        payload.forEach(user => updateParticipantVideo(user.userId));
    });

    // Render videos for all users already in the session (including self, if not teacher)
    client.getAllUser().forEach(user => {
        updateParticipantVideo(user.userId);
    });

  } catch (error) {
    console.error('Error starting video session:', error);
    // Propagate the error so Join.vue can handle it (e.g., for VIDEO_USER_FORBIDDEN_CAPTURE)
    throw error; 
  }
};

export const leaveVideoSession = async () => {
  if (client) {
    console.log('Leaving video session.');
    try {
        await client.leave();
    } catch (e) {
        console.error('Error during client.leave():', e);
    }
  }
  // Clean up all video elements from the container
  if (videoContainerElement) {
    videoContainerElement.innerHTML = '';
  }
  mediaStream = null;
  videoContainerElement = null;
  currentTeacherId = null;
};

// Export mediaStream for external controls if needed
export { mediaStream };