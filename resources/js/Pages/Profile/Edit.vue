<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const logout = () => {
    router.post(route("logout"));
};

// Check if current route is the prepare class route
const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};
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
                        <Link
                            :href="route('announcement')"
                            :class="{'bg-teal-600 text-white': isCurrentRoute('announcement'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('announcement')}"
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
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"
                                />
                            </svg>
                            Announcement
                        </Link>
                        <a href="#"
                            class="flex items-center gap-4 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Class history
                        </a>
                        <Link
                            :href="route('profile.edit')"
                            :class="{'bg-teal-600 text-white': isCurrentRoute('profile.edit'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('profile.edit')}"
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
                        </Link>
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

            <!-- Settings Form -->
            <section class="bg-white rounded-2xl shadow-sm p-10 max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold mb-10">Profile</h2>

                <!-- Profile Picture -->
                <div class="flex flex-col sm:flex-row items-center gap-8 mb-12">
                    <div class="relative">
                        <img src="images/app_logo.jpg" alt="Profile" class="w-48 h-48 rounded-full object-cover shadow-lg">
                        <button class="absolute bottom-2 right-2 bg-white text-teal-600 px-4 py-2 rounded-full shadow hover:bg-gray-100">Change</button>
                    </div>
                </div>

                <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        
                    />

                <!-- Change Password -->
                <UpdatePasswordForm  />

               

                <!-- Notification -->
                <h3 class="text-2xl font-bold mb-8">Notification</h3>
                <div class="flex items-center justify-between mb-12">
                    <p class="text-lg text-gray-700">Class reminders via mail</p>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-14 h-8 bg-gray-300 rounded-full peer peer-checked:bg-teal-600"></div>
                        <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition peer-checked:translate-x-6"></div>
                    </label>
                </div>

                <!-- Save Changes -->
                <div class="flex justify-center">
                    <button class="bg-teal-600 text-white px-20 py-5 rounded-full font-bold text-xl hover:bg-teal-700">Save changes</button>
                </div>
            </section>
        </main>
    </div>

    <!-- Mobile Layout -->
    <div class="lg:hidden p-4">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <img src="YOUR_LOGO_URL_HERE" alt="Logo" class="w-40 mx-auto mb-6">
            <h1 class="text-2xl font-bold text-center mb-8">Profile Settings</h1>

            <div class="flex flex-col items-center mb-10">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Profile" class="w-32 h-32 rounded-full object-cover mb-4">
                <button class="bg-teal-600 text-white px-6 py-2 rounded-full">Change</button>
            </div>

            <div class="space-y-6 mb-10">
                <input type="text" value="Aisha" placeholder="Name" class="w-full px-5 py-4 border border-gray-300 rounded-xl">
                <input type="email" value="Aisha2@gmail.com" placeholder="Email" class="w-full px-5 py-4 border border-gray-300 rounded-xl">
                <button class="w-full bg-teal-600 text-white py-4 rounded-full font-bold">Confirm</button>
            </div>

            <h2 class="text-xl font-bold mb-6">Change password</h2>
            <div class="space-y-6 mb-10">
                <input type="password" placeholder="Old password" class="w-full px-5 py-4 border border-gray-300 rounded-xl">
                <input type="password" placeholder="New password" class="w-full px-5 py-4 border border-gray-300 rounded-xl">
                <input type="password" placeholder="Confirm password" class="w-full px-5 py-4 border border-gray-300 rounded-xl">
                <button class="w-full bg-teal-600 text-white py-4 rounded-full font-bold">Confirm</button>
            </div>

            <div class="flex items-center justify-between mb-10">
                <p>Class reminders via mail</p>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-14 h-8 bg-gray-300 rounded-full peer peer-checked:bg-teal-600"></div>
                    <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition peer-checked:translate-x-6"></div>
                </label>
            </div>

            <button class="w-full bg-teal-600 text-white py-5 rounded-full font-bold text-xl">Save changes</button>
        </div>
    </div>

</body> 
    <!--<AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout> -->
</template>
