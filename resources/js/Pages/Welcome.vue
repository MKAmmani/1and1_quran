<script setup>
import { Link } from "@inertiajs/vue3";
import { onMounted } from 'vue';

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
        <header class="bg-white shadow-sm hidden lg:block">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center"
            >
                <div class="flex items-center">
                    <img
                        src="/images/app_logo.jpg"
                        alt="1-on-1 Quran Classes Logo"
                        class="h-16 md:h-20 w-auto md:w-48 object-contain rounded-md"
                    />
                    <nav class="ml-10 space-x-8">
                        <Link
                            :href="route('welcome')"
                            class="text-gray-600 hover:text-teal-600"
                            >Home</Link
                        >
                        <Link
                            :href="route('login')"
                            class="text-gray-600 hover:text-teal-600"
                            >About us</Link
                        >
                        <Link
                            :href="route('login')"
                            class="text-gray-600 hover:text-teal-600"
                            >Contact</Link
                        >
                    </nav>
                </div>
                <div class="space-x-4">
                    <Link
                        :href="route('login')"
                        class="border border-teal-600 text-teal-600 px-4 py-2 rounded-md hover:bg-teal-50"
                    >
                        Log In
                    </Link>
                    <Link
                        :href="route('register')"
                        class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700"
                    >
                        Sign up for free
                    </Link>
                </div>
            </div>
        </header>

        <!-- Mobile padding for fixed header -->
        <div class="h-16 lg:hidden w-full"></div>

        <!-- Hero Section -->
        <section class="bg-gradient-to-b from-gray-50 to-teal-50 py-20">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col lg:flex-row items-center"
            >
                <div class="lg:w-1/2">
                    <h1 class="text-5xl font-bold text-gray-900 leading-tight">
                        Learn the Holy Qur'an.<br />
                        One-on-One,<br />
                        Anywhere.
                    </h1>
                    <p class="mt-6 text-xl text-gray-700">
                        Embark on a transformative journey of faith and
                        knowledge. Master Quranic recitation with expert
                        guidance and authentic Islamic teachings.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <div
                            class="bg-white shadow-md rounded-lg px-6 py-4 flex items-center space-x-4"
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
                            class="bg-white shadow-md rounded-lg px-6 py-4 flex items-center space-x-4"
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
                    <button
                        class="mt-10 bg-teal-600 text-white text-lg px-8 py-4 rounded-md hover:bg-teal-700 shadow-lg"
                    >
                        Start Learning
                    </button>
                </div>
                <div class="lg:w-1/2 relative mt-10 lg:mt-0 flex justify-center overflow-hidden">
                    <img
                        src="/images/1_student.png"
                        alt="Child learning Quran"
                        class="mx-auto w-full max-w-xs sm:max-w-md md:max-w-2xl h-auto object-cover rounded-none border border-gray-100"
                    />
                    <div
                        class="absolute top-10 right-10 bg-teal-400 rounded-full w-16 h-16 sm:w-32 sm:h-32"
                    ></div>
                    <div
                        class="absolute bottom-0 left-20 bg-teal-500 rounded-full w-24 h-24 sm:w-48 sm:h-48"
                    ></div>
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
                <h2 class="text-4xl font-bold text-center text-teal-600 mb-12">
                    Key Features
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl shadow-md p-8 text-center">
                        <div
                            class="bg-teal-100 rounded-full w-16 h-16 mx-auto flex items-center justify-center text-3xl mb-4"
                        >
                            📊
                        </div>
                        <h3 class="text-xl font-semibold">
                            Track your progress
                        </h3>
                        <p class="mt-4 text-gray-600">
                            Simple progress reports to help you grow
                            consistently.
                        </p>
                    </div>
                    <div
                        class="bg-teal-600 text-white rounded-2xl shadow-md p-8 text-center"
                    >
                        <div
                            class="bg-teal-700 rounded-full w-16 h-16 mx-auto flex items-center justify-center text-3xl mb-4"
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
                    <div class="bg-white rounded-2xl shadow-md p-8 text-center">
                        <div
                            class="bg-teal-100 rounded-full w-16 h-16 mx-auto flex items-center justify-center text-3xl mb-4"
                        >
                            💬
                        </div>
                        <h3 class="text-xl font-semibold">
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
        <section class="py-20 bg-gray-50">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col lg:flex-row items-center gap-0"
            >
                <div class="lg:w-1/2 h-96 w-full">
                    <div class="h-full rounded-2xl overflow-hidden shadow-xl">
                        <img
                            src="/images/student_img.png"
                            alt="Learning Quran"
                            class="w-full h-full object-cover"
                        />
                    </div>
                </div>
                <div
                    class="lg:w-1/2 bg-teal-50 rounded-2xl p-10 h-96 flex flex-col justify-center w-full"
                >
                    <h2 class="text-4xl font-bold text-teal-600 mb-6">
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
        <footer class="bg-teal-700 text-white py-12">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col md:flex-row justify-between items-center"
            >
                <div class="flex items-center mb-6 md:mb-0 w-full">
                    <img
                        src="/images/app_logo.jpg"
                        alt="Logo"
                        class="h-20 w-40 md:h-24 md:w-48 bg-white rounded-lg object-cover max-w-full"
                    />
                </div>
                <div class="text-center md:text-left w-full mt-4 md:mt-0">
                    <p class="font-semibold mb-2">Support and help team</p>
                    <p>1on1quranclasses25@gmail.com</p>
                    <div class="mt-4">
                        <p class="font-semibold">Quick Links</p>
                        <p>Home</p>
                        <p>About</p>
                    </div>
                </div>
                <div class="text-center md:text-right mt-4 md:mt-0 w-full">
                    <p class="font-semibold mb-2">Contact Us</p>
                    <p>Phone</p>
                    <p>(678) 209-599, 470-783-3604</p>
                    <p class="mt-4">Email</p>
                    <p>1on1quranclasses250@gmail.com</p>
                </div>
            </div>
        </footer>
    </body>
</template>
