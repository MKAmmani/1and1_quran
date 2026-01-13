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
});

const page = usePage();
const sidebarOpen = ref(false);

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
<!--<body class="bg-gray-50 text-gray-800 h-screen flex flex-col lg:flex-row">

    <!-- Mobile Header with Hamburger Menu 
    <header class="lg:hidden bg-white shadow-md px-4 py-3 flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-700 focus:outline-none mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <img src="/images/app_logo.jpg" alt="1-on-1 Quran Classes Logo" class="w-24" />
    </header>
    
    <!-- Mobile Sidebar Overlay -
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    <!-- Sidebar --
    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="w-64 bg-white shadow-lg flex flex-col fixed lg:static h-full z-40 transform lg:transform-none transition-transform duration-300 ease-in-out">
            <!-- Logo --
            <div class="px-8 pt-8 pb-4">
                <img src="/images/app_logo.jpg" alt="1-on-1 Quran Classes Logo" class="w-48 mx-auto" />
            </div>

            <!-- Navigation --
            <nav class="flex-1 px-6 pt-2 pb-6 flex flex-col">
                <div class="space-y-2">
                    <Link :href="route('teacher.index')" :class="{'bg-teal-600 text-white': isCurrentRoute('teacher.index'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('teacher.index')}" class="flex items-center gap-4 px-6 py-4 rounded-lg font-medium">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </Link>
                    <Link :href="route('prepre_class')" :class="{'bg-teal-600 text-white': isCurrentRoute('prepre_class'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('prepre_class')}" class="flex items-center gap-4 px-6 py-4 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Prepare
                    </Link>
                    <a href="#" class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Students
                    </a>
                    <a href="#" class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Live class
                    </a>
                    <a href="#" class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Quran Pages
                    </a>
                    <Link :href="route('announcement')" :class="{'bg-teal-600 text-white': isCurrentRoute('announcement'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('announcement')}" class="flex items-center gap-4 px-6 py-4 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                        Announcement
                    </Link>
                    <a href="#" class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Class history
                    </a>
                    <Link :href="route('profile.edit')" :class="{'bg-teal-600 text-white': isCurrentRoute('profile.edit'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('profile.edit')}" class="flex items-center gap-4 px-6 py-4 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Settings
                    </Link>
                </div>
                <button @click="logout" class="flex items-center gap-4 text-red-500 px-6 py-4 rounded-lg hover:bg-red-50 mt-auto w-full text-left">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </nav>
        </aside>

        <!-- Main Content --
        <main class="flex-1 p-4 lg:p-10">
            <!-- Header --
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-semibold">Assalaikum Alaykum, {{ $page.props.auth.user.first_name }}</h1>
                    <p class="text-gray-500">{{ currentDate }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <img src="/images/student_2.PNG" alt="Profile Picture" class="w-12 h-12 bg-gray-200 rounded-full border-2 border-dashed object-cover" />
                    <div class="text-left">
                        <p class="font-medium">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>
            </header>

            <!-- Today's Session --
            <section class="bg-white rounded-2xl shadow-sm p-8 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Today's Session</h2>
                    <button @click="startLiveSession" class="bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition">
                        Start class
                    </button>
                </div>

                <h3 class="text-xl font-semibold mb-6">My students</h3>

                <div class="space-y-4">
                    <div v-for="student in students" :key="student.id" class="grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-3">
                            <p class="font-medium text-lg">{{ student.first_name }} {{ student.last_name }}</p>
                            <p class="text-sm text-gray-500">{{ student.surahs_completed }} of 114 surahs</p>
                        </div>
                        <div class="col-span-6">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-teal-600 h-3 rounded-full" :style="{ width: student.progress + '%' }"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ student.progress }}% completed</p>
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <span class="flex items-center gap-2" :class="student.online ? 'text-green-600' : 'text-red-600'">
                                <span class="w-3 h-3 rounded-full" :class="student.online ? 'bg-green-600' : 'bg-red-600'"></span>
                                {{ student.online ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button class="text-gray-400 text-xl leading-none">
                                ⋮
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Stats --
            <section class="mb-8">
                <h2 class="text-2xl font-bold mb-6">Quick stats</h2>
                <div class="grid grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl p-6 shadow-sm border">
                        <p class="text-4xl font-bold text-teal-600">{{ totalStudents }}</p>
                        <p class="text-gray-600">Total students</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border">
                        <p class="text-4xl font-bold text-teal-600">{{ classesThisWeek }}</p>
                        <p class="text-gray-600">Classes this week</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border">
                        <p class="text-4xl font-bold text-teal-600">2</p>
                        <p class="text-gray-600">This week</p>
                    </div>
                </div>
            </section>

            <!-- Recent Activities & Upcoming Classes --
            <div class="grid grid-cols-2 gap-8">
                <section class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-2xl font-bold mb-6">Recent Activities</h2>
                    <div v-if="recentActivities && recentActivities.length > 0" class="space-y-4">
                        <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-teal-600 rounded-full"></span>
                            <p>Class "{{ activity.title }}" completed.</p>
                        </div>
                    </div>
                    <p v-else class="text-gray-500">No recent activities.</p>
                </section>

                <section class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-2xl font-bold mb-6">Upcoming Classes</h2>
                    <div v-if="upcomingClasses && upcomingClasses.length > 0" class="space-y-6">
                        <div v-for="upcoming in upcomingClasses" :key="upcoming.id" class="flex items-center justify-between">
                            <div>
                                <p class="font-medium">{{ upcoming.title }}</p>
                                <p class="text-sm text-gray-500">{{ new Date(upcoming.started_at).toLocaleString() }}</p>
                            </div>
                            <button class="bg-teal-600 text-white px-4 py-2 rounded-full text-sm hover:bg-teal-700">
                                Start
                            </button>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-gray-500">No upcoming classes.</p>
                        <Link :href="route('prepre_class')" class="text-teal-600 hover:underline">Schedule a class</Link>
                    </div>
                </section>
            </div>
        </main>
</body> -->

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
