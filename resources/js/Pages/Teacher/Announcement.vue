<script setup>
import { router, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from 'vue';

const logout = () => {
    router.post(route("logout"));
};

// Check if current route is the announcement route
const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};

// Mobile menu functionality
const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

onMounted(() => {
    const handleClickOutside = (event) => {
        const menuToggle = document.getElementById('menuToggle');
        const mobileNav = document.getElementById('mobileNav');

        if (isMobileMenuOpen.value && menuToggle && mobileNav &&
            !mobileNav.contains(event.target) &&
            !menuToggle.contains(event.target)) {
            closeMobileMenu();
        }
    };

    document.addEventListener('click', handleClickOutside);

    // Clean up the event listener when component is unmounted
    onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside);
    });
});
</script>

<template>
    <body class="bg-gray-50 text-gray-800 min-h-screen">

    <!-- Desktop Layout -->
    <div class="hidden lg:flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Logo -->
            <div class="px-8 pt-8 pb-4">
                <img
                    src="/images/app_logo.jpg"
                    alt="1-on-1 Quran Classes Logo"
                    class="w-48 mx-auto"
                />
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-6 pt-2 pb-6 flex flex-col">
                <div class="space-y-2">
                    <Link
                        :href="route('teacher.index')"
                        :class="{'bg-teal-600 text-white': isCurrentRoute('teacher.index'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('teacher.index')}"
                        class="flex items-center gap-4 px-6 py-4 rounded-lg font-medium"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                        Home
                    </Link>
                    <Link
                        :href="route('prepre_class')"
                        :class="{'bg-teal-600 text-white': isCurrentRoute('prepre_class'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('prepre_class')}"
                        class="flex items-center gap-4 px-6 py-4 rounded-lg hover:bg-gray-100"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            />
                        </svg>
                        Prepare
                    </Link>
                    <a
                        href="#"
                        class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                        Students
                    </a>
                    <Link
                        :href="route('announcement')"
                        :class="{'bg-teal-600 text-white': isCurrentRoute('announcement'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('announcement')}"
                        class="flex items-center gap-4 px-6 py-4 rounded-lg font-medium"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"
                            />
                        </svg>
                        Announcement
                    </Link>
                    <a
                        href="#"
                        class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                            />
                        </svg>
                        Live class
                    </a>
                    <a
                        href="#"
                        class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                        Quran Pages
                    </a>
                    <a
                        href="#"
                        class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            />
                        </svg>
                        Chat
                    </a>
                    <a
                        href="#"
                        class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                        Settings
                    </a>
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100 mt-auto w-full text-left"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        />
                    </svg>
                    Logout
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10">
            <!-- Header -->
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-semibold">Assalaikum Alaykum, Ustadha Aisha</h1>
                    <p class="text-gray-500">Monday, January 30 2023</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="font-medium">Aisha</p>
                        <p class="text-sm text-gray-500">info@aisha.com</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-200 rounded-full border-2 border-dashed"></div>
                </div>
            </header>

            <!-- Make Announcement -->
            <section class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-3xl font-bold text-teal-600 mb-8 flex items-center gap-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Make announcement
                </h2>

                <!-- Select List -->
                <h3 class="text-xl font-semibold mb-6">Select list</h3>

                <div class="space-y-4 mb-8">
                    <!-- Student Row 1 -->
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-1 flex justify-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="w-5 h-5 text-teal-600 bg-white border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 checked:bg-teal-600 checked:border-teal-600" checked>
                            </label>
                        </div>
                        <div class="col-span-2">
                            <p class="font-medium text-lg">Salmah</p>
                            <p class="text-sm text-gray-500">13 of 114 surahs</p>
                        </div>
                        <div class="col-span-6">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-teal-600 h-3 rounded-full" style="width: 11%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">11% completed</p>
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <span class="flex items-center gap-2 text-green-600"><span class="w-3 h-3 bg-green-600 rounded-full"></span> Online</span>
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button class="text-gray-400 text-xl leading-none">⋮</button>
                        </div>
                    </div>

                    <!-- Student Row 2 -->
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-1 flex justify-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="w-5 h-5 text-teal-600 bg-white border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 checked:bg-teal-600 checked:border-teal-600" checked>
                            </label>
                        </div>
                        <div class="col-span-2">
                            <p class="font-medium text-lg">Ali</p>
                            <p class="text-sm text-gray-500">33 of 114 surahs</p>
                        </div>
                        <div class="col-span-6">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-teal-600 h-3 rounded-full" style="width: 21%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">21% completed</p>
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <span class="flex items-center gap-2 text-green-600"><span class="w-3 h-3 bg-green-600 rounded-full"></span> Online</span>
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button class="text-gray-400 text-xl leading-none">⋮</button>
                        </div>
                    </div>

                    <!-- Student Row 3 -->
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-1 flex justify-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="w-5 h-5 text-teal-600 bg-white border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 checked:bg-teal-600 checked:border-teal-600" checked>
                            </label>
                        </div>
                        <div class="col-span-2">
                            <p class="font-medium text-lg">Rashad</p>
                            <p class="text-sm text-gray-500">16 of 114 surahs</p>
                        </div>
                        <div class="col-span-6">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-teal-600 h-3 rounded-full" style="width: 16%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">16% completed</p>
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <span class="flex items-center gap-2 text-red-600"><span class="w-3 h-3 bg-red-600 rounded-full"></span> Offline</span>
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button class="text-gray-400 text-xl leading-none">⋮</button>
                        </div>
                    </div>

                    <!-- Student Row 4 -->
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-1 flex justify-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="w-5 h-5 text-teal-600 bg-white border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 checked:bg-teal-600 checked:border-teal-600">
                            </label>
                        </div>
                        <div class="col-span-2">
                            <p class="font-medium text-lg">Rashad</p>
                            <p class="text-sm text-gray-500">16 of 114 surahs</p>
                        </div>
                        <div class="col-span-6">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-teal-600 h-3 rounded-full" style="width: 16%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">16% completed</p>
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <span class="flex items-center gap-2 text-red-600"><span class="w-3 h-3 bg-red-600 rounded-full"></span> Offline</span>
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button class="text-gray-400 text-xl leading-none">⋮</button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mb-10">
                    <button class="text-teal-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        Close all
                    </button>
                </div>

                <!-- Announcement Input -->
                <div class="border-2 border-teal-600 rounded-xl p-6 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <input type="text" placeholder="Make announcement" class="text-lg text-gray-700 outline-none flex-1">
                    </div>
                    <button class="bg-teal-600 text-white p-4 rounded-full hover:bg-teal-700 shadow-lg">
                        <svg class="w-6 h-6 rotate-[-45deg]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-7-9-7v14z"/></svg>
                    </button>
                </div>
            </section>
        </main>
    </div>

    <!-- Mobile Layout -->
    <div class="lg:hidden flex flex-col min-h-screen">
        <!-- Mobile Header with Hamburger on left -->
        <div class="bg-white shadow-md px-4 py-3 flex items-center">
            <button id="menuToggle" @click="toggleMobileMenu" class="text-gray-700 focus:outline-none mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <img src="/images/app_logo.jpg" alt="1-on-1 Quran Classes Logo" class="w-24">
        </div>

        <!-- Sidebar (Hidden by default) - Slide in from left -->
        <aside id="mobileNav" :class="{'hidden': !isMobileMenuOpen, 'translate-x-0': isMobileMenuOpen, '-translate-x-full': !isMobileMenuOpen}" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg flex-col z-50 transform transition-transform duration-300 ease-in-out">
            <!-- Navigation -->
            <nav class="px-4 pt-2 pb-4 flex flex-col">
                <div class="space-y-2">
                    <Link
                        :href="route('teacher.index')"
                        :class="{'bg-teal-600 text-white': isCurrentRoute('teacher.index'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('teacher.index')}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm"
                        @click="closeMobileMenu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </Link>
                    <Link
                        :href="route('prepre_class')"
                        :class="{'bg-teal-600 text-white': isCurrentRoute('prepre_class'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('prepre_class')}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm"
                        @click="closeMobileMenu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Prepare
                    </Link>
                    <a href="#" class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm" @click="closeMobileMenu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Students
                    </a>
                    <Link
                        :href="route('announcement')"
                        :class="{'bg-teal-600 text-white': isCurrentRoute('announcement'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('announcement')}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm"
                        @click="closeMobileMenu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Announcement
                    </Link>
                    <a href="#" class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm" @click="closeMobileMenu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Live class
                    </a>
                    <a href="#" class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm" @click="closeMobileMenu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Quran Pages
                    </a>
                    <a href="#" class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm" @click="closeMobileMenu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chat
                    </a>
                    <a href="#" class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm" @click="closeMobileMenu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100 mt-auto w-full text-left"
                    @click="closeMobileMenu"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        />
                    </svg>
                    Logout
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-4 bg-gray-50 flex flex-col">
            <h1 class="text-xl font-bold mb-2">Make announcement</h1>

            <h2 class="text-lg font-semibold mt-8 mb-4">Select students</h2>
            <div class="space-y-6 mb-6">
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-4 cursor-pointer flex-1">
                        <input type="checkbox" class="w-5 h-5 text-teal-600 bg-white border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 checked:bg-teal-600 checked:border-teal-600" checked>
                        <div class="flex-1">
                            <p class="font-medium text-lg">Salmah</p>
                            <p class="text-sm text-gray-500">13 of 114 surahs</p>
                            <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                                <div class="bg-teal-600 h-3 rounded-full" style="width: 11%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">11% completed</p>
                        </div>
                    </label>
                    <div class="flex items-center">
                        <span class="flex items-center gap-2 text-green-600"><span class="w-3 h-3 bg-green-600 rounded-full"></span> Online</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-4 cursor-pointer flex-1">
                        <input type="checkbox" class="w-5 h-5 text-teal-600 bg-white border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 checked:bg-teal-600 checked:border-teal-600" checked>
                        <div class="flex-1">
                            <p class="font-medium text-lg">Ali</p>
                            <p class="text-sm text-gray-500">33 of 114 surahs</p>
                            <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                                <div class="bg-teal-600 h-3 rounded-full" style="width: 21%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">21% completed</p>
                        </div>
                    </label>
                    <div class="flex items-center">
                        <span class="flex items-center gap-2 text-green-600"><span class="w-3 h-3 bg-green-600 rounded-full"></span> Online</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-4 cursor-pointer flex-1">
                        <input type="checkbox" class="w-5 h-5 text-teal-600 bg-white border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 checked:bg-teal-600 checked:border-teal-600" checked>
                        <div class="flex-1">
                            <p class="font-medium text-lg">Rashad</p>
                            <p class="text-sm text-gray-500">16 of 114 surahs</p>
                            <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                                <div class="bg-teal-600 h-3 rounded-full" style="width: 16%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">16% completed</p>
                        </div>
                    </label>
                    <div class="flex items-center">
                        <span class="flex items-center gap-2 text-red-600"><span class="w-3 h-3 bg-red-600 rounded-full"></span> Offline</span>
                    </div>
                </div>
                <!-- Add more as needed -->
            </div>

            <!-- Announcement Input -->
            <div class="border-2 border-teal-600 rounded-xl p-6 flex items-center justify-between flex-1">
                <div class="flex items-center gap-4">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <input type="text" placeholder="Make announcement" class="text-lg text-gray-700 outline-none flex-1">
                </div>
                <button class="bg-teal-600 text-white p-4 rounded-full hover:bg-teal-700 shadow-lg">
                    <svg class="w-6 h-6 rotate-[-45deg]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-7-9-7v14z"/></svg>
                </button>
            </div>
        </div>
    </div>

</body>
</template>