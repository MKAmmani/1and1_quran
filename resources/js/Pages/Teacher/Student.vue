<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";

const props = defineProps({
    students: Array,
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

const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};

const handleResize = () => {
    sidebarOpen.value = window.innerWidth >= 768;
};

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-display h-screen flex flex-col overflow-hidden transition-colors duration-200">
    <header class="md:hidden h-16 px-4 flex items-center justify-between flex-shrink-0 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark z-20">
        <button @click="sidebarOpen = true" class="p-2 rounded-lg text-text-light dark:text-text-dark">
            <span class="material-icons">menu</span>
        </button>
        <div class="w-24">
            <img alt="Quran Classes Logo" class="object-contain w-full h-full" src="/images/app_logo.jpg" />
        </div>
        <div class="w-10"></div> <!-- Spacer -->
    </header>
    
    <header class="hidden md:flex h-24 bg-surface-light dark:bg-surface-dark items-center justify-between px-8 border-b border-border-light dark:border-border-dark flex-shrink-0">
        <div class="flex items-center space-x-4">
            <div class="flex items-center gap-2">
                <div class="relative w-12 h-12">
                    <img alt="Quran Classes Logo" class="object-contain w-full h-full" src="/images/app_logo.jpg" />
                </div>
            </div>
        </div>
        <div class="hidden md:block flex-1 pl-12">
            <h1 class="text-xl font-medium text-gray-600 dark:text-gray-300">Assalaikum Alaykum, {{ $page.props.auth.user.first_name }}</h1>
        </div>
        <div class="flex items-center space-x-8">
            <span class="text-sm text-gray-500 dark:text-gray-400 hidden lg:block">{{ currentDate }}</span>
            <div class="flex items-center space-x-3 cursor-pointer group">
                <div class="w-10 h-10 rounded-full border border-primary flex items-center justify-center text-primary bg-primary/10 group-hover:bg-primary/20 transition-colors">
                    <span class="material-icons-outlined">person</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.email }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>

    <div class="flex flex-1 overflow-hidden">
        <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed md:static md:translate-x-0 top-0 left-0 h-full w-64 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex flex-col py-6 overflow-y-auto flex-shrink-0 scrollbar-hide z-40 md:z-auto transition-all duration-300 ease-in-out">
            <nav class="space-y-1 px-4">
                <Link :href="route('teacher.index')" :class="{'bg-primary text-white': isCurrentRoute('teacher.index'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('teacher.index')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">home</span>
                    Home
                </Link>
                <Link :href="route('prepre_class')" :class="{'bg-primary text-white': isCurrentRoute('prepre_class'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('prepre_class')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">menu_book</span>
                    Prepare Class
                </Link>
                <Link :href="route('teacher.students')" :class="{'bg-primary text-white': isCurrentRoute('teacher.students'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('teacher.students')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg shadow-md shadow-primary/30">
                    <span class="material-icons-outlined mr-3">people</span>
                    Students
                </Link>
                <Link :href="route('announcement')" :class="{'bg-primary text-white': isCurrentRoute('announcement'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('announcement')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">campaign</span>
                    Announcement
                </Link>
                <Link :href="route('live')" :class="{'bg-primary text-white': isCurrentRoute('live'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('live')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">videocam</span>
                    Live class
                </Link>
                <Link :href="route('quran.surahs')" :class="{'bg-primary text-white': isCurrentRoute('quran.surahs'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('quran.surahs')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">library_books</span>
                    Quran library
                </Link>
                <Link :href="route('class.history')" :class="{'bg-primary text-white': isCurrentRoute('teacher.history'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('teacher.history')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">history</span>
                    Class history
                </Link>
            </nav>
            <div class="mt-auto px-4 space-y-1 pt-8">
                <Link :href="route('profile.edit')" :class="{'bg-primary text-white': isCurrentRoute('profile.edit'), 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': !isCurrentRoute('profile.edit')}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">settings</span>
                    Settings
                </Link>
                <button @click="logout" class="w-full text-left flex items-center px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 group transition-colors">
                    <span class="material-icons-outlined mr-3 text-gray-500 dark:text-gray-400 group-hover:text-primary">logout</span>
                    Logout
                </button>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark p-8">
            <div class="max-w-6xl mx-auto bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-8 min-h-[600px] relative">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-8">Student list</h2>
                <div class="flex flex-col space-y-2">
                    <div v-for="student in students" :key="student.id" class="group flex flex-col md:flex-row items-center p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors border-b border-gray-50 dark:border-gray-800 md:border-none">
                        <div class="w-full md:w-16 flex justify-start pl-2 mb-2 md:mb-0">
                            <div class="w-6 h-6 rounded bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <span class="material-icons text-sm font-bold">check</span>
                            </div>
                        </div>
                        <div class="w-full md:w-1/4 mb-2 md:mb-0">
                            <span class="text-lg text-gray-800 dark:text-gray-200 font-medium">{{ student.first_name }} {{ student.last_name }}</span>
                        </div>
                        <div class="w-full md:w-1/3 flex flex-col mb-2 md:mb-0 pr-16">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-400">{{ student.surahs_completed }} of 114 surahs</span>
                                <span class="text-gray-800 dark:text-gray-300 font-medium">{{ student.progress }}% completed</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-primary h-1.5 rounded-full" :style="{ width: student.progress + '%' }"></div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/5 flex items-center mb-2 md:mb-0 md:pl-8">
                            <div class="flex items-center">
                                <div class="relative flex items-center justify-center w-4 h-4 mr-2">
                                    <span v-if="student.online" class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2" :class="student.online ? 'bg-green-500' : 'bg-red-500'"></span>
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ student.online ? 'Online' : 'Offline' }}</span>
                            </div>
                        </div>
                        <div class="w-full md:w-auto flex justify-end">
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-8 right-8">
                    <button class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors">
                        <span class="material-icons-outlined text-lg mr-1">expand_less</span>
                        Close all
                    </button>
                </div>
            </div>
        </main>
    </div>
</body>
</template>