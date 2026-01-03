import client, { initZoom } from './client';

let mediaStream = null;

const renderVideo = async (canvas, userId, isSelf) => {
  if (!mediaStream) return;
  const dimensions = isSelf
    ? { width: 320, height: 180, x: canvas.width - 330, y: canvas.height - 190 }
    : { width: 1280, height: 720, x: 0, y: 0 };

  try {
    await mediaStream.renderVideo(canvas, userId, dimensions.width, dimensions.height, dimensions.x, dimensions.y, 2);
    console.log(`Successfully rendered video for userId: ${userId}`);
  } catch (error) {
    console.error(`Error rendering video for ${userId}:`, error);
  }
};

const stopRenderVideo = async (canvas, userId) => {
  if (!mediaStream) return;
  try {
    await mediaStream.stopRenderVideo(canvas, userId);
    console.log(`Successfully stopped video for userId: ${userId}`);
  } catch (error) {
    console.error(`Error stopping video for ${userId}:`, error);
  }
};

const handlePeerVideoStateChange = (canvas) => (payload) => {
  console.log('Event: peer-video-state-change', payload);
  if (payload.action === 'Start') {
    renderVideo(canvas, payload.userId, false); // Remote users are never "self"
  } else if (payload.action === 'Stop') {
    stopRenderVideo(canvas, payload.userId);
  }
};

export const startVideoSession = async (sessionName, token, userName) => {
  try {
    console.log('Attempting to start video session...');
    await initZoom();
    const canvas = document.getElementById('zoom-video-canvas');
    if (!canvas) {
      throw new Error('Canvas element not found');
    }

    mediaStream = client.getMediaStream();

    await client.join(sessionName, token, userName, null);
    console.log('Joined Zoom session successfully!');

    await mediaStream.startAudio();
    await mediaStream.startVideo();
    console.log('Local audio and video started.');

    // Render self-view
    renderVideo(canvas, client.getCurrentUserInfo().userId, true);

    // Set up listener for remote video
    client.on('peer-video-state-change', handlePeerVideoStateChange(canvas));

  } catch (error) {
    console.error('Error starting video session:', error);
  }
};

export const leaveVideoSession = () => {
  if (client) {
    console.log('Leaving video session.');
    client.leave();
  }
  mediaStream = null;
};
