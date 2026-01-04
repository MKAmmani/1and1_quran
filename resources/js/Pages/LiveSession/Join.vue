<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';
import { router, usePage } from '@inertiajs/vue3';
import client, { initZoom } from '@/zoom/client';

const $page = usePage();

const props = defineProps({
  liveSession: Object,
  sessionName: String,
  userName: String,
});

// Reactive variables
const isChatOpen = ref(false); // Start with chat closed by default
const messages = ref([]);
const newMessage = ref('');
const chatMessagesRef = ref(null);
const isVideoOn = ref(false); // Student's video is off by default
const isAudioOn = ref(true);
const participants = ref([]);

const currentVerses = ref([]);
const isQuranView = ref(false);
const selectedSurah = ref(null);
const selectedAyah = ref(null);

let mediaStream = null;

// Sync participants with Zoom client
const syncParticipants = () => {
  participants.value = client.getAllUser()
};

// Video and audio controls
const toggleVideo = async () => {
  try {
    const mediaStream = client.getMediaStream();
    if (isVideoOn.value) {
      await mediaStream.stopVideo();
      await removeSelfVideo();
    } else {
      await mediaStream.startVideo();
      await renderSelfVideo();
    }
    isVideoOn.value = !isVideoOn.value;
  } catch (error) {
    console.error('Error toggling video:', error);
    if (error.errorCode === 6103) {
      alert('Camera is already in use by another program. Please close other applications using the camera and try again.');
    } else {
      alert('Error toggling video: ' + (error.reason || error.message));
    }
  }
};

const toggleAudio = async () => {
  try {
    const mediaStream = client.getMediaStream();
    if (isAudioOn.value) {
      await mediaStream.muteAudio();
    } else {
      await mediaStream.unmuteAudio();
    }
    isAudioOn.value = !isAudioOn.value;
  } catch (error) {
    console.error('Error toggling audio:', error);
  }
};

const toggleScreenShare = async () => {
  // Students are not allowed to share their screen
  console.log("Students cannot share screen.");
};

const renderSelfVideo = async () => {
  const selfId = client.getCurrentUserInfo().userId;
  const selfParticipant = { userId: selfId, bVideoOn: true };
  const videoContainer = document.getElementById('video-container');
  const videoId = `video-${selfId}`;
  if (!document.getElementById(videoId)) {
    try {
      let videoPlayerElement = document.createElement('video-player');
      let videoElement = await mediaStream.attachVideo(selfId, 3);
      videoPlayerElement.id = videoId;
      // Make video player fill the container
      videoPlayerElement.style.width = '100%';
      videoPlayerElement.style.height = '100%';
      videoPlayerElement.style.position = 'absolute';
      videoPlayerElement.style.top = '0';
      videoPlayerElement.style.left = '0';
      // Style the video element to fill and maintain aspect ratio
      videoElement.style.width = '100%';
      videoElement.style.height = '100%';
      videoElement.style.objectFit = 'contain';
      videoPlayerElement.appendChild(videoElement);
      videoContainer.appendChild(videoPlayerElement);
      console.log(`Rendering self video for user ${selfId}`);
    } catch (e) {
      console.error(`Error rendering self video for user ${selfId}`, e);
    }
  }
};

const removeSelfVideo = async () => {
  const selfId = client.getCurrentUserInfo().userId;
  const videoId = `video-${selfId}`;
  const videoElement = document.getElementById(videoId);
  if (videoElement) {
    videoElement.remove();
    console.log(`Removed self video for user ${selfId}`);
  }
  if (mediaStream) {
    try {
      await mediaStream.detachVideo(selfId);
    } catch (e) {
      console.error('Error detaching self video:', e);
    }
  }
};


// Function to toggle chat visibility
const toggleChat = () => {
  isChatOpen.value = !isChatOpen.value;
};

// Function to scroll chat to bottom
const scrollToBottom = () => {
  nextTick(() => {
    if (chatMessagesRef.value) {
      chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight;
    }
  });
};

// Function to open chat
const openChat = () => {
  isChatOpen.value = true;
};

// Function to close chat
const closeChat = () => {
  isChatOpen.value = false;
};

// Each session starts with a fresh chat - no historical messages loaded
const fetchMessages = async () => {
  // No need to fetch old messages - each session starts fresh
  messages.value = [];
};

// Send a new message
const sendMessage = async () => {
  if (!newMessage.value.trim()) return;

  const messageText = newMessage.value.trim();
  newMessage.value = ''; // Clear the input immediately

  try {
    // Optimistically add the message to the local array
    const tempMessage = {
      id: Date.now(), // Temporary ID
      message: messageText,
      user: $page.props.auth.user,
      created_at: new Date().toISOString(),
      isTemp: true // Mark as temporary
    };
    messages.value = [...messages.value, tempMessage];
    scrollToBottom();

    const response = await axios.post('/chat-messages', {
      live_session_id: props.liveSession.id,
      message: messageText
    });

    // Replace the temporary message with the real one from the server
    const realMessage = response.data;
    messages.value = messages.value.map(msg =>
      msg.isTemp && msg.message === messageText ? realMessage : msg
    );
  } catch (error) {
    console.error('Error sending message:', error);
    // Remove the temporary message on error
    messages.value = messages.value.filter(msg => !msg.isTemp || msg.message !== messageText);
    newMessage.value = messageText; // Restore the input
    alert('Failed to send message. Please try again.');
  }
};

// Format time for display
const formatTime = (timeString) => {
  if (!timeString) return '';
  const date = new Date(timeString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Listen for real-time chat messages
let echoListener = null;

const token = ref(null);
let refreshTokenInterval = null;

// Function to refresh the JWT token
const refreshToken = async () => {
  try {
    const { data } = await axios.post('/video-sdk/token', {
      session_name: props.sessionName,
      role: 0 // 0 for participant/student
    });
    token.value = data.token;
    await client.renewToken(token.value);
    console.log('Zoom token refreshed successfully.');
  } catch (error) {
    console.error('Error refreshing Zoom token:', error);
  }
};

const logout = () => {
    router.post(route("logout"));
};

const leaveSession = async () => {
  try {
    // Detach all videos
    for (const participant of participants.value) {
      const videoElement = document.getElementById(`video-${participant.userId}`);
      if (videoElement) {
        videoElement.remove();
      }
      if (mediaStream) {
        try {
          await mediaStream.detachVideo(participant.userId);
        } catch (e) {
          console.error('Error detaching video:', e);
        }
      }
    }

    // Leave Zoom session
    await client.leave();

    // Redirect to student dashboard or another page
    router.get(route('student.dashboard'));
  } catch (e) {
    console.error('Failed to leave session', e);
  }
};

const canvas = ref(null);

onMounted(async () => {
  console.log('Join.vue props:', props);
  const videoContainer = document.getElementById('video-container');

  try {
    await initZoom();
    console.log('Zoom SDK initialized.');

    const { data } = await axios.post('/video-sdk/token', {
      session_name: props.sessionName,
      role: 0,
    });
    token.value = data.token;
    console.log('Successfully fetched token.');

    await client.join(props.sessionName, token.value, props.userName);
    console.log('Joined Zoom session successfully!');

    mediaStream = client.getMediaStream();

    // Start local audio
    await mediaStream.startAudio({ audio: true });
    console.log('Local audio started.');

    const renderVideo = async (participant) => {
      if (participant.bVideoOn) {
        const videoId = `video-${participant.userId}`;
        if (!document.getElementById(videoId)) {
            try {
              let videoPlayerElement = document.createElement('video-player');
              let videoElement = await mediaStream.attachVideo(participant.userId, 3);
              videoPlayerElement.id = videoId;
              // Make video player fill the container
              videoPlayerElement.style.width = '100%';
              videoPlayerElement.style.height = '100%';
              videoPlayerElement.style.position = 'absolute';
              videoPlayerElement.style.top = '0';
              videoPlayerElement.style.left = '0';
              // Style the video element to fill and maintain aspect ratio
              videoElement.style.width = '100%';
              videoElement.style.height = '100%';
              videoElement.style.objectFit = 'contain';
              videoPlayerElement.appendChild(videoElement);
              videoContainer.appendChild(videoPlayerElement);
              console.log(`Rendering video for user ${participant.userId}`);
            } catch (e) {
              console.error(`Error rendering video for user ${participant.userId}`, e);
            }
        }
      }
    };

    const removeVideo = async (userId) => {
      const videoId = `video-${userId}`;
      const videoElement = document.getElementById(videoId);
      if (videoElement) {
        videoElement.remove();
        console.log(`Removed video for user ${userId}`);
      }
      await mediaStream.detachVideo(userId);
    };

    // Render videos for participants already in the session
    syncParticipants();
    console.log('Existing participants:', participants.value);
    for (const participant of participants.value) {
      await renderVideo(participant);
    }
    
    // Handle new users joining
    client.on('user-added', async (payload) => {
      console.log('User added:', payload);
      syncParticipants();
      for (const user of payload) {
        await renderVideo(user);
      }
    });

    // Handle users leaving
    client.on('user-removed', async (payload) => {
      console.log('User removed:', payload);
      syncParticipants();
      for (const user of payload) {
        await removeVideo(user.userId);
      }
    });
    
    // Handle user video state updates
    client.on('user-updated', async (payload) => {
      console.log('User updated:', payload);
      syncParticipants();
      for (const user of payload) {
        if(user.bVideoOn) {
          await renderVideo(user);
        } else {
          await removeVideo(user.userId);
        }
      }
    });

    // Handle remote user's video state changes
    client.on('peer-video-state-change', async (payload) => {
      console.log('Peer video state change:', payload);
      if (payload.action === 'Start') {
        await renderVideo(payload);
      } else if (payload.action === 'Stop') {
        await removeVideo(payload.userId);
      }
    });

    await fetchMessages();
    console.log('Initial messages fetched.');

    if (window.Echo) {
      echoListener = window.Echo.private(`live-session.${props.liveSession.id}`)
        .listen('.chat.message', (e) => {
          const messageExists = messages.value.some(msg => msg.id === e.chatMessage.id);
          if (!messageExists) {
            // Replace any temporary message with the same content
            const tempIndex = messages.value.findIndex(msg => msg.isTemp && msg.message === e.chatMessage.message);
            if (tempIndex !== -1) {
              messages.value[tempIndex] = e.chatMessage;
            } else {
              messages.value = [...messages.value, e.chatMessage];
            }
            scrollToBottom();
          }
        })
        .listen('.quran.verse.changed', async (e) => {
            console.log('Received Quran verse change event:', e);
            try {
                const response = await axios.get(`/quran/ayahs/${e.surah}/${e.ayah}/4`);
                console.log('Quran API response received');
                currentVerses.value = response.data.verses;
                selectedSurah.value = e.surahName;
                selectedAyah.value = e.ayah;
                isQuranView.value = true;
            } catch (error) {
                console.error('Error fetching verses:', error);
            }
        });
      console.log('Echo listener started.');
    }

  } catch (error) {
    console.error('Error in onMounted hook:', error);
    if (error.response) {
      console.error('Error response:', error.response.data);
    }
  }
});

onUnmounted(() => {
  // Clean up the Echo listener and token refresh interval
  if (window.Echo && props.liveSession?.id) {
    window.Echo.leave(`live-session.${props.liveSession.id}`);
  }
  if (refreshTokenInterval) {
    clearInterval(refreshTokenInterval);
  }
  if (client) {
    client.leave();
  }
});
</script>

<template>
  <body
      class="bg-background-light dark:bg-background-dark font-display text-text-primary-light dark:text-text-primary-dark antialiased h-screen overflow-hidden flex transition-colors duration-200">
    <aside
        class="w-64 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex-shrink-0 flex flex-col h-full overflow-y-auto hidden lg:flex">
      <div class="p-6 flex items-center justify-center">
        <img alt="Quran Classes Logo" class="rounded-full w-14 h-14 object-cover" src="/images/app_logo.jpg" />
      </div>
      <nav class="flex-1 px-4 space-y-2 mt-4">
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors">
          <span class="material-icons-outlined text-xl">home</span>
          <span class="font-medium text-sm">Home</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors">
          <span class="material-icons-outlined text-xl">description</span>
          <span class="font-medium text-sm">Prepare Class</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors"
            >
          <span class="material-icons-outlined text-xl">people</span>
          <span class="font-medium text-sm">Students</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors">
          <span class="material-icons-outlined text-xl">campaign</span>
          <span class="font-medium text-sm">Announcement</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors">
          <span class="material-icons-outlined text-xl">videocam</span>
          <span class="font-medium text-sm">Live class</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors"
            >
          <span class="material-icons-outlined text-xl">library_books</span>
          <span class="font-medium text-sm">Quran library</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors"
            >
          <span class="material-icons-outlined text-xl">history</span>
          <span class="font-medium text-sm">Class history</span>
        </a>
      </nav>
      <div class="px-4 pb-6 mt-auto space-y-2">
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors">
          <span class="material-icons-outlined text-xl">settings</span>
          <span class="font-medium text-sm">Settings</span>
        </a>
        <button @click="logout" class="w-full flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-opacity-10 hover:text-primary rounded-xl transition-colors">
          <span class="material-icons-outlined text-xl">logout</span>
          <span class="font-medium text-sm">Logout</span>
        </button>
      </div>
    </aside>
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
      <header
          class="h-20 flex items-center justify-between px-8 bg-background-light dark:bg-background-dark shrink-0">
        <div class="flex flex-col">
          <h2 class="text-xl font-semibold text-text-primary-light dark:text-text-primary-dark">Live Quran Class</h2>
          <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">{{ liveSession.title }}</p>
        </div>
        <div class="flex items-center gap-6">
          <div class="flex flex-col items-end">
            <span class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ new Date().toDateString() }}</span>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-primary">
              <span class="material-icons-outlined">person</span>
            </div>
            <div class="flex flex-col">
              <span class="text-sm font-semibold leading-tight">{{ userName }}</span>
              <span
                  class="text-xs text-text-secondary-light dark:text-text-secondary-dark">Student</span>
            </div>
          </div>
        </div>
      </header>
      <div class="flex-1 flex overflow-hidden p-6 gap-6 pt-0">
        <div class="flex-1 flex flex-col min-w-0 h-full gap-6">
          <div
              class="bg-surface-light dark:bg-surface-dark rounded-2xl p-4 shadow-sm border border-border-light dark:border-border-dark flex items-center justify-between flex-wrap gap-4 shrink-0">
            <div>
              <h3 class="text-primary font-medium text-lg">{{ liveSession.title }}</h3>
              <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Teacher: {{ liveSession.teacher?.first_name }} {{ liveSession.teacher?.last_name }}</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex -space-x-3">
                <!-- Real participants -->
                <div v-for="participant in participants.slice(0, 3)" :key="participant.userId" class="w-10 h-10 rounded-full border-2 border-white dark:border-surface-dark bg-primary flex items-center justify-center relative">
                  <span class="text-xs text-white font-medium">{{ (participant.displayName || participant.userName || '?').charAt(0).toUpperCase() }}</span>
                  <!-- Video indicator -->
                  <div v-if="participant.bVideoOn" class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border border-white dark:border-surface-dark"></div>
                </div>
                <!-- Show +count if more than 3 participants -->
                <div v-if="participants.length > 3" class="w-10 h-10 rounded-full border-2 border-white dark:border-surface-dark bg-gray-400 flex items-center justify-center">
                  <span class="text-xs text-white font-medium">+{{ participants.length - 3 }}</span>
                </div>
              </div>
              <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark ml-2">{{ participants.length }} people on call</span>
            </div>
            <div class="flex items-center gap-4">
              <div
                  class="flex items-center gap-2 bg-white dark:bg-gray-800 border border-border-light dark:border-border-dark rounded-lg px-3 py-1.5 shadow-sm">
                <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                <span class="font-medium text-sm">Live</span>
              </div>
              <div
                  class="bg-gray-100 dark:bg-gray-700 text-text-primary-light dark:text-text-primary-dark font-mono px-4 py-1.5 rounded-lg text-sm border border-border-light dark:border-border-dark">
                {{ new Date().toLocaleTimeString() }}
              </div>
              <button @click="leaveSession"
                  class="bg-[#EB5757] hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Leave Session
              </button>
            </div>
          </div>
          <div
              class="flex-1 bg-surface-light dark:bg-surface-dark rounded-2xl border border-border-light dark:border-border-dark flex flex-col overflow-hidden relative shadow-sm">
            <div
                class="flex-1 relative bg-[#FDFDFD] dark:bg-[#252525] flex items-center justify-center p-4 overflow-auto">
                <div v-if="isQuranView" class="max-w-4xl w-full h-full flex flex-col items-center justify-center relative text-center">
                    <div v-if="currentVerses && currentVerses.length > 0" class="w-full h-full overflow-y-auto space-y-6 p-4">
                        <div v-for="verse in currentVerses" :key="verse.id" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-center mb-4">
                                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium">
                                    Verse {{ verse.verse_number }}
                                </span>
                            </div>
                            <p class="text-3xl font-arabic leading-loose mb-4 text-right" dir="rtl" style="font-family: 'Amiri', 'Noto Sans Arabic', 'Tajawal', serif;">
                                {{ verse.text_uthmani }}
                            </p>
                            <p class="text-base text-gray-600 dark:text-gray-300 text-left">
                                {{ verse.translations ? verse.translations[0]?.text : 'Translation loading...' }}
                            </p>
                        </div>
                    </div>
                    <div v-else class="text-gray-500">Loading verses...</div>
                </div>
              <div v-else class="max-w-4xl w-full h-full flex items-center justify-center relative">
                <!-- Video container for Zoom SDK -->
                <div id="video-container" class="w-full h-full flex items-center justify-center bg-black rounded-lg video-player-container" style="z-index: 1;"></div>
                <div
                    class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-3 bg-black/70 backdrop-blur-sm px-4 py-2 rounded-full z-10">
                  <button @click="toggleScreenShare" class="text-white hover:text-primary transition-colors p-2">
                    <span class="material-icons-outlined text-xl">present_to_all</span>
                  </button>
                  <button @click="toggleAudio" class="text-white hover:text-primary transition-colors p-2 relative">
                    <span class="material-icons-outlined text-xl">{{ isAudioOn ? 'mic' : 'mic_off' }}</span>
                    <span v-if="isAudioOn" class="absolute top-2 right-2 w-2 h-2 bg-green-500 rounded-full"></span>
                    <span v-else class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                  </button>
                  <button @click="toggleVideo" class="text-white hover:text-primary transition-colors p-2 relative">
                    <span class="material-icons-outlined text-xl">{{ isVideoOn ? 'videocam' : 'videocam_off' }}</span>
                    <span v-if="!isVideoOn" class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                  </button>
                </div>
              </div>
            </div>
            <div
                class="h-16 border-t border-border-light dark:border-border-dark flex items-center justify-between px-6 bg-surface-light dark:bg-surface-dark">
              <button
                  class="bg-primary hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Full quran view
              </button>
              <div class="relative">
                <button
                    class="flex items-center gap-10 px-4 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-sm">
                  <span class="text-text-secondary-light dark:text-text-secondary-dark">Select
                      surah</span>
                  <span class="material-icons-outlined text-lg">expand_more</span>
                </button>
              </div>
              <div class="flex items-center gap-4">
                <div class="flex flex-col items-center cursor-pointer group">
                  <button
                      class="w-10 h-10 bg-primary/10 dark:bg-primary/20 text-primary flex items-center justify-center rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-icons text-2xl rotate-180">play_arrow</span>
                  </button>
                  <span
                      class="text-[10px] text-text-secondary-light dark:text-text-secondary-dark mt-1">Next</span>
                </div>
                <div class="flex flex-col items-center cursor-pointer group">
                  <button
                      class="w-10 h-10 bg-primary/10 dark:bg-primary/20 text-primary flex items-center justify-center rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-icons text-2xl">play_arrow</span>
                  </button>
                  <span
                      class="text-[10px] text-text-secondary-light dark:text-text-secondary-dark mt-1">Prev</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <aside v-show="isChatOpen"
            class="w-80 bg-surface-light dark:bg-surface-dark rounded-2xl border border-border-light dark:border-border-dark flex flex-col shadow-sm shrink-0">
          <div
              class="h-16 flex items-center justify-between px-6 border-b border-border-light dark:border-border-dark">
            <h3
                class="font-semibold text-lg relative after:content-[''] after:absolute after:-bottom-[21px] after:left-0 after:w-full after:h-[2px] after:bg-primary">
              Chats</h3>
            <button @click="closeChat"
                class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary transition-colors">
              <span class="material-icons-outlined">close</span>
            </button>
          </div>
          <div class="flex-1 overflow-y-auto p-4 space-y-6 scrollbar-hide" ref="chatMessagesRef">
            <div v-for="message in messages" :key="message.id" class="flex flex-col gap-1">
              <div class="flex items-start gap-2" v-if="message.user_id != $page.props.auth.user.id">
                <img :alt="message.user?.name || 'User'" class="w-8 h-8 rounded-full object-cover"
                    :src="message.user?.avatar || `https://ui-avatars.com/api/?name=${message.user?.name || 'User'}&color=fff&background=5cb65f`" />
                <div class="flex flex-col max-w-[85%]">
                  <span class="text-xs font-semibold mb-0.5">{{ message.user?.name || 'Unknown User' }}</span>
                  <div
                      class="bg-gray-100 dark:bg-gray-800 p-3 rounded-tr-xl rounded-br-xl rounded-bl-xl text-sm text-text-primary-light dark:text-text-primary-dark">
                    {{ message.message }}
                  </div>
                  <span
                      class="text-[10px] text-text-secondary-light dark:text-text-secondary-dark self-end mt-1">{{ formatTime(message.created_at) }}</span>
                </div>
              </div>
              <div class="flex flex-col max-w-[85%] items-end" v-else>
                <div
                    class="bg-primary text-white p-3 rounded-tl-xl rounded-br-xl rounded-bl-xl text-sm relative">
                  {{ message.message }}
                </div>
                <div class="flex items-center gap-1 mt-1">
                  <span
                      class="text-[10px] text-text-secondary-light dark:text-text-secondary-dark">{{ formatTime(message.created_at) }}</span>
                  <span class="material-icons text-[10px] text-primary">done_all</span>
                </div>
              </div>
            </div>
          </div>
          <div class="p-4 border-t border-border-light dark:border-border-dark">
            <div class="relative">
              <input
                  v-model="newMessage"
                  @keyup.enter="sendMessage"
                  class="w-full bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl py-3 pl-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent placeholder-text-secondary-light dark:placeholder-text-secondary-dark text-text-primary-light dark:text-text-primary-dark"
                  placeholder="Write your message" type="text" />
              <button
                  @click="sendMessage"
                  class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1.5 text-primary hover:bg-primary/10 rounded-lg transition-colors">
                <span class="material-icons-outlined text-xl rotate-[-30deg]">send</span>
              </button>
            </div>
          </div>
        </aside>
      </div>
    </main>
    <div v-show="!isChatOpen" class="fixed bottom-6 right-6 z-50">
      <button @click="openChat" class="bg-primary text-white p-4 rounded-full shadow-lg hover:bg-teal-700 transition-colors">
        <span class="material-icons-outlined text-2xl">chat</span>
      </button>
    </div>
    <div
        class="lg:hidden fixed bottom-0 w-full bg-surface-light dark:bg-surface-dark border-t border-border-light dark:border-border-dark flex justify-around py-3 z-50">
      <a class="flex flex-col items-center text-primary" href="#">
        <span class="material-icons-outlined">videocam</span>
        <span class="text-[10px] mt-1">Live</span>
      </a>
      <a class="flex flex-col items-center text-text-secondary-light dark:text-text-secondary-dark" href="#">
        <span class="material-icons-outlined">home</span>
        <span class="text-[10px] mt-1">Home</span>
      </a>
      <a class="flex flex-col items-center text-text-secondary-light dark:text-text-secondary-dark" href="#">
        <span class="material-icons-outlined">chat</span>
        <span class="text-[10px] mt-1">Chat</span>
      </a>
    </div>
  </body>
</template>
