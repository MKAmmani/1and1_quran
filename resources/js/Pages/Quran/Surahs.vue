<script setup>
import { onMounted, ref, computed, onBeforeUnmount } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  surahs: Object
});

const search = ref('')
const sidebarOpen = ref(false);
const page = usePage();

const filteredSurahs = computed(() => {
    if (!search.value) {
        return props.surahs.chapters;
    }
    return props.surahs.chapters.filter(surah =>
        surah.name_simple.toLowerCase().includes(search.value.toLowerCase()) ||
        surah.name_arabic.toLowerCase().includes(search.value.toLowerCase()) ||
        surah.translated_name.name.toLowerCase().includes(search.value.toLowerCase())
    );
});

const toggleTheme = () => {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
}

const handleResize = () => {
    sidebarOpen.value = window.innerWidth >= 768;
};

onMounted(() => {
    if (localStorage.theme === 'dark') {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
    handleResize();
    window.addEventListener('resize', handleResize);
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-sans antialiased transition-colors duration-200">
    <!-- Mobile Header with Hamburger Menu -->
    <header class="md:hidden h-16 px-4 flex items-center justify-between flex-shrink-0 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark z-20">
        <button @click="sidebarOpen = true" class="p-2 rounded-lg text-text-light dark:text-text-dark">
            <span class="material-icons">menu</span>
        </button>
        <div class="w-24">
            <img alt="1-on-1 Quran Classes Logo" class="w-full h-auto object-contain" src="/images/app_logo.jpg" />
        </div>
        <div class="w-10"></div> <!-- Spacer for alignment -->
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>

<div class="flex h-screen overflow-hidden">
<aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed md:static md:translate-x-0 top-0 left-0 h-full w-64 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex flex-col justify-between z-40 md:z-auto transition-all duration-300 ease-in-out shadow-sm">
<div>
<div class="p-6 flex justify-center mb-6">
<img alt="placeholder" class="hidden" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBitpuLeGUBxVwelbVxUGTabZuI9YH1CMGYRKmbCatLghV12E-da741MIY948KjrUQquSKB9F6jUDg5vyF4nt39DTx94e730rpcW_C4Lahh9hKhy4F6DvdO4OrITMiLumtrq5tDCrrcs2wq5TChbHGsaASz4Ljp60TjyRm68wf9TsL1I1pwBwoj0r3AKSnja60KrZ4L2dXoTr5cbrri8x_beXn3rV1zyiJuG7TAblM7BpNcadl1INMAXOQhk_DTRz20f6plxiN56RM"/> 
<div class="w-40">
<img alt="1-on-1 Quran Classes Logo" class="w-full h-auto object-contain" src="/images/app_logo.jpg"/>
</div>
</div>
<nav class="px-4 space-y-1">
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">home</span>
                    Home
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">class</span>
                    Prepare Class
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">playlist_add_check</span>
                    Students
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">campaign</span>
                    Announcement
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">videocam</span>
                    Live class
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg shadow-md transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">menu_book</span>
                    Quran Pages
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">history_edu</span>
                    Class history
                </a>
</nav>
</div>
<div class="px-4 py-6 space-y-1 border-t border-gray-100 dark:border-gray-800">
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">settings</span>
                Settings
            </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" href="#">
<span class="material-icons text-[20px] mr-3">logout</span>
                Logout
            </a>
<div class="flex justify-end pt-4 pr-2">
<span class="material-icons text-muted-light dark:text-muted-dark text-sm cursor-pointer">compare_arrows</span>
</div>
</div>
</aside>
<main class="flex-1 flex flex-col h-screen overflow-y-auto">
<header class="bg-surface-light dark:bg-surface-dark px-8 py-6 flex flex-col md:flex-row justify-between items-start md:items-center border-b border-border-light dark:border-border-dark sticky top-0 z-10">
<div class="mb-4 md:mb-0">
<h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-100">Assalaikum Alaykum, {{ $page.props.auth.user.first_name }}</h2>
</div>
<div class="flex flex-col md:flex-row items-end md:items-center gap-6">
<span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-primary">
<span class="material-symbols-outlined">person</span>
</div>
<div class="flex flex-col">
<span class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $page.props.auth.user.first_name }}</span>
<span class="text-xs text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.email }}</span>
</div>
</div>
</div>
</header>
<div class="p-6 md:p-8 max-w-7xl mx-auto w-full">
<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
<h3 class="text-2xl font-bold text-gray-800 dark:text-white w-full md:w-auto">Quran Surahs</h3>
<div class="relative w-full md:w-72">
<input v-model="search" class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-surface-dark text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-shadow" placeholder="Search surah" type="text"/>
<span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 dark:text-gray-500 pointer-events-none">search</span>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
<Link :href="route('quran.surah', { surah: surah.id })" v-for="surah in filteredSurahs" :key="surah.id" class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-border-light dark:border-border-dark flex items-center justify-between hover:shadow-md transition-shadow cursor-pointer">
<div class="flex items-center gap-4">
<div class="relative flex items-center justify-center w-12 h-12">
<svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
<path d="M12 2L14.8 4.6L18.5 4.1L19.4 7.8L22.8 9.5L20.9 12.9L22.8 16.3L19.4 18L18.5 21.7L14.8 21.2L12 23.8L9.2 21.2L5.5 21.7L4.6 18L1.2 16.3L3.1 12.9L1.2 9.5L4.6 7.8L5.5 4.1L9.2 4.6L12 2Z"></path>
</svg>
<span class="absolute text-sm font-bold text-primary">{{ surah.id }}</span>
</div>
<div class="flex flex-col">
<span class="font-semibold text-gray-800 dark:text-white text-base">{{ surah.name_simple }}</span>
<div class="flex items-center gap-1 mt-1">
<span class="material-symbols-outlined text-[16px] text-gray-400">menu_book</span>
<span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ surah.verses_count }} Ayat</span>
</div>
</div>
</div>
<div>
<span class="font-serif text-xl font-bold text-gray-800 dark:text-gray-200">{{ surah.name_arabic }}</span>
</div>
</Link>
</div>
<div class="flex justify-between items-center mt-auto pb-4">
<button class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white transition-colors">
<span class="material-symbols-outlined">arrow_left</span>
</button>
<button class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white transition-colors">
<span class="material-symbols-outlined">arrow_right</span>
</button>
</div>
</div>
</main>
</div>


</body>
</template>
    