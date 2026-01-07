<script setup>
import {
    onMounted,
    ref,
    computed,
    onBeforeUnmount
} from "vue";
import {
    router,
    Link,
    usePage
} from "@inertiajs/vue3";

const props = defineProps({
    liveSessions: Array,
    flash: Object,
    announcements: Array,
    recentActivities: Array,
    upcomingClasses: Array,
});

const sidebarOpen = ref(false);

const page = usePage();

const currentDate = computed(() => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date().toLocaleDateString('en-US', options);
});

const requestToJoin = (sessionId) => {
    router.post(route('live-session.request-join', sessionId));
};

const logout = () => {
    router.post(route("logout"));
};

const handleResize = () => {
    sidebarOpen.value = window.innerWidth >= 768;
};

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize);
    if (window.Echo) {
        const channelName = `student.${page.props.auth.user.id}`;
        console.log(`Listening on channel: ${channelName}`);

        window.Echo.private(channelName)
            .listen('.join.request.approved', (e) => {
                alert('Join request approved event received! The student should now be redirected.');
                console.log('Join request approved event received:', e);
                router.visit(route('live-session.join', e.joinRequest.live_session_id));
            });
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-display antialiased transition-colors duration-200">
    <!-- Mobile Header with Hamburger Menu -->
    <header class="md:hidden h-16 px-4 flex items-center justify-between flex-shrink-0 bg-surface-light dark:bg-surface-dark border-b border-gray-200 dark:border-gray-800 z-20">
        <button @click="sidebarOpen = true" class="p-2 rounded-lg text-text-light dark:text-text-dark">
            <span class="material-icons">menu</span>
        </button>
        <!--App logo-->
        <img src="/images/app_logo.jpg" alt="App Logo" class="h-24 w-auto">
        <div class="w-10"></div> <!-- Spacer for alignment -->
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>

    <div class="flex min-h-screen">
        <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="w-64 fixed md:static md:translate-x-0 inset-y-0 left-0 z-40 md:z-auto flex flex-col bg-surface-light dark:bg-surface-dark border-r border-gray-200 dark:border-gray-800 transition-all duration-300 ease-in-out">
            <div class="p-6 flex justify-center items-center h-40">
                <div class="relative w-32 h-24">
                    <img alt="1-on-1 Quran Classes Logo" class="object-contain w-full h-full dark:opacity-90 dark:brightness-110" src="/images/app_logo.jpg" />
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a class="flex items-center px-4 py-3 bg-primary text-white rounded-lg shadow-md transition-all hover:bg-secondary group" href="#">
                    <span class="material-icons text-xl mr-3">home</span>
                    <span class="font-medium">Home</span>
                </a>
                <a class="flex items-center px-4 py-3 text-text-muted-light dark:text-text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group" href="#">
                    <span class="material-icons text-xl mr-3 group-hover:text-primary transition-colors">videocam</span>
                    <span class="font-medium">Live class</span>
                </a>
                <a class="flex items-center px-4 py-3 text-text-muted-light dark:text-text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group" href="#">
                    <span class="material-icons text-xl mr-3 group-hover:text-primary transition-colors">library_books</span>
                    <span class="font-medium">Quran library</span>
                </a>
            </nav>
            <div class="p-4 space-y-2 mb-4">
                <a class="flex items-center px-4 py-3 text-text-muted-light dark:text-text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group" href="#">
                    <span class="material-icons text-xl mr-3 group-hover:text-primary transition-colors">settings</span>
                    <span class="font-medium">Settings</span>
                </a>
                <button @click="logout" class="w-full text-left flex items-center px-4 py-3 text-text-muted-light dark:text-text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group">
                    <span class="material-icons text-xl mr-3 group-hover:text-primary transition-colors">logout</span>
                    <span class="font-medium">Logout</span>
                </button>
            </div>
        </aside>
        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-text-light dark:text-text-dark">Assalamu Alaykum, {{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</h1>
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-sm text-text-muted-light dark:text-text-muted-dark font-medium hidden md:block">{{ currentDate }}</span>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-text-muted-light dark:text-text-muted-dark">
                            <span class="material-icons">person_outline</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-text-light dark:text-text-dark">{{ $page.props.auth.user.first_name }}</span>
                            <span class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $page.props.auth.user.email }}</span>
                        </div>
                    </div>
                </div>
            </header>
            <section class="mb-10 flex flex-col md:flex-row justify-between items-center gap-6 bg-white dark:bg-surface-dark p-6 rounded-xl transition-colors duration-200">
                <div class="text-lg text-text-muted-light dark:text-text-muted-dark max-w-2xl">
                    Ready to connect with your teacher and Continue your quran journey.
                </div>
                <div v-if="liveSessions.length > 0">
                    <div v-for="session in liveSessions" :key="session.id" class="flex flex-col items-center">
                        <button @click="requestToJoin(session.id)" class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-lg font-medium shadow-sm transition-all flex items-center gap-2 whitespace-nowrap">
                            <span class="material-icons">person_add</span>
                            <!--Request to Join Class - {{ session.title }} -->
                            Request to Join Class
                        </button>
                        <p class="text-sm text-gray-600 mt-2">Teacher: {{ session.teacher?.first_name }} {{ session.teacher?.last_name }}</p>
                    </div>
                </div>
                <button v-else class="bg-gray-400 text-white px-8 py-3 rounded-lg font-medium cursor-not-allowed flex items-center gap-2 whitespace-nowrap">
                    <span class="material-icons">videocam_off</span>
                    No Live Class
                </button>
            </section>
            <section class="mb-10">
                <h2 class="text-xl font-bold text-text-light dark:text-text-dark mb-6">Upcoming Classes</h2>
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 divide-y divide-gray-100 dark:divide-gray-800">
                    <div v-if="upcomingClasses && upcomingClasses.length > 0">
                        <div v-for="upcoming in upcomingClasses" :key="upcoming.id" class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <img alt="Instructor" class="w-12 h-12 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-sm" :src="upcoming.teacher?.avatar || `https://ui-avatars.com/api/?name=${upcoming.teacher?.first_name}+${upcoming.teacher?.last_name}&color=fff&background=5cb65f`" />
                                <div>
                                    <h3 class="font-semibold text-text-light dark:text-text-dark">{{ upcoming.title }}</h3>
                                    <p class="text-sm text-primary mt-1">Teacher: {{ upcoming.teacher?.first_name }} {{ upcoming.teacher?.last_name }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col md:items-end text-sm text-text-muted-light dark:text-text-muted-dark gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons text-base">schedule</span>
                                    {{ new Date(upcoming.started_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons text-base">calendar_today</span>
                                    {{ new Date(upcoming.started_at).toLocaleDateString([], { month: 'short', day: 'numeric' }) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="p-6 text-text-muted-light dark:text-text-muted-dark">
                        No upcoming classes scheduled.
                    </div>
                </div>
            </section>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <section>
                    <h2 class="text-xl font-bold text-text-light dark:text-text-dark mb-6">Announcement</h2>
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 space-y-6">
                        <div v-if="announcements && announcements.length > 0">
                            <div v-for="announcement in announcements" :key="announcement.id" class="flex gap-4 items-start">
                                <div class="mt-1 text-primary">
                                    <span class="material-icons rotate-12">campaign</span>
                                </div>
                                <div>
                                    <p class="text-text-light dark:text-text-dark font-medium">{{ announcement.message }}</p>
                                    <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">{{ new Date(announcement.created_at).toLocaleString() }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-text-muted-light dark:text-text-muted-dark">No announcements.</p>
                        </div>
                    </div>
                </section>
                <section>
                    <h2 class="text-xl font-bold text-text-light dark:text-text-dark mb-6">Recent Activities</h2>
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 space-y-6">
                        <div v-if="recentActivities && recentActivities.length > 0">
                            <div v-for="activity in recentActivities" :key="activity.id" class="flex gap-4 items-start">
                                <div class="mt-1.5 h-3 w-3 rounded-full bg-primary flex-shrink-0"></div>
                                <div>
                                    <p class="text-text-light dark:text-text-dark text-sm">Class "{{ activity.title }}" completed with {{ activity.teacher.first_name }} {{ activity.teacher.last_name }}.</p>
                                    <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">{{ new Date(activity.ended_at).toLocaleString() }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-text-muted-light dark:text-text-muted-dark">No recent activities.</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

</body>
</template>
