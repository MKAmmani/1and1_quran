<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  surah_details: Object,
  verses: Object,
  currentPage: Number,
  totalPages: Number,
});

const searchAyah = ref('');
const searchedVerse = ref(null);

const fetchAyah = async () => {
    if (!searchAyah.value) {
        searchedVerse.value = null;
        return;
    }
    try {
        const response = await axios.get(`/quran/ayah/${props.surah_details.id}/${searchAyah.value}`);
        searchedVerse.value = response.data.verse;
    } catch (error) {
        console.error('Error fetching ayah:', error);
        searchedVerse.value = null;
    }
};

const currentDate = ref(new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));

</script>

<template>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark antialiased h-screen overflow-hidden flex transition-colors duration-300">
    <aside class="w-64 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex flex-col justify-between h-full shadow-sm z-10 transition-colors duration-300">
        <div>
            <div class="p-6 flex justify-center mb-6">
                <div class="w-32">
                    <img alt="1-on-1 Quran Classes Logo" class="w-full h-auto object-contain" src="/images/app_logo.jpg" />
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
            <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center px-4 py-3 text-sm font-medium text-muted-light dark:text-muted-dark hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <span class="material-icons text-[20px] mr-3">logout</span>
                Logout
            </Link>
            <div class="flex justify-end pt-4 pr-2">
                <span class="material-icons text-muted-light dark:text-muted-dark text-sm cursor-pointer">compare_arrows</span>
            </div>
        </div>
    </aside>
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        <header class="h-20 bg-surface-light dark:bg-surface-dark flex items-center justify-between px-8 border-b border-border-light dark:border-border-dark transition-colors duration-300">
            <div>
                <h1 class="text-xl font-semibold text-text-light dark:text-text-dark">Assalaikum Alaykum, {{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</h1>
            </div>
            <div class="flex items-center space-x-6">
                <span class="text-sm text-muted-light dark:text-muted-dark hidden md:block">{{ currentDate }}</span>
                <div class="flex items-center pl-6 border-l border-gray-200 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900 flex items-center justify-center text-primary dark:text-green-300 border border-green-100 dark:border-green-800">
                        <span class="material-icons">person_outline</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-text-light dark:text-text-dark">{{ $page.props.auth.user.first_name }}</p>
                        <p class="text-xs text-muted-light dark:text-muted-dark">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-8 bg-background-light dark:bg-background-dark transition-colors duration-300">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text-light dark:text-text-dark mb-4 md:mb-0">{{ surah_details.name_simple }}</h2>
                <div class="relative w-full md:w-72">
                    <input v-model="searchAyah" class="w-full pl-4 pr-10 py-2 rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition-colors duration-300 placeholder-muted-light dark:placeholder-muted-dark" placeholder="Search by ayah number" type="number" @keyup.enter="fetchAyah" />
                    <button @click="fetchAyah" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="material-icons text-muted-light dark:text-muted-dark text-lg">search</span>
                    </button>
                </div>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-border-light dark:border-border-dark p-4 md:p-8 flex flex-col items-center justify-center relative min-h-[600px] transition-colors duration-300">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold">{{ surah_details.name_simple }}</h2>
                    <h3 class="text-2xl font-serif">{{ surah_details.name_arabic }}</h3>
                </div>

                <div v-if="surah_details.bismillah_pre && !searchedVerse" class="text-center my-4 text-2xl font-serif">
                    بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ
                </div>

                <div v-if="searchedVerse">
                    <div class="flex flex-row items-center space-x-4">
                        <div class="w-1/12 text-center text-lg font-bold">{{ searchedVerse.verse_key }}</div>
                        <div class="w-11/12 flex flex-col">
                            <p class="text-right text-3xl mb-4 font-serif leading-loose">{{ searchedVerse.text_uthmani }}</p>
                            <p v-if="searchedVerse.translations && searchedVerse.translations.length > 0" class="text-left text-xl text-gray-600 dark:text-gray-400">{{ searchedVerse.translations[0].text }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="flex flex-col space-y-8">
                    <div v-for="verse in verses.verses" :key="verse.id" class="flex flex-row items-center space-x-4">
                        <div class="w-1/12 text-center text-lg font-bold">{{ verse.verse_key }}</div>
                        <div class="w-11/12 flex flex-col">
                            <p class="text-right text-3xl mb-4 font-serif leading-loose">{{ verse.text_uthmani }}</p>
                            <p v-if="verse.translations && verse.translations.length > 0" class="text-left text-xl text-gray-600 dark:text-gray-400">{{ verse.translations[0].text }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between w-full mt-8">
                    <Link v-if="currentPage > 1" :href="route('quran.surah', { surah: surah_details.id, page: currentPage - 1 })" class="px-4 py-2 bg-primary text-white rounded">
                        <span class="material-icons text-2xl rotate-180">play_arrow</span>
                    </Link>
                    <div v-else></div>

                    <Link v-if="currentPage < totalPages" :href="route('quran.surah', { surah: surah_details.id, page: currentPage + 1 })" class="px-4 py-2 bg-primary text-white rounded">
                        <span class="material-icons text-2xl ">play_arrow</span>
                    </Link>
                    <div v-else></div>
                </div>
            </div>
        </div>
    </main>

</body>
</template>