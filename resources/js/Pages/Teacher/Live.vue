t <script setup>
import { ref, onMounted, onUnmounted, nextTick, watch, computed } from 'vue'
import axios from 'axios'
import client, { initZoom } from '@/zoom/client'
import { router, usePage, Link } from '@inertiajs/vue3'

/* ---------------- ROUTE HELPERS ---------------- */
const isCurrentRoute = (routeName) => route().current(routeName)

const $page = usePage()

/* ---------------- PROPS ---------------- */
const props = defineProps({
  liveSessionId: Number,
  sessionName: String,
  userName: String,
  sessionToken: String,
  liveSession: Object
})

/* ---------------- TEMPLATE STATE ---------------- */
const videoContainer = ref(null)
const sidebarOpen = ref(false)

const participants = ref([])
const joinRequests = ref([])
const isChatOpen = ref(false)
const messages = ref([])
const newMessage = ref('')
const chatMessagesRef = ref(null)

const isVideoOn = ref(true)
const isAudioOn = ref(true)

const token = ref(props.sessionToken)

let mediaStream = null
let zoomReady = false

const sessionStartTime = ref(null);
const sessionDuration = ref('00:00');
let durationInterval = null;

const surahs = ref([]);
const selectedSurah = ref(null);
const selectedAyah = ref(null);
const currentVerses = ref([]);
const isQuranView = ref(false);
const surahSearchQuery = ref('');
const isSurahDropdownOpen = ref(false);

watch(selectedSurah, (newSurah, oldSurah) => {
  if (newSurah !== oldSurah) {
    selectedAyah.value = null;
  }
});


const fetchSurahs = async () => {
  try {
    const response = await axios.get('/quran/chapters');
    surahs.value = response.data.chapters;
  } catch (error) {
    console.error('Error fetching surahs:', error);
  }
};

const displayVerse = async () => {
  if (!selectedSurah.value || !selectedAyah.value) {
    alert('Please select a Surah and Ayah.');
    return;
  }
  try {
    // Broadcast to other participants
    await axios.post(`/live-sessions/${props.liveSessionId}/quran-verse`, {
      surah: selectedSurah.value.id,
      ayah: selectedAyah.value,
      surah_name: selectedSurah.value.name_simple,
    });

    // Immediately fetch and display for the teacher
    const response = await axios.get(`/quran/ayahs/${selectedSurah.value.id}/${selectedAyah.value}/4`);
    currentVerses.value = response.data.verses;
    isQuranView.value = true;
  } catch (error) {
    console.error('Error displaying verse:', error);
  }
};

const displayNextVerses = async () => {
  if (!selectedSurah.value || !selectedAyah.value) return;
  
  const nextAyah = Math.min(selectedAyah.value + 4, selectedSurah.value.verses_count);
  if (nextAyah !== selectedAyah.value) {
    selectedAyah.value = nextAyah;
    await displayVerse();
  }
};

const displayPreviousVerses = async () => {
  if (!selectedSurah.value || !selectedAyah.value) return;
  
  const prevAyah = Math.max(selectedAyah.value - 4, 1);
  if (prevAyah !== selectedAyah.value) {
    selectedAyah.value = prevAyah;
    await displayVerse();
  }
};

const switchToVideoView = () => {
  isQuranView.value = false;
  currentVerses.value = [];
};

const selectSurah = (surah) => {
  selectedSurah.value = surah;
  isSurahDropdownOpen.value = false;
};

const clearSurahSelection = () => {
  selectedSurah.value = null;
  selectedAyah.value = null;
};




/* ---------------- UI HELPERS ---------------- */
const openChat = () => (isChatOpen.value = true)
const closeChat = () => (isChatOpen.value = false)

const toggleFullScreen = () => {
  if (!document.fullscreenElement) document.documentElement.requestFullscreen()
  else document.exitFullscreen()
}

const logout = () => router.post(route('logout'))

/* ---------------- PARTICIPANTS ---------------- */
const syncParticipants = () => {
  participants.value = client.getAllUser()
}

/* ---------------- MEDIA CONTROLS ---------------- */
const toggleVideo = async () => {
  if (!mediaStream) return
  try {
    isVideoOn.value ? await mediaStream.stopVideo() : await mediaStream.startVideo()
    isVideoOn.value = !isVideoOn.value
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
  if (!mediaStream) return
  isAudioOn.value ? await mediaStream.muteAudio() : await mediaStream.unmuteAudio()
  isAudioOn.value = !isAudioOn.value
}

const toggleScreenShare = async () => {
  if (!mediaStream) return
  await mediaStream.startShareScreen()
}

/* ---------------- CHAT FUNCTIONS ---------------- */
const fetchMessages = async () => {
  // Each session starts with a fresh chat - no historical messages loaded
  messages.value = [];
}

const sendMessage = async () => {
  if (!newMessage.value.trim()) return

  const messageText = newMessage.value.trim()
  newMessage.value = '' // Clear the input immediately

  try {
    // Optimistically add the message to the local array
    const tempMessage = {
      id: Date.now(), // Temporary ID
      message: messageText,
      user: $page.props.auth.user,
      created_at: new Date().toISOString(),
      isTemp: true // Mark as temporary
    }
    messages.value = [...messages.value, tempMessage]
    scrollToBottom()

    const response = await axios.post('/chat-messages', {
      live_session_id: props.liveSessionId,
      message: messageText
    })

    // Replace the temporary message with the real one from the server
    const realMessage = response.data
    messages.value = messages.value.map(msg =>
      msg.isTemp && msg.message === messageText ? realMessage : msg
    )
  } catch (error) {
    console.error('Error sending message:', error)
    // Remove the temporary message on error
    messages.value = messages.value.filter(msg => !msg.isTemp || msg.message !== messageText)
    newMessage.value = messageText // Restore the input
    alert('Failed to send message. Please try again.');
  }
}

// Format time for display
const formatTime = (timeString) => {
  if (!timeString) return ''
  const date = new Date(timeString)
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

// Function to scroll chat to bottom
const scrollToBottom = () => {
  nextTick(() => {
    if (chatMessagesRef.value) {
      chatMessagesRef.value.scrollTop = chatMessagesRef.value.scrollHeight
    }
  })
}

/* ---------------- JOIN REQUESTS ---------------- */
const approveRequest = async (request) => {
  try {
    // Call API to approve
    await axios.post(`/join-requests/${request.id}/approve`)
    joinRequests.value = joinRequests.value.filter(r => r.id !== request.id)
  } catch (e) {
    console.error('Failed to approve join request', e)
  }
}

const declineRequest = async (request) => {
  try {
    // Call API to decline
    await axios.post(`/join-requests/${request.id}/decline`)
    joinRequests.value = joinRequests.value.filter(r => r.id !== request.id)
  } catch (e) {
    console.error('Failed to decline join request', e)
  }
}

/* ---------------- FETCH JOIN REQUESTS ---------------- */
const fetchJoinRequests = async () => {
  try {
    if (!props.liveSessionId) return;
    const response = await axios.get(`/live-sessions/${props.liveSessionId}/join-requests`)
    joinRequests.value = response.data
  } catch (e) {
    console.error('Failed to fetch join requests', e)
  }
}

/* ---------------- END SESSION ---------------- */
const endSession = async () => {
  try {
    // Call API to end the session
    if (props.liveSessionId) {
      await axios.post(`/live-sessions/${props.liveSessionId}/end`)
    }

    // Detach all videos
    participants.value.forEach(u => mediaStream?.detachVideo(u.userId))

    // Leave Zoom session
    await client.leave()
    participants.value = []
    zoomReady = false

    // Redirect to dashboard or another page
    router.get(route('teacher.index'))
  } catch (e) {
    console.error('Failed to end session', e)
  }
}

const handleResize = () => {
    sidebarOpen.value = window.innerWidth >= 768; // md breakpoint is 768px
};

/* ---------------- ZOOM LIFECYCLE ---------------- */
onMounted(async () => {
  // Always fetch surahs regardless of session state
  await fetchSurahs();

  handleResize();
  window.addEventListener('resize', handleResize);

  await nextTick()
  if (!props.sessionName || !props.userName) return

  // Get the video container element
  const videoContainerElement = document.getElementById('video-container')
  if (!videoContainerElement) {
    console.error('Video container not found')
    return
  }

  // Ensure the container has the required class for Zoom SDK
  if (!videoContainerElement.classList.contains('video-player-container')) {
    videoContainerElement.classList.add('video-player-container')
  }

  // Clean up any existing video-player elements
  const existingPlayers = videoContainerElement.querySelectorAll('video-player')
  existingPlayers.forEach(player => player.remove())

  try {
    await initZoom()
    mediaStream = client.getMediaStream()

    if (!token.value) {
      const { data } = await axios.post('/video-sdk/token', {
        session_name: props.sessionName,
        role: 1
      })
      token.value = data.token
    }

    await client.join(props.sessionName, token.value, props.userName)
    zoomReady = true

    sessionStartTime.value = new Date();
    durationInterval = setInterval(() => {
      if (sessionStartTime.value) {
        const now = new Date();
        const diff = now - sessionStartTime.value;
        const minutes = Math.floor(diff / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);
        sessionDuration.value = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
      }
    }, 1000);

    await mediaStream.startAudio()
    await mediaStream.startVideo()

    syncParticipants()

    // Define renderVideo function here, inside onMounted
    const renderVideo = async (participant) => {
      if (participant.bVideoOn) {
        const videoId = `video-${participant.userId}`;
        if (!document.getElementById(videoId)) {
            try {
              let videoPlayerElement = document.createElement('video-player');
              videoPlayerElement.id = videoId;
              // Make video player fill the container
              videoPlayerElement.style.width = '100%';
              videoPlayerElement.style.height = '100%';
              videoPlayerElement.style.position = 'absolute';
              videoPlayerElement.style.top = '0';
              videoPlayerElement.style.left = '0';
              // Append video player to DOM first
              videoContainerElement.appendChild(videoPlayerElement);
              // Then attach video
              await mediaStream.attachVideo(participant.userId, videoPlayerElement);
              console.log(`Rendering video for user ${participant.userId}`);
            } catch (e) {
              console.error(`Error rendering video for user ${participant.userId}`, e);
            }
        }
      }
    };

    // Define removeVideo function here too
    const removeVideo = async (userId) => {
      const videoElement = document.getElementById(`video-${userId}`)
      if (videoElement) videoElement.remove()

      if (mediaStream) {
        try {
          await mediaStream.detachVideo(userId)
        } catch (e) {
          console.error('Error detaching video:', e)
        }
      }
    }

    // Render videos for existing participants
    for (const participant of participants.value) {
      if (participant.bVideoOn) {
        await renderVideo(participant)
      }
    }

    // Fetch join requests
    await fetchJoinRequests()

    // Fetch initial chat messages
    await fetchMessages()

    /* -------- ZOOM EVENTS -------- */
    client.on('user-added', ({ userId }) => {
      syncParticipants()
      const user = client.getAllUser().find(u => u.userId === userId)
      if (user?.bVideoOn) renderVideo(user)
    })

    client.on('user-removed', ({ userId }) => {
      syncParticipants()
      removeVideo(userId)
    })

    client.on('user-updated', (payload) => {
      syncParticipants()
      for (const user of payload) {
        if (user.bVideoOn) {
          renderVideo(user)
        } else {
          removeVideo(user.userId)
        }
      }
    })

    client.on('peer-video-state-change', (payload) => {
      const userId = payload.userId
      if (payload.action === 'Start') {
        const user = client.getAllUser().find(u => u.userId === userId)
        if (user) renderVideo(user)
      } else if (payload.action === 'Stop') {
        removeVideo(userId)
      }
    })

    /* -------- REAL-TIME EVENTS -------- */
    if (window.Echo) {
      window.Echo.private(`teacher.${$page.props.auth.user.id}`)
        .listen('.join.request.sent', (e) => {
          joinRequests.value.push(e.joinRequest)
        })

      window.Echo.private(`live-session.${props.liveSessionId}`)
        .listen('.chat.message', (e) => {
          const messageExists = messages.value.some(msg => msg.id === e.chatMessage.id)
          if (!messageExists) {
            // Replace any temporary message with the same content
            const tempIndex = messages.value.findIndex(msg => msg.isTemp && msg.message === e.chatMessage.message)
            if (tempIndex !== -1) {
              messages.value[tempIndex] = e.chatMessage
            } else {
              messages.value = [...messages.value, e.chatMessage]
            }
            scrollToBottom()
          }
        })
        .listen('.quran.verse.changed', async (e) => {
            console.log('Received Quran verse change event:', e);
            try {
                const response = await axios.get(`/quran/ayahs/${e.surah}/${e.ayah}/4`);
                console.log('Quran API response received');
                currentVerses.value = response.data.verses;
                isQuranView.value = true;
            } catch (error) {
                console.error('Error fetching verses:', error);
            }
        });
    }
  } catch (e) {
    console.error('Zoom init failed', e)
  }
})

/* ---------------- CLEANUP ---------------- */
onUnmounted(async () => {
  window.removeEventListener('resize', handleResize);
  if (!zoomReady) return
  try {
    participants.value.forEach(u => mediaStream?.detachVideo(u.userId))
    await client.leave()

    // Clean up video container
    const cleanupVideoContainerElement = videoContainer.value || document.getElementById('video-container');
    if (cleanupVideoContainerElement) {
      // Remove all video elements
      const videos = cleanupVideoContainerElement.querySelectorAll('video');
      videos.forEach(video => video.remove());
    }

    // Clean up Echo listener
    if (window.Echo) {
      window.Echo.leave(`teacher.${$page.props.auth.user.id}`)
      window.Echo.leave(`live-session.${props.liveSessionId}`)
    }
    if (durationInterval) {
      clearInterval(durationInterval);
    }
  } catch {}
})
</script>



<template>

    <body
        class="bg-background-light dark:bg-background-dark font-display text-text-primary-light dark:text-text-primary-dark antialiased h-screen overflow-hidden flex flex-col md:flex-row transition-colors duration-200">

        <!-- Mobile Header with Hamburger Menu -->
        <header class="md:hidden h-16 px-4 flex items-center justify-between flex-shrink-0 bg-background-light dark:bg-background-dark border-b border-border-light dark:border-border-dark z-20">
            <button @click="sidebarOpen = true" class="p-2 rounded-lg text-text-primary-light dark:text-text-primary-dark">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <img src="/images/app_logo.jpg" alt="App Logo" class="h-24 w-auto" />
            <div class="w-10"></div> <!-- Spacer for alignment -->
        </header>

        <!-- Mobile Sidebar Overlay -->
        <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>

        <aside
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed md:static md:translate-x-0 top-0 left-0 h-full w-64 md:w-72 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex-shrink-0 flex flex-col overflow-y-auto z-40 md:z-auto transition-transform duration-300 ease-in-out">
            <div class="md:hidden absolute inset-0 bg-white/30 dark:bg-gray-900/30 backdrop-blur-md -z-10 md:backdrop-blur-none md:bg-surface-light dark:md:bg-surface-dark"></div>
            <div class="p-4 md:p-8 mb-4 flex justify-center">
                <img src="/images/app_logo.jpg" alt="App Logo" class="h-40 w-auto" />
            </div>
            <nav class="flex-1 px-4 space-y-2">
                <Link :href="route('teacher.index')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('teacher.index'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('teacher.index')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">home</span>
                    <span class="font-medium">Home</span>
                </Link>
                <Link :href="route('prepre_class')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('prepre_class'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('prepre_class')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">edit_note</span>
                    <span class="font-medium">Prepare Class</span>
                </Link>
                <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-medium">Students</span>
                </a>
                <Link :href="route('announcement')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('announcement'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('announcement')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">campaign</span>
                    <span class="font-medium">Announcement</span>
                </Link>
                <Link :href="route('live')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('live'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('live')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">videocam</span>
                    <span class="font-medium">Live class</span>
                </Link>
                <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                    <span class="material-symbols-outlined">book_2</span>
                    <span class="font-medium">Quran library</span>
                </a>
                <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                    <span class="material-symbols-outlined">history_edu</span>
                    <span class="font-medium">Class history</span>
                </a>

            </nav>
            <div class="p-4 mt-auto">
                <Link :href="route('profile.edit')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('profile.edit'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('profile.edit')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-medium">Settings</span>
                </Link>
            </div>
            <div class="p-4">
                <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-logout-bg dark:bg-red-900/30 text-logout-text dark:text-red-400 font-medium hover:opacity-90 transition-all duration-200">
                    <span class="material-symbols-outlined transform rotate-180">logout</span>
                    <span>Logout</span>
                </Link>
            </div>
        </aside>
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-background-light dark:bg-background-dark relative">
            <header class="h-16 md:h-20 px-4 flex items-center justify-between flex-shrink-0 bg-background-light dark:bg-background-dark z-10 border-b md:border-b-0 border-border-light dark:border-border-dark">
                <div class="hidden md:block">
                    <h2 class="text-xl font-semibold text-text-primary-light dark:text-text-primary-dark">Assalaikum Alaykum, {{ userName }}</h2>
                </div>
                <div class="md:hidden text-base font-semibold text-text-primary-light dark:text-text-primary-dark">
                    Live Class
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
                                class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ $page.props.auth.user.email }}</span>
                        </div>
                    </div>
                    <button @click="toggleFullScreen"
                        class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary transition-colors">
                        <span class="material-icons-outlined">open_in_full</span>
                    </button>
                </div>
            </header>
            <div class="flex-1 flex overflow-hidden px-4 py-6 md:p-6 gap-6 pt-0">
                <div class="flex-1 flex flex-col min-w-0 h-full gap-6">
                    <div
                        class="bg-surface-light dark:bg-surface-dark rounded-2xl p-4 shadow-sm border border-border-light dark:border-border-dark flex items-center justify-between flex-wrap gap-4 shrink-0">
                        <div>
                            <h3 class="text-primary font-medium text-lg">{{ props.liveSession?.title || 'Live Quran Class' }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-3">
                                <img v-for="participant in participants.slice(0, 4)" :key="participant.id" :alt="participant.name"
                                    class="w-10 h-10 rounded-full border-2 border-white dark:border-surface-dark object-cover"
                                    :src="participant.avatar || 'https://ui-avatars.com/api/?name=' + participant.name + '&color=fff&background=5cb65f'" />
                            </div>
                            <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark ml-2">{{ participants.length }} people
                                on call</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="flex items-center gap-2 bg-white dark:bg-gray-800 border border-border-light dark:border-border-dark rounded-lg px-3 py-1.5 shadow-sm">
                                <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                                <span class="font-medium text-sm">Live</span>
                            </div>
                            <div
                                class="bg-gray-100 dark:bg-gray-700 text-text-primary-light dark:text-text-primary-dark font-mono px-4 py-1.5 rounded-lg text-sm border border-border-light dark:border-border-dark">
                                {{ sessionDuration }}
                            </div>
                            <button @click="endSession"
                                class="bg-[#EB5757] hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                                End Session
                            </button>
                        </div>
                    </div>
                    <div
                        class="flex-1 bg-surface-light dark:bg-surface-dark rounded-2xl border border-border-light dark:border-border-dark flex flex-col overflow-hidden relative shadow-sm">
                        <div
                            class="flex-1 relative bg-[#FDFDFD] dark:bg-[#252525] flex items-center justify-center p-4 overflow-auto">
                            <div v-if="isQuranView" class="max-w-4xl w-full h-full flex flex-col items-center justify-center relative text-center">
                                <div v-if="currentVerses && currentVerses.length > 0" class="w-full h-full overflow-y-auto space-y-6 p-4">
                                    <div v-if="currentVerses[0].verse_number === 1 && selectedSurah.id !== 9" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                                        <p class="text-3xl font-arabic leading-loose mb-4 text-right" dir="rtl" style="font-family: 'Amiri', 'Noto Sans Arabic', 'Tajawal', serif;">
                                            بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
                                        </p>
                                    </div>
                                    <div v-for="verse in currentVerses" :key="verse.id" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-center mb-4">
                                            <span class="bg-primary-hover text-white px-3 py-1 rounded-full text-sm font-medium">
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
                                <video-player-container id="video-container" class="w-full h-full flex items-center justify-center bg-black rounded-lg" style="z-index: 1;">
                                </video-player-container>

                                <!-- Controls overlay -->
                                <div
                                class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-3 bg-black/70 backdrop-blur-sm px-4 py-2 rounded-full z-10">
                                <button @click="toggleScreenShare" class="text-white hover:text-primary transition-colors p-2">
                                    <span class="material-icons-outlined text-xl">present_to_all</span>
                                </button>
                                <button @click="toggleAudio" class="text-white hover:text-primary transition-colors p-2 relative">
                                    <span class="material-icons-outlined text-xl">{{ isAudioOn ? 'mic' : 'mic_off' }}</span>
                                    <span v-if="isAudioOn" class="absolute top-2 right-2 w-2 h-2 bg-green-500 rounded-full">
                                    </span>
                                    <span v-else class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full">
                                    </span>
                                </button>
                                <button @click="toggleVideo" class="text-white hover:text-primary transition-colors p-2 relative">
                                    <span class="material-icons-outlined text-xl">{{ isVideoOn ? 'videocam' : 'videocam_off' }}</span>
                                    <span v-if="!isVideoOn" class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full">
                                    </span>
                                </button>
                                </div>
                            </div>
                        </div>
                        <div
                            class="h-auto md:h-16 border-t border-border-light dark:border-border-dark flex flex-col md:flex-row items-center justify-between px-4 md:px-6 py-4 md:py-0 gap-4 md:gap-0 bg-surface-light dark:bg-surface-dark">
                            <button v-if="isQuranView" @click="switchToVideoView"
                                class="w-full md:w-auto bg-primary hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Show Video
                            </button>
                            <button v-else
                                @click="isQuranView = true"
                                class="w-full md:w-auto bg-primary hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Full Quran View
                            </button>
                            <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                                <div class="relative w-full md:w-48">
                                    <select
                                        v-model="selectedSurah"
                                        @change="isSurahDropdownOpen = false"
                                        class="flex items-center px-4 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-sm w-full appearance-none"
                                    >
                                        <option :value="null" disabled>Select Surah</option>
                                        <option
                                            v-for="surah in surahs"
                                            :key="surah.id"
                                            :value="surah"
                                        >
                                            {{ surah.name_simple }}
                                        </option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-text-secondary-light dark:text-text-secondary-dark">
                                        <span class="material-icons text-sm">expand_more</span>
                                    </div>
                                </div>
                                <div class="relative w-full md:w-auto" v-if="selectedSurah">
                                      <select v-model="selectedAyah" class="flex items-center px-4 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-sm w-full md:w-auto">
                                        <option :value="null" disabled>Select Ayah</option>
                                        <option v-for="ayah in selectedSurah.verses_count" :key="ayah" :value="ayah">
                                            {{ ayah }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-4 w-full md:w-auto">
                                <div class="flex flex-col items-center cursor-pointer group">
                                  <button
                                      @click="displayPreviousVerses"
                                      :disabled="!selectedSurah || !selectedAyah || selectedAyah <= 1"
                                      class="w-10 h-10 bg-primary/10 dark:bg-primary/20 text-primary flex items-center justify-center rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                                    <span class="material-icons text-2xl rotate-180">play_arrow</span>
                                  </button>
                                  <span
                                      class="text-[10px] text-text-secondary-light dark:text-text-secondary-dark mt-1">Prev</span>
                                </div>
                                <button @click="displayVerse"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Display
                                </button>
                                <div class="flex flex-col items-center cursor-pointer group">
                                  <button
                                      @click="displayNextVerses"
                                      :disabled="!selectedSurah || !selectedAyah || selectedAyah >= selectedSurah.verses_count"
                                      class="w-10 h-10 bg-primary/10 dark:bg-primary/20 text-primary flex items-center justify-center rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                                    <span class="material-icons text-2xl">play_arrow</span>
                                  </button>
                                  <span
                                      class="text-[10px] text-text-secondary-light dark:text-text-secondary-dark mt-1">Next</span>
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
                                    :src="message.user?.avatar || `https://ui-avatars.com/api/?name=${message.user?.first_name?.[0]}${message.user?.last_name?.[0] || (message.user?.name || 'U')}&color=fff&background=5cb65f`" />
                                <div class="flex flex-col max-w-[85%]">
                                    <span class="text-xs font-semibold mb-0.5">
                                        {{ message.user?.first_name && message.user?.last_name ? `${message.user.first_name} ${message.user.last_name}` : (message.user?.name || 'Unknown User') }}
                                        <small class="text-[10px] opacity-75">({{ message.user?.role || 'User' }})</small>
                                    </span>
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
                <aside v-if="joinRequests.length > 0"
                    class="w-80 bg-surface-light dark:bg-surface-dark rounded-2xl border border-border-light dark:border-border-dark flex flex-col shadow-sm shrink-0">
                    <div
                        class="h-16 flex items-center justify-between px-6 border-b border-border-light dark:border-border-dark">
                        <h3
                            class="font-semibold text-lg">
                            Join Requests</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-6 scrollbar-hide">
                        <div v-for="request in joinRequests" :key="request.id" class="flex items-center justify-between">
                            <div>
                                <p>{{ request.student.first_name }} {{ request.student.last_name }}</p>
                                <p class="text-xs text-gray-500">{{ request.student.email }}</p>
                            </div>
                            <div>
                                <button @click="approveRequest(request)" class="text-green-500 px-2 py-1 rounded-md">Approve</button>
                                <button @click="declineRequest(request)" class="text-red-500 px-2 py-1 rounded-md">Decline</button>
                            </div>
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
      

    </body>
</template>