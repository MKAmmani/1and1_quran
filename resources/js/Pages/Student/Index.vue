<script setup>
import { onMounted, ref } from "vue";
import { router, Link, usePage } from "@inertiajs/vue3";

const props = defineProps({
    liveSessions: Array,
    flash: Object,
});

const page = usePage();

const requestToJoin = (sessionId) => {
    router.post(route('live-session.request-join', sessionId));
};

const logout = () => {
    router.post(route("logout"));
};

// Close mobile menu function
const closeMobileMenu = () => {
    const mobileNav = document.getElementById("mobileNav");
    if (mobileNav && !mobileNav.classList.contains("-translate-x-full") && !mobileNav.classList.contains("hidden")) {
        mobileNav.classList.add("-translate-x-full");
        // Hide after animation completes
        setTimeout(() => {
            mobileNav.classList.add("hidden");
        }, 300);
    }
};

onMounted(() => {
    if (window.Echo) {
        const channelName = `student.${page.props.auth.user.id}`;
        console.log(`Listening on channel: ${channelName}`);

        window.Echo.private(channelName)
            .listen('.join.request.approved', (e) => {
                console.log('Join request approved event received:', e);
                router.visit(route('live-session.join', e.joinRequest.live_session_id));
            });
    }

    // Mobile menu toggle
    const menuToggle = document.getElementById("menuToggle");
    const mobileNav = document.getElementById("mobileNav");

    if (menuToggle && mobileNav) {
        menuToggle.addEventListener("click", function () {
            mobileNav.classList.remove("hidden");
            // Trigger the slide-in animation
            setTimeout(() => {
                mobileNav.classList.remove("-translate-x-full");
            }, 10);
        });

        // Close menu when clicking outside
        document.addEventListener("click", function (event) {
            const isClickInside =
                mobileNav.contains(event.target) ||
                menuToggle.contains(event.target);
            if (!isClickInside && !mobileNav.classList.contains("-translate-x-full") && !mobileNav.classList.contains("hidden")) {
                closeMobileMenu();
            }
        });
    }
});
</script>

<template>
    <body class="bg-gray-50 min-h-screen">

    <!-- Desktop Layout -->
    <div class="hidden lg:flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Logo -->
            <div class="p-8 border-b">
                <img src="/images/app_logo.jpg" alt="1-on-1 Quran Classes Logo" class="w-48 mx-auto">
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-6 space-y-2">
                <a href="#" class="flex items-center gap-4 bg-teal-600 text-white px-6 py-4 rounded-lg font-medium shadow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Home
                </a>
                <a href="#" class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Live class
                </a>
                <a href="#" class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Quran library
                </a>
                <a href="#" class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center gap-4 text-red-500 px-6 py-4 rounded-lg hover:bg-red-50 mt-auto w-full text-left"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10">
            <!-- Header -->
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-semibold">Assalamu Alaykum, Fatima zahra</h1>
                    <p class="text-gray-500">Monday, January 30 2023</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="font-medium">Aisha</p>
                        <p class="text-sm text-gray-500">info@aisha.com</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-200 rounded-full border-2 border-dashed"></div>
                </div>
            </header>

            <!-- Welcome Banner -->
            <div class="bg-white rounded-2xl shadow-sm p-8 mb-10 flex items-center justify-between">
                <p class="text-xl text-gray-700">Ready to connect with your teacher and Continue your quran journey.</p>
                <div v-if="flash && flash.success" class="text-green-500">{{ flash.success }}</div>
                <div v-if="liveSessions.length > 0" class="flex flex-col items-center">
                    <button v-for="session in liveSessions" :key="session.id" @click="requestToJoin(session.id)" class="bg-teal-600 text-white px-8 py-4 rounded-full font-bold hover:bg-teal-700 flex items-center gap-3 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Request to Join Class - {{ session.title }}
                    </button>
                    <p class="text-sm text-gray-600 mt-2">Teacher: {{ liveSessions[0].teacher?.first_name }} {{ liveSessions[0].teacher?.last_name }}</p>
                </div>
                <button v-else class="bg-gray-400 text-white px-8 py-4 rounded-full font-bold cursor-not-allowed flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    No Live Class
                </button>
            </div>

            <!-- Upcoming Classes -->
            <section class="mb-10">
                <h2 class="text-3xl font-bold mb-8">Upcoming Classes</h2>
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Teacher" class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <p class="text-lg font-medium">Surah: Al-Baqarah (verses 1-10)</p>
                                <p class="text-teal-600">Teacher: Ustadha Aishat</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="flex items-center gap-2 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 4:00pm - 4:45 PM</p>
                            <p class="flex items-center gap-2 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Today, Jan 30</p>
                        </div>
                    </div>

                    <!-- Repeat for other classes -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Teacher" class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <p class="text-lg font-medium">Surah: Al-Baqarah (verses 1-10)</p>
                                <p class="text-teal-600">Teacher: Ustadh hakeem</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="flex items-center gap-2 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 4:00pm - 4:45 PM</p>
                            <p class="flex items-center gap-2 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Today, Jan 30</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Teacher" class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <p class="text-lg font-medium">Surah: Al-Baqarah (verses 1-10)</p>
                                <p class="text-teal-600">Teacher: Ustadha Aishat</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="flex items-center gap-2 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 4:00pm - 4:45 PM</p>
                            <p class="flex items-center gap-2 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Today, Jan 30</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Announcement -->
            <section class="mb-10">
                <h2 class="text-3xl font-bold mb-8">Announcement</h2>
                <div class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
                    <div class="flex items-start gap-4">
                        <svg class="w-6 h-6 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <div>
                            <p class="font-medium">New interactive exercises available Juz 'amma</p>
                            <p class="text-sm text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <svg class="w-6 h-6 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <div>
                            <p class="font-medium">Platform maintenance scheduled for Feb. 12 2023</p>
                            <p class="text-sm text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recent Activities -->
            <section>
                <h2 class="text-3xl font-bold mb-8">Recent Activities</h2>
                <div class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 bg-teal-600 rounded-full"></span>
                        <p>Class Suratul fathia completed with ustadha aisha</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 bg-teal-600 rounded-full"></span>
                        <p>Class Suratul fathia completed with ustadha aisha</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 bg-teal-600 rounded-full"></span>
                        <p>Class Tajweed essentials completed with Ustadh Hakeem</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Mobile Layout -->
    <div class="lg:hidden p-4">
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 text-center">
            <img src="YOUR_LOGO_URL_HERE" alt="Logo" class="w-40 mx-auto mb-6">
            <h1 class="text-2xl font-bold mb-2">Assalamu Alaykum, Fatima zahra</h1>
            <p class="text-gray-500 mb-8">Monday, January 30 2023</p>
            <div v-if="liveSessions.length > 0" class="space-y-3 mb-8">
                <Link v-for="session in liveSessions" :key="session.id" :href="route('live-session.join', session.id)" class="w-full bg-teal-600 text-white py-4 rounded-full font-bold block">
                    Join Class - {{ session.title }}
                </Link>
                <p class="text-sm text-gray-600">Teacher: {{ liveSessions[0].teacher?.first_name }} {{ liveSessions[0].teacher?.last_name }}</p>
            </div>
            <button v-else class="w-full bg-gray-400 text-white py-4 rounded-full font-bold mb-8 cursor-not-allowed">No Live Class</button>
        </div>

        <h2 class="text-xl font-bold mb-4">Upcoming Classes</h2>
        <div class="space-y-4 mb-8">
            <div class="bg-white rounded-xl p-4 shadow">
                <div class="flex items-center gap-4">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Teacher" class="w-12 h-12 rounded-full">
                    <div>
                        <p class="font-medium">Surah: Al-Baqarah (verses 1-10)</p>
                        <p class="text-teal-600 text-sm">Ustadha Aishat</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">4:00pm - 4:45 PM • Today, Jan 30</p>
            </div>
            <!-- Repeat -->
        </div>

        <h2 class="text-xl font-bold mb-4">Recent Activities</h2>
        <div class="bg-white rounded-xl p-6 shadow space-y-4">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 bg-teal-600 rounded-full"></span>
                <p>Class Suratul fathia completed with ustadha aisha</p>
            </div>
            <!-- Repeat -->
        </div>
    </div>

</body>
</template>