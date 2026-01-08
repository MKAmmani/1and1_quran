<script setup>
import { router, Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({
    students: Array,
});

const page = usePage();

const form = ref({
    students: [],
    message: '',
});

const sendAnnouncement = () => {
    router.post(route('announcements.send'), form.value, {
        onSuccess: () => form.value.message = '',
    });
};

const logout = () => {
    router.post(route("logout"));
};

const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};

const sidebarOpen = ref(false);

const handleResize = () => {
    if (window.innerWidth >= 768) { // md breakpoint
        sidebarOpen.value = false;
    }
};

onMounted(() => {
    sidebarOpen.value = window.innerWidth >= 768;
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});

const currentDate = computed(() => {
    return new Date().toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});
</script>

<template>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-body min-h-screen flex">
    <!-- Mobile Header with Hamburger Menu -->
    <header class="md:hidden h-16 px-4 flex items-center justify-between flex-shrink-0 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark z-20">
        <button @click="sidebarOpen = true" class="p-2 rounded-lg text-text-primary-light dark:text-text-primary-dark">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="/images/app_logo.jpg" alt="App Logo" class="h-10 w-auto">
        <div class="w-10"></div> <!-- Spacer for alignment -->
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>

    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="w-64 flex-shrink-0 bg-surface-light dark:bg-surface-dark border-r border-gray-200 dark:border-gray-700 h-screen sticky top-0 flex flex-col justify-between overflow-y-auto z-40 transition-transform duration-300 ease-in-out md:translate-x-0 md:static">
        <div>
            <div class="p-8 flex items-center justify-center">
                <div class="flex flex-col items-center">
                    <div class="relative w-16 h-16 mb-2">
                        <img alt="Quran Class Logo" class="w-full h-full object-contain filter drop-shadow-md" src="/images/app_logo.jpg" />
                    </div>
                    <div class="text-center leading-tight">
                        <span class="block text-xs font-bold text-accent-gold uppercase tracking-widest">1-on-1</span>
                        <span class="block text-lg font-bold text-primary dark:text-white">QURAN</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 tracking-wider">CLASSES</span>
                    </div>
                </div>
            </div>
            <nav class="px-4 space-y-2 mt-4">
                <Link :href="route('teacher.index')" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group">
                    <span class="material-icons-outlined text-xl w-8">home</span>
                    <span class="font-medium">Home</span>
                </Link>
                <Link :href="route('prepre_class')" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group">
                    <span class="material-icons-outlined text-xl w-8">video_library</span>
                    <span class="font-medium">Prepare Class</span>
                </Link>
                <Link :href="route('teacher.students')" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group">
                    <span class="material-icons-outlined text-xl w-8">people_alt</span>
                    <span class="font-medium">Students</span>
                </Link>
                <Link :href="route('announcement')" class="sidebar-item flex items-center px-4 py-3 bg-primary text-white shadow-md rounded-lg group">
                    <span class="material-icons-outlined text-xl w-8 transform -rotate-12">campaign</span>
                    <span class="font-medium">Announcement</span>
                </Link>
                <Link :href="route('live')" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group">
                    <span class="material-icons-outlined text-xl w-8">live_tv</span>
                    <span class="font-medium">Live class</span>
                </Link>
                <Link :href="route('quran.surahs')" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group">
                    <span class="material-icons-outlined text-xl w-8">menu_book</span>
                    <span class="font-medium">Quran library</span>
                </Link>
                <Link :href="route('class.history')" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group">
                    <span class="material-icons-outlined text-xl w-8">history</span>
                    <span class="font-medium">Class history</span>
                </Link>
            </nav>
        </div>
        <div class="px-4 pb-8 space-y-2 mt-auto">
            <Link :href="route('profile.edit')" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group">
                <span class="material-icons-outlined text-xl w-8">settings</span>
                <span class="font-medium">Settings</span>
            </Link>
            <button @click="logout" class="sidebar-item flex items-center px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg group w-full">
                <span class="material-icons-outlined text-xl w-8">logout</span>
                <span class="font-medium">Logout</span>
            </button>
        </div>
    </aside>
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="flex-shrink-0 px-10 py-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Assalaikum Alaykum, {{ $page.props.auth.user.first_name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ currentDate }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full border border-primary flex items-center justify-center bg-white dark:bg-gray-800">
                    <span class="material-icons-outlined text-primary text-2xl">person_outline</span>
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.email }}</p>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto px-10 pb-10">
            <div class="mb-6 flex items-center space-x-3 text-primary">
                <span class="material-icons-outlined text-3xl transform -rotate-12">campaign</span>
                <h2 class="text-2xl font-bold">Make announcement</h2>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 h-full flex flex-col relative">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Select list</h3>
                <div class="flex-1 overflow-y-auto pr-2 space-y-6 mb-24">
                    <div v-for="student in students" :key="student.id" class="flex items-center justify-between group hover:bg-gray-50 dark:hover:bg-gray-800/50 p-2 rounded-lg transition-colors">
                        <div class="flex items-center space-x-6 w-1/4">
                            <div class="relative flex items-center">
                                <input type="checkbox" :value="student.id" v-model="form.students" class="w-6 h-6 text-primary bg-primary/10 border-none rounded focus:ring-primary dark:bg-gray-700 dark:checked:bg-primary" />
                            </div>
                            <span class="text-lg font-medium text-gray-700 dark:text-gray-200">{{ student.first_name }} {{ student.last_name }}</span>
                        </div>
                        <div class="flex-1 max-w-md px-4">
                            <div class="flex justify-between text-xs text-gray-400 dark:text-gray-500 mb-1">
                                <span>{{ student.surahs_completed }} of 114 surahs</span>
                                <span class="text-gray-600 dark:text-gray-300 font-medium">{{ student.progress }}% completed</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-primary h-1.5 rounded-full" :style="{ width: student.progress + '%' }"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end w-1/4 space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-2.5 h-2.5 rounded-full" :class="student.online ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]'"></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ student.online ? 'Online' : 'Offline' }}</span>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-28 right-8 flex justify-end">
                    <button class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm font-medium">
                        <span class="material-icons-outlined mr-1 text-lg">expand_less</span>
                        Close all
                    </button>
                </div>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-primary text-2xl transform -rotate-12">campaign</span>
                        </div>
                        <input v-model="form.message" class="block w-full pl-12 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm shadow-sm" placeholder="Make annoucement" type="text" />
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                            <button @click="sendAnnouncement" class="p-1 rounded-full text-primary hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" type="submit">
                                <span class="material-icons-outlined text-2xl transform -rotate-45">send</span>
                            </button>
                        </div>
                    </div>
                    <div class="absolute inset-0 border border-primary/50 rounded-lg pointer-events-none"></div>
                </div>
            </div>
        </div>
    </main>
</body>
</template>