<script setup>
import { Link } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';

const isScrolled = ref(false);

// Function to close the mobile menu
const closeMobileMenu = () => {
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
        mobileMenu.classList.add('hidden');
    }
};

onMounted(() => {
    // Toggle mobile menu when hamburger button is clicked
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (event) => {
            const isClickInsideMenu = mobileMenu.contains(event.target);
            const isClickOnButton = mobileMenuButton.contains(event.target);

            if (!isClickInsideMenu && !isClickOnButton && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Handle scroll effect for header
    window.addEventListener('scroll', () => {
        isScrolled.value = window.scrollY > 10;
    });
});
</script>

<template>
    <body class="bg-gray-50 text-gray-800">
        <!-- Mobile Header (Hidden on larger screens) -->
        <header class="bg-white shadow-sm lg:hidden fixed w-full top-0 z-50">
            <div class="px-4 py-3 flex justify-between items-center">
                <div class="flex items-center">
                    <img
                        src="/images/app_logo.jpg"
                        alt="1-on-1 Quran Classes Logo"
                        class="h-12 w-auto md:w-32 object-contain rounded-md"
                    />
                </div>
                <button
                    id="mobileMenuButton"
                    class="text-gray-700 focus:outline-none"
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
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation Menu (Hidden by default) -->
            <div
                id="mobileMenu"
                class="hidden bg-white shadow-lg absolute top-full left-0 right-0 z-50"
            >
                <nav class="py-4 px-4 flex flex-col space-y-4">
                    <Link
                        :href="route('welcome')"
                        class="text-gray-600 hover:text-teal-600 py-2"
                        @click="closeMobileMenu"
                    >
                        Home
                    </Link>
                    <Link
                        :href="route('login')"
                        class="text-gray-600 hover:text-teal-600 py-2"
                        @click="closeMobileMenu"
                    >
                        About us
                    </Link>
                    <Link
                        :href="route('login')"
                        class="text-gray-600 hover:text-teal-600 py-2"
                        @click="closeMobileMenu"
                    >
                        Contact
                    </Link>
                    <div class="pt-4 border-t border-gray-200 flex flex-col space-y-3">
                        <Link
                            :href="route('login')"
                            class="border border-teal-600 text-teal-600 px-4 py-2 rounded-md hover:bg-teal-50 text-center"
                        >
                            Log In
                        </Link>
                        <Link
                            :href="route('register')"
                            class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 text-center"
                        >
                            Sign up for free
                        </Link>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Desktop Header (Hidden on small screens) -->
        <header
            class="bg-white shadow-sm hidden lg:block fixed w-full top-0 z-50 transition-all duration-300"
            :class="{ 'py-2 shadow-md': isScrolled, 'py-4': !isScrolled }"
        >
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center"
            >
                <div class="flex items-center">
                    <img
                        src="/images/app_logo.jpg"
                        alt="1-on-1 Quran Classes Logo"
                        class="h-12 md:h-16 w-auto md:w-40 object-contain rounded-md transition-all duration-300"
                        :class="{ 'h-10 md:h-14': isScrolled }"
                    />
                    <nav class="ml-10 space-x-8">
                        <Link
                            :href="route('welcome')"
                            class="text-gray-600 hover:text-teal-600 font-medium transition-colors duration-300"
                            >Home</Link
                        >
                        <Link
                            :href="route('login')"
                            class="text-gray-600 hover:text-teal-600 font-medium transition-colors duration-300"
                            >About us</Link
                        >
                        <Link
                            :href="route('login')"
                            class="text-gray-600 hover:text-teal-600 font-medium transition-colors duration-300"
                            >Contact</Link
                        >
                    </nav>
                </div>
                <div class="space-x-4">
                    <Link
                        :href="route('login')"
                        class="border border-teal-600 text-teal-600 px-4 py-2 rounded-md hover:bg-teal-50 font-medium transition-all duration-300"
                    >
                        Log In
                    </Link>
                    <Link
                        :href="route('register')"
                        class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 font-medium transition-all duration-300 shadow-md hover:shadow-lg"
                    >
                        Sign up for free
                    </Link>
                </div>
            </div>
        </header>

        <!-- Mobile padding for fixed header -->
        <div class="h-16 lg:hidden w-full"></div>

        <!-- Hero Section -->
        <section class="bg-gradient-to-b from-gray-50 to-teal-50 py-32 pt-40">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col lg:flex-row items-center"
            >
                <div class="lg:w-1/2 animate-fade-in-up">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Learn the Holy Qur'an.<br />
                        <span class="text-teal-600">One-on-One,</span><br />
                        Anywhere.
                    </h1>
                    <p class="mt-6 text-xl text-gray-700 max-w-lg">
                        Embark on a transformative journey of faith and
                        knowledge. Master Quranic recitation with expert
                        guidance and authentic Islamic teachings.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <div
                            class="bg-white shadow-lg rounded-xl px-6 py-4 flex items-center space-x-4 transform transition-transform duration-300 hover:scale-105"
                        >
                            <span class="text-2xl">🎯</span>
                            <div>
                                <p class="font-semibold">Tajweed Mastery</p>
                                <p class="text-sm text-gray-600">
                                    Perfect your pronunciation
                                </p>
                            </div>
                        </div>
                        <div
                            class="bg-white shadow-lg rounded-xl px-6 py-4 flex items-center space-x-4 transform transition-transform duration-300 hover:scale-105"
                        >
                            <span class="text-2xl">👨‍🏫</span>
                            <div>
                                <p class="font-semibold">Expert Teachers</p>
                                <p class="text-sm text-gray-600">
                                    Certified Islamic Scholars
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <Link
                            :href="route('register')"
                            class="inline-block bg-teal-600 text-white text-lg px-8 py-4 rounded-xl hover:bg-teal-700 shadow-xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl"
                        >
                            Start Learning
                        </Link>
                    </div>
                </div>
                <div class="lg:w-1/2 relative mt-10 lg:mt-0 flex justify-center overflow-hidden animate-fade-in">
                    <div class="relative">
                        <img
                            src="/images/1_student.png"
                            alt="Child learning Quran"
                            class="mx-auto w-full max-w-xs sm:max-w-md md:max-w-2xl h-auto object-cover rounded-xl border-8 border-white shadow-2xl transform transition-transform duration-500 hover:rotate-1"
                        />
                        <div
                            class="absolute -top-6 -right-6 bg-teal-400 rounded-full w-16 h-16 sm:w-24 sm:h-24 opacity-80 animate-pulse"
                        ></div>
                        <div
                            class="absolute -bottom-8 -left-8 bg-teal-500 rounded-full w-24 h-24 sm:w-32 sm:h-32 opacity-80 animate-pulse delay-300"
                        ></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quote Section -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
                <p class="text-2xl italic text-gray-700">
                    "Recite the quran , for on the day of resurrection it will
                    come as an intercessor for it reciters"
                </p>
                <p class="mt-4 text-lg text-gray-600">(Sahih Muslim)</p>
            </div>
        </section>

        <!-- Key Features -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <h2 class="text-4xl font-bold text-center text-teal-600 mb-12 animate-fade-in">
                    Key Features
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in-up">
                        <div
                            class="bg-gradient-to-r from-teal-100 to-cyan-100 rounded-full w-16 h-16 mx-auto flex items-center justify-center text-3xl mb-4"
                        >
                            📊
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">
                            Track your progress
                        </h3>
                        <p class="mt-4 text-gray-600">
                            Simple progress reports to help you grow
                            consistently.
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-teal-600 to-teal-700 text-white rounded-2xl shadow-lg p-8 text-center transform transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in-up delay-100"
                    >
                        <div
                            class="bg-teal-800 rounded-full w-16 h-16 mx-auto flex items-center justify-center text-3xl mb-4"
                        >
                            📖
                        </div>
                        <h3 class="text-2xl font-semibold">
                            Live Synchronized Quran Pages
                        </h3>
                        <p class="mt-4">
                            Everyone sees the exact same Qur'an page-live and
                            synced.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in-up delay-200">
                        <div
                            class="bg-gradient-to-r from-teal-100 to-cyan-100 rounded-full w-16 h-16 mx-auto flex items-center justify-center text-3xl mb-4"
                        >
                            💬
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">
                            Chat with student & Teacher
                        </h3>
                        <p class="mt-4 text-gray-600">
                            Stay connected with smooth in-class messaging.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Us -->
        <section class="py-20 bg-white">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col lg:flex-row items-center gap-12"
            >
                <div class="lg:w-1/2 h-96 w-full animate-fade-in-up">
                    <div class="h-full rounded-2xl overflow-hidden shadow-xl border-8 border-white">
                        <img
                            src="/images/student_img.png"
                            alt="Learning Quran"
                            class="w-full h-full object-cover transform transition-transform duration-500 hover:scale-105"
                        />
                    </div>
                </div>
                <div
                    class="lg:w-1/2 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-2xl p-10 h-auto flex flex-col justify-center w-full shadow-lg animate-fade-in-up delay-100"
                >
                    <h2 class="text-4xl font-bold text-teal-700 mb-6">
                        About Us
                    </h2>
                    <p class="text-lg text-gray-700 mb-4">
                        We connect students and teachers for one-on-one Qur'an
                        learning – anywhere in the world. Our goal is to make it
                        easy, interactive, and spiritually rewarding.
                    </p>
                    <p class="text-lg text-gray-700 mb-4">
                        With live Qur'an pages, real-time guidance, and progress
                        tracking, we bring the beauty of traditional learning
                        into a modern experience.
                    </p>
                    <p class="text-lg font-semibold text-gray-800">
                        Start your journey with us – one verse at a time.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gradient-to-r from-teal-700 to-teal-800 text-white py-16">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col md:flex-row justify-between items-start"
            >
                <div class="flex flex-col items-center md:items-start mb-10 md:mb-0 w-full md:w-auto">
                    <img
                        src="/images/app_logo.jpg"
                        alt="Logo"
                        class="h-20 w-40 md:h-24 md:w-48 bg-white rounded-xl object-cover max-w-full shadow-lg mb-4"
                    />
                    <p class="text-teal-200 text-center md:text-left mt-4 max-w-xs">
                        Connecting students and teachers for one-on-one Qur'an learning – anywhere in the world.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full mt-8 md:mt-0">
                    <div>
                        <p class="font-semibold text-lg mb-4 text-teal-200">Quick Links</p>
                        <ul class="space-y-2">
                            <li><Link :href="route('welcome')" class="hover:text-teal-200 transition-colors">Home</Link></li>
                            <li><Link :href="route('login')" class="hover:text-teal-200 transition-colors">About Us</Link></li>
                            <li><Link :href="route('login')" class="hover:text-teal-200 transition-colors">Contact</Link></li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-semibold text-lg mb-4 text-teal-200">Support</p>
                        <ul class="space-y-2">
                            <li class="text-teal-100">1on1quranclasses25@gmail.com</li>
                            <li class="text-teal-100 mt-4">Support Hours: Mon-Fri, 9AM-6PM EST</li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-semibold text-lg mb-4 text-teal-200">Contact Us</p>
                        <ul class="space-y-2">
                            <li class="text-teal-100">(678) 209-599, 470-783-3604</li>
                            <li class="text-teal-100 mt-4">1on1quranclasses250@gmail.com</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-teal-600 text-center text-teal-300">
                <p>&copy; {{ new Date().getFullYear() }} 1-on-1 Quran Classes. All rights reserved.</p>
            </div>
        </footer>
    </body>
</template>
