<script setup>
import { router, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({
    students: Array,
    surahs: Array,
});

const page = usePage();

const currentDate = computed(() => {
    return new Date().toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const form = useForm({
    students: [],
    title: '',
    surah_id: null,
    page: null,
    date: '',
    time: '',
    notes: '',
});

const scheduleClass = () => {
    form.post(route('class.schedule'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const startClass = () => {
    form.post(route('live-class.start'), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('live'));
        },
    });
};

const logout = () => {
    router.post(route("logout"));
};

const showAllStudents = ref(false);
const visibleStudents = computed(() => {
    if (showAllStudents.value) {
        return props.students;
    }
    return props.students.slice(0, 3);
});

const sidebarOpen = ref(false);

const handleResize = () => {
    if (window.innerWidth >= 768) { // md breakpoint
        sidebarOpen.value = false;
    }
};

// Check if current route is the prepare class route
const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};

onMounted(() => {
    sidebarOpen.value = window.innerWidth >= 768; // Initialize sidebar visibility
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark antialiased h-screen overflow-hidden flex flex-col md:flex-row transition-colors duration-200">
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

    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed md:static md:translate-x-0 top-0 left-0 h-full w-64 md:w-72 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex-shrink-0 flex flex-col z-40 md:z-auto transition-all duration-300 ease-in-out">
        <div class="md:hidden absolute inset-0 bg-white/30 dark:bg-gray-900/30 backdrop-blur-md -z-10 md:backdrop-blur-none md:bg-surface-light dark:md:bg-surface-dark"></div>
        <div class="p-4 md:p-8 mb-4 flex justify-center">
            <img src="/images/app_logo.jpg" alt="App Logo" class="h-40 w-auto">
        </div>
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <Link :href="route('teacher.index')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('teacher.index'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('teacher.index')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">home</span>
                <span class="font-medium">Home</span>
            </Link>
            <Link :href="route('prepre_class')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('prepre_class'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('prepre_class')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">menu_book</span>
                <span class="font-medium">Prepare</span>
            </Link>
            <Link :href="route('teacher.students')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('teacher.students'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('teacher.students')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">playlist_add_check</span>
                <span class="font-medium">Students</span>
            </Link>
            <Link :href="route('live')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('live'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('live')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">live_tv</span>
                <span class="font-medium">Live class</span>
            </Link>
            <Link :href="route('quran.surahs')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('quran.surahs'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('quran.surahs')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">auto_stories</span>
                <span class="font-medium">Quran library</span>
            </Link>
            <Link :href="route('class.history')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('teacher.history'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('teacher.history')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">history_edu</span>
                <span class="font-medium">Class history</span>
            </Link>
            <Link :href="route('announcement')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('announcement'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('announcement')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">campaign</span>
                <span class="font-medium">Announcement</span>
            </Link>
        </nav>
        <div class="p-4 mt-auto border-t border-gray-100 dark:border-gray-800 space-y-2">
            <Link :href="route('profile.edit')" :class="{'bg-primary text-white shadow-md': isCurrentRoute('profile.edit'), 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 group': !isCurrentRoute('profile.edit')}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                <span class="material-icons-outlined">settings</span>
                <span class="font-medium">Settings</span>
            </Link>
            <button @click="logout" class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group">
                <span class="material-icons-outlined">logout</span>
                <span class="font-medium">Logout</span>
            </button>
        </div>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-background-light dark:bg-background-dark relative">
        <!-- Desktop and Mobile Header -->
        <header class="h-16 md:h-24 px-4 flex items-center justify-between flex-shrink-0 bg-background-light dark:bg-background-dark z-10 border-b md:border-b-0 border-border-light dark:border-border-dark">
            <div class="md:hidden text-base font-semibold text-text-secondary-light dark:text-text-secondary-dark">
                <span class="text-text-primary-light dark:text-text-primary-dark">Prepare Class</span>
            </div>
            <div class="hidden md:block">
                <h1 class="text-xl md:text-2xl font-semibold text-text-secondary-light dark:text-text-secondary-dark">Assalaikum Alaykum, <span class="text-text-primary-light dark:text-text-primary-dark">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</span></h1>
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
                <div class="space-y-6">
                    <section class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                        <h2 class="text-2xl font-display font-semibold text-gray-800 dark:text-white mb-6">Prepare my class</h2>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Select students</h3>
                        <div class="space-y-4">
                        <p v-if="form.errors.students" class="text-sm text-red-600 -mb-2">{{ form.errors.students }}</p>
                        <div v-for="student in visibleStudents" :key="student.id" class="flex flex-col md:flex-row items-start md:items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750 border border-gray-100 dark:border-gray-700 transition-colors">
                            <div class="w-full md:w-auto flex items-center gap-4">
                                <input type="checkbox" :value="student.id" v-model="form.students" class="w-6 h-6 text-primary bg-white border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <span class="font-medium text-lg text-gray-800 dark:text-gray-200">{{ student.first_name }} {{ student.last_name }}</span>
                            </div>
                            <div class="flex-1 w-full md:w-auto flex-col px-10 md:px-0">
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <span>{{ student.surahs_completed }} of 114 surahs</span>
                                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ student.progress }}% completed</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                    <div class="bg-primary h-1.5 rounded-full" :style="{ width: student.progress + '%' }"></div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-4 ml-auto">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" :class="{'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]': student.online, 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]': !student.online}"></span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ student.online ? 'Online' : 'Offline' }}</span>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <span class="material-icons-outlined">more_vert</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="students.length > 3" class="flex justify-center mt-6">
                        <button @click="showAllStudents = !showAllStudents" class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">
                            <span class="material-icons-outlined text-lg mr-1">{{ showAllStudents ? 'expand_less' : 'expand_more' }}</span>
                            {{ showAllStudents ? 'View less' : 'View all' }}
                        </button>
                    </div>
                </section>
                <section class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <h3 class="text-2xl font-display font-semibold text-gray-800 dark:text-white mb-6">Choose Surah/Quran page</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="md:col-span-2 space-y-6">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <div class="relative">
                                        <select v-model="form.surah_id" class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md appearance-none">
                                            <option :value="null">Select Surah</option>
                                            <option v-for="surah in surahs" :key="surah.id" :value="surah.id">
                                                {{ surah.name_simple }}
                                            </option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-400">
                                            <span class="material-icons-outlined">expand_more</span>
                                        </div>
                                    </div>
                                    <p v-if="form.errors.surah_id" class="text-sm text-red-600 mt-1">{{ form.errors.surah_id }}</p>
                                </div>
                                <div class="flex-1">
                                    <input v-model="form.page" class="block w-full pl-3 pr-3 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-primary focus:border-primary sm:text-sm rounded-md placeholder-gray-300 dark:placeholder-gray-600" placeholder="page" type="number" />
                                    <p v-if="form.errors.page" class="text-sm text-red-600 mt-1">{{ form.errors.page }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                                    <input v-model="form.title" class="block w-full pl-3 pr-3 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-primary focus:border-primary sm:text-sm rounded-md placeholder-gray-300 dark:placeholder-gray-600" placeholder="Suratul Waqiah" type="text" />
                                    <p v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</p>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                                    <input v-model="form.notes" class="block w-full pl-3 pr-3 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-primary focus:border-primary sm:text-sm rounded-md placeholder-gray-300 dark:placeholder-gray-600" placeholder="Focus on tajweed" type="text" />
                                    <p v-if="form.errors.notes" class="text-sm text-red-600 mt-1">{{ form.errors.notes }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Date and time</label>
                                <div class="flex flex-col md:flex-row gap-6">
                                    <div class="flex-1">
                                        <input v-model="form.date" class="block w-full pl-3 pr-3 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-primary focus:border-primary sm:text-sm rounded-md placeholder-gray-300 dark:placeholder-gray-600" type="date" />
                                        <p v-if="form.errors.date" class="text-sm text-red-600 mt-1">{{ form.errors.date }}</p>
                                    </div>
                                    <div class="flex-1">
                                        <input v-model="form.time" class="block w-full pl-3 pr-3 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-primary focus:border-primary sm:text-sm rounded-md placeholder-gray-300 dark:placeholder-gray-600" type="time" />
                                        <p v-if="form.errors.time" class="text-sm text-red-600 mt-1">{{ form.errors.time }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4 justify-start pt-0 lg:pt-0">
                            <button @click="startClass" :disabled="form.processing" class="w-full bg-primary hover:bg-secondary text-white font-semibold py-4 px-6 rounded-lg shadow transition-colors text-lg disabled:opacity-50">
                                Start class
                            </button>
                            <button @click="scheduleClass" :disabled="form.processing" class="w-full bg-transparent hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200 font-semibold py-4 px-6 rounded-lg border border-gray-400 dark:border-gray-600 transition-colors text-lg disabled:opacity-50">
                                Schedule class
                            </button>
                        </div>
                    </div>
                </section>
                </div>
            </div>
        </div>
    </main>
</body>
</template>
