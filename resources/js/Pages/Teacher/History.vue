<template>
<body class="bg-background-light dark:bg-background-dark font-sans text-text-primary-light dark:text-text-primary-dark antialiased h-screen overflow-hidden flex flex-col md:flex-row transition-colors duration-200">
    <!-- Mobile Header with Hamburger Menu -->
    <header class="md:hidden h-16 px-4 flex items-center justify-between flex-shrink-0 bg-background-light dark:bg-background-dark border-b border-border-light dark:border-border-dark z-20">
        <button @click="sidebarOpen = true" class="p-2 rounded-lg text-text-primary-light dark:text-text-primary-dark">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <!--App logo-->
        <img src="/images/app_logo.jpg" alt="App Logo" class="h-24 w-auto">
        <div class="w-10"></div> <!-- Spacer for alignment -->
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>

    <!-- Sidebar -->
    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed md:static md:translate-x-0 top-0 left-0 h-full w-64 md:w-72 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex-shrink-0 flex flex-col z-40 md:z-auto transition-all duration-300 ease-in-out">
        <div class="md:hidden absolute inset-0 bg-white/30 dark:bg-gray-900/30 backdrop-blur-md -z-10 md:backdrop-blur-none md:bg-surface-light dark:md:bg-surface-dark"></div>
        <div class="p-4 md:p-8 mb-4 flex justify-center">
            <img src="/images/app_logo.jpg" alt="App Logo" class="h-40 w-auto">
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                <span class="material-symbols-outlined">home</span>
                <span class="font-medium">Home</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                <span class="material-symbols-outlined">edit_note</span>
                <span class="font-medium">Prepare Class</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                <span class="material-symbols-outlined">group</span>
                <span class="font-medium">Students</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                <span class="material-symbols-outlined">campaign</span>
                <span class="font-medium">Announcement</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                <span class="material-symbols-outlined">videocam</span>
                <span class="font-medium">Live class</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                <span class="material-symbols-outlined">book_2</span>
                <span class="font-medium">Quran pages</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg bg-primary text-white shadow-md" href="#">
                <span class="material-symbols-outlined">history_edu</span>
                <span class="font-medium">History</span>
            </a>
            <div class="pt-8"></div>
            <a class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-medium">Settings</span>
            </a>
        </nav>
        <div class="p-4 mt-auto">
            <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-logout-bg dark:bg-red-900/30 text-logout-text dark:text-red-400 font-medium hover:opacity-90 transition-all duration-200">
                <span class="material-symbols-outlined transform rotate-180">logout</span>
                <span>Logout</span>
            </Link>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-background-light dark:bg-background-dark relative">
        <!-- Desktop and Mobile Header -->
        <header class="h-16 md:h-24 px-4 flex items-center justify-between flex-shrink-0 bg-background-light dark:bg-background-dark z-10 border-b md:border-b-0 border-border-light dark:border-border-dark">
            <div class="hidden md:block">
                <h1 class="text-xl md:text-2xl font-semibold text-text-secondary-light dark:text-text-secondary-dark">Assalaikum Alaykum, <span class="text-text-primary-light dark:text-text-primary-dark">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</span></h1>
            </div>
            <div class="md:hidden text-base font-semibold text-text-secondary-light dark:text-text-secondary-dark">
                <span class="text-text-primary-light dark:text-text-primary-dark">Class History</span>
            </div>
            <div class="flex items-center gap-4 md:gap-8">
                <div class="text-sm text-text-secondary-light dark:text-text-secondary-dark">
                    {{ currentDate }}
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border border-primary/30 flex items-center justify-center text-primary bg-surface-light dark:bg-surface-dark">
                        <span class="material-symbols-outlined text-sm md:text-base">person</span>
                    </div>
                    <div class="hidden md:block">
                        <div class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark">{{ $page.props.auth.user.first_name }}</div>
                        <div class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ $page.props.auth.user.email }}</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto px-4 py-6 md:py-12 pb-12">
            <div class="w-full">
                <h2 class="text-xl md:text-2xl font-bold mb-6 md:mb-8 text-text-primary-light dark:text-text-primary-dark">Class history</h2>
                <div class="space-y-4 md:space-y-6">
                    <div v-for="session in sessions" :key="session.id" class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark p-4 md:p-8 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                        <div class="flex-1 space-y-3 md:space-y-4">
                            <h3 class="text-xl md:text-3xl font-medium text-text-primary-light dark:text-text-primary-dark tracking-tight">{{ formatDate(session.started_at) }}</h3>
                            <div class="space-y-2 md:space-y-3 pt-1">
                                <div class="text-primary font-medium">
                                    Surah: {{ session.title }}
                                </div>
                                <div class="text-text-secondary-light dark:text-text-secondary-dark text-sm md:text-base">
                                    Time: {{ formatTime(session.started_at) }} - {{ formatTime(session.ended_at) }}
                                </div>
                                <div class="text-text-secondary-light dark:text-text-secondary-dark text-sm md:text-base">
                                    Teacher: {{ session.teacher.first_name }} {{ session.teacher.last_name }}
                                </div>
                                <div class="text-text-secondary-light dark:text-text-secondary-dark text-sm md:text-base">
                                    Student: {{ session.students.map(s => s.first_name + ' ' + s.last_name).join(' & ') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="w-full md:w-auto bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-text-secondary-light dark:text-text-secondary-dark px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                                View details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        sessions: Array,
    },
    data() {
        return {
            sidebarOpen: false
        }
    },
    computed: {
        currentDate() {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date().toLocaleDateString('en-US', options);
        }
    },
    mounted() {
        // Initialize sidebar visibility based on viewport and keep it in sync on resize
        this.sidebarOpen = window.innerWidth >= 768;
        window.addEventListener('resize', this.handleResize);
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.handleResize);
    },
    methods: {
        handleResize() {
            // Keep sidebar visible on desktop, hide on small screens
            this.sidebarOpen = window.innerWidth >= 768;
        },
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        },
        formatTime(dateTime) {
            const options = { hour: '2-digit', minute: '2-digit', hour12: true };
            return new Date(dateTime).toLocaleTimeString('en-US', options);
        }
    }
}
</script>
