<script setup>
import {
    onMounted,
    computed,
    onBeforeUnmount,
    ref
} from "vue";
import {
    router,
    Link,
    usePage
} from "@inertiajs/vue3";
import axios from 'axios';

const props = defineProps({
    students: Array,
    totalStudents: Number,
    classesThisWeek: Number,
    recentActivities: Array,
    upcomingClasses: Array,
    surahs: Array,
});

const page = usePage();
const sidebarOpen = ref(false);

const getSurahName = (surahId) => {
    if (!surahId || !props.surahs) {
        return 'N/A';
    }
    const surah = props.surahs.find(s => s.id === surahId);
    return surah ? surah.name_simple : `Surah ${surahId}`;
};

const handleResize = () => {
    sidebarOpen.value = window.innerWidth >= 768; // md breakpoint is 768px
};

const currentDate = computed(() => {
    return new Date().toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const logout = () => {
    router.post(route("logout"));
};

// Function to start a live session
const startLiveSession = () => {
    const studentIds = props.students.map(s => s.id);
    router.post(route('live-class.start'), {
        title: "Today's Quran Class",
        students: studentIds,
    }, {
        onError: (errors) => {
            console.error('Error starting live session:', errors);
            alert('Failed to start live session. Please try again.');
        }
    });
};

// Check if current route is the prepare class route
const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize);
    if (window.Echo) {
        const channelName = `teacher.${page.props.auth.user.id}`;
        console.log(`Listening on channel: ${channelName}`);

        window.Echo.private(channelName)
            .listen('.join.request.sent', (e) => {
                console.log('Join request sent event received:', e);
                // Optionally show a notification
            });
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>

    <header class="lg:hidden bg-white shadow-md px-4 py-3 flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-700 focus:outline-none mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <img src="/images/app_logo.jpg" alt="1-on-1 Quran Classes Logo" class="w-24" />
    </header>
    
<body class="bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100 font-sans transition-colors duration-200">
    <div class="flex flex-col md:flex-row h-screen overflow-hidden">

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

        <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed md:static md:translate-x-0 top-0 left-0 h-full w-64 bg-surface-light dark:bg-surface-dark border-r border-gray-200 dark:border-gray-800 flex-shrink-0 flex flex-col z-40 md:z-auto transition-transform duration-300 ease-in-out overflow-y-auto">
            <div class="md:hidden absolute inset-0 bg-white/30 dark:bg-gray-900/30 backdrop-blur-md -z-10 md:backdrop-blur-none md:bg-surface-light dark:md:bg-surface-dark"></div>
            <div class="p-6 flex justify-center items-center">
                <div class="relative flex items-center justify-center w-[186px] h-[122px]">
                    <img alt="1-on-1 Quran Classes Logo" class="object-contain h-24 w-auto" src="/images/app_logo.jpg" />
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <Link :href="route('teacher.index')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('teacher.index'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('teacher.index')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">home</span>
                    <span class="font-medium">Home</span>
                </Link>
                <Link :href="route('prepre_class')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('prepre_class'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('prepre_class')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">edit_note</span>
                    <span class="font-medium">Prepare Class</span>
                </Link>
                <a class="flex items-center gap-4 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary rounded-lg transition-all duration-200" :href="route('teacher.students')">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-medium">Students</span>
                </a>
                <Link class="flex items-center gap-4 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary rounded-lg transition-all duration-200" :href="route('announcement')">
                    <span class="material-symbols-outlined">campaign</span>
                    <span class="font-medium">Announcement</span>
                </Link>
                <Link :href="route('live')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('live'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('live')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">videocam</span>
                    <span class="font-medium">Live class</span>
                </Link>
                <Link class="flex items-center gap-4 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary rounded-lg transition-all duration-200" :href="route('quran.surahs')">
                    <span class="material-symbols-outlined">book_2</span>
                    <span class="font-medium">Quran library</span>
                </Link>
                <Link class="flex items-center gap-4 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary rounded-lg transition-all duration-200" :href="route('class.history')">
                    <span class="material-symbols-outlined">history_edu</span>
                    <span class="font-medium">Class history</span>
                </Link>
            </nav>
            <div class="p-4 mt-auto border-t border-gray-100 dark:border-gray-800 space-y-2">
                <Link :href="route('profile.edit')" :class="{'bg-primary text-white shadow-md shadow-primary/30': isCurrentRoute('profile.edit'), 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary': !isCurrentRoute('profile.edit')}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-medium">Settings</span>
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-logout-bg dark:bg-red-900/30 text-logout-text dark:text-red-400 font-medium hover:opacity-90 transition-all duration-200">
                    <span class="material-symbols-outlined transform rotate-180">logout</span>
                    <span>Logout</span>
                </Link>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto h-full p-4 md:p-6 lg:p-10 bg-background-light dark:bg-background-dark">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-10 gap-4">
                <div class="hidden md:block">
                    <h1 class="text-2xl font-display font-semibold text-gray-800 dark:text-white">Assalaikum Alaykum, {{ $page.props.auth.user.first_name }}</h1>
                </div>
                <div class="md:hidden text-base font-semibold text-text-primary-light dark:text-text-primary-dark">
                    Dashboard
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark hidden sm:block">{{ currentDate }}</span>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-primary">
                            <span class="material-icons-outlined">person</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $page.props.auth.user.first_name }}</span>
                            <span class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ $page.props.auth.user.email }}</span>
                        </div>
                    </div>
                </div>
            </header>
            <section class="mb-10">
                <h2 class="text-2xl font-display font-semibold text-gray-800 dark:text-white mb-6">Today's Session</h2>
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">My students</h3>
                        <button  @click="startLiveSession" class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                            Start class
                        </button>
                    </div>
                    <div class="space-y-6">
                        <div v-for="student in students" :key="student.id" class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-4 border-b border-gray-50 dark:border-gray-700/50 last:border-0 last:pb-0">
                            <div class="w-full md:w-1/4">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ student.first_name }} {{ student.last_name }}</span>
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col gap-1">
                                <div class="flex justify-between text-xs text-text-secondary-light dark:text-text-secondary-dark mb-1">
                                    <span>{{ student.surahs_completed || 0 }} of 114 surahs</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ student.progress || 0 }}% completed</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" :style="{ width: (student.progress || 0) + '%' }"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1" v-if="student.current_surah_id">
                                    Currently on: <span class="font-medium">{{ getSurahName(student.current_surah_id) }}</span>
                                </div>
                            </div>
                            <div class="w-full md:w-1/4 flex justify-between md:justify-end items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" :class="student.online ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]'"></span>
                                    <span class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ student.online ? 'Online' : 'Offline' }}</span>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <span class="material-icons-outlined text-lg">more_vert</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mb-10">
                <h2 class="text-2xl font-display font-semibold text-gray-800 dark:text-white mb-6">Quick stats</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4">
                        <span class="text-5xl font-bold text-primary">{{ totalStudents }}</span>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Total students</span>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4">
                        <span class="text-5xl font-bold text-primary">{{ classesThisWeek }}</span>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Classes this<br />week</span>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4">
                        <span class="text-5xl font-bold text-primary">2</span>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">This week</span>
                    </div>
                </div>
            </section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-display font-semibold text-gray-800 dark:text-white mb-6">Recent Activities</h2>
                    <div class="space-y-4">
                        <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-2">Today</p>
                        <div v-for="activity in recentActivities" :key="activity.id" class="bg-gray-50 dark:bg-gray-800/50 border-l-4 border-primary rounded-r-lg p-4 transition-colors">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                {{ activity.title }}
                            </p>
                        </div>
                        <div v-if="!recentActivities || recentActivities.length === 0" class="text-gray-500">
                            No recent activities.
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-semibold text-gray-800 dark:text-white mb-6">Upcoming Classes</h2>
                    <div class="space-y-3">
                        <div v-for="upcoming in upcomingClasses" :key="upcoming.id" class="bg-surface-light dark:bg-surface-dark border border-gray-100 dark:border-gray-800 rounded-xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 text-gray-800 dark:text-white">
                                <span class="material-icons-outlined">play_circle_filled</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ upcoming.title }}</h4>
                                <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ new Date(upcoming.started_at).toLocaleTimeString() }}</p>
                            </div>
                            <button @click="startLiveSession" class="bg-primary hover:bg-primary-dark text-white text-xs px-2 py-1 rounded transition-colors">Start</button>
                        </div>
                        <div v-if="!upcomingClasses || upcomingClasses.length === 0">
                            <p class="text-gray-500">No upcoming classes.</p>
                            <Link :href="route('prepre_class')" class="text-primary hover:underline">Schedule a class</Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</template>
