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
const startLiveSession = async () => {
    try {
        const studentIds = props.students.map(s => s.id);
        const response = await axios.post(route('live-class.start'), {
            title: "Today's Quran Class",
            students: studentIds,
        });

        if (response.data && response.data.success) {
            // Redirect to the live class page
            router.visit(route('live'));
        } else {
            console.error('Failed to start live session:', response.data.message);
            alert('Failed to start live session. Please try again.');
        }
    } catch (error) {
        console.error('Error starting live session:', error);
        alert('Failed to start live session. Please try again.');
    }
};

// Check if current route is the prepare class route
const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};

const handleResize = () => {
    if (window.innerWidth >= 768) {
        sidebarOpen.value = false;
    }
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
<body class="bg-gray-50 text-gray-800 h-screen flex flex-col lg:flex-row">

    <!-- Mobile Header with Hamburger Menu -->
    <header class="lg:hidden bg-white shadow-md px-4 py-3 flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-700 focus:outline-none mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <img src="/images/app_logo.jpg" alt="1-on-1 Quran Classes Logo" class="w-24" />
    </header>
    
    <!-- Mobile Sidebar Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="w-64 bg-white shadow-lg flex flex-col fixed lg:static h-full z-40 transform lg:transform-none transition-transform duration-300 ease-in-out">
            <!-- Logo -->
            <div class="px-8 pt-8 pb-4">
                <img src="/images/app_logo.jpg" alt="1-on-1 Quran Classes Logo" class="w-48 mx-auto" />
            </div>

            <!-- Navigation -->
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

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-10">
            <!-- Header -->
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

            <!-- Today's Session -->
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

            <!-- Quick Stats -->
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

            <!-- Recent Activities & Upcoming Classes -->
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
</body>
</template>