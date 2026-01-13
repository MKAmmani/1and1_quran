<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, reactive, watch, computed, onMounted, onBeforeUnmount } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object,
        default: () => ({})
    }
});

const page = usePage();
const photoPreview = ref(null);
const selectedPhoto = ref(null);
const localStatus = ref('');
const sidebarOpen = ref(false);

const isStudent = computed(() => page.props.auth.user.role === 'student');

const currentDate = computed(() => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date().toLocaleDateString('en-US', options);
});

const handleResize = () => {
    sidebarOpen.value = window.innerWidth >= 768;
};

onMounted(() => {
    sidebarOpen.value = window.innerWidth >= 768;
    window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});

const notificationSettings = reactive({
    class_reminders: true,
    announcements: true
});

const selectProfilePhoto = (event) => {
    const photo = event.target.files[0];

    if (!photo) {
        photoPreview.value = null;
        return;
    }

    selectedPhoto.value = photo;

    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(photo);
};

const updateProfilePhoto = () => {
    if (!selectedPhoto.value) return;

    const formData = new FormData();
    formData.append('photo', selectedPhoto.value);
    formData.append('_method', 'PUT'); // Laravel expects _method for PUT requests via form

    router.post(route('profile.update'), formData, {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            selectedPhoto.value = null;
            localStatus.value = 'Profile photo updated successfully.';
            const fileInput = document.querySelector('input[type="file"][name="photo"]');
            if (fileInput) fileInput.value = '';
        },
        onError: (errors) => {
            console.error('Error updating profile photo:', errors);
            localStatus.value = 'Failed to update profile photo.';
        }
    });
};

const updateNotificationSettings = () => {
    const settingsData = {
        notification_settings: {
            class_reminders: notificationSettings.class_reminders,
            announcements: notificationSettings.announcements
        }
    };

    router.patch(route('profile.update'), settingsData, {
        preserveScroll: true,
        onSuccess: () => {
            localStatus.value = 'Notification settings updated successfully.';
        },
        onError: (errors) => {
            console.error('Error updating notification settings:', errors);
            localStatus.value = 'Failed to update notification settings.';
        }
    });
};

// Initialize notification settings from user data
const currentUser = page.props.auth.user;
if (currentUser && currentUser.notification_settings) {
    notificationSettings.class_reminders = currentUser.notification_settings.class_reminders ?? true;
    notificationSettings.announcements = currentUser.notification_settings.announcements ?? true;
}

// Watch for changes in notification settings and update automatically
let debounceTimeout;
watch(notificationSettings, () => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        updateNotificationSettings();
    }, 1000); // Update after 1 second of no changes
}, { deep: true });
</script>

<template>
    <Head title="Profile" />

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
                <template v-if="!isStudent">
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('teacher.index')">
                        <span class="material-symbols-outlined">home</span>
                        <span class="font-medium">Home</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('prepre_class')">
                        <span class="material-symbols-outlined">edit_note</span>
                        <span class="font-medium">Prepare Class</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('teacher.students')">
                        <span class="material-symbols-outlined">group</span>
                        <span class="font-medium">Students</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('announcement')">
                        <span class="material-symbols-outlined">campaign</span>
                        <span class="font-medium">Announcement</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('live')">
                        <span class="material-symbols-outlined">videocam</span>
                        <span class="font-medium">Live class</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('quran.surahs')">
                        <span class="material-symbols-outlined">book_2</span>
                        <span class="font-medium">Quran library</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('class.history')">
                        <span class="material-symbols-outlined">history_edu</span>
                        <span class="font-medium">History</span>
                    </Link>
                </template>
                <template v-else>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('student.dashboard')">
                        <span class="material-symbols-outlined">home</span>
                        <span class="font-medium">Home</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" href="#">
                        <span class="material-symbols-outlined">videocam</span>
                        <span class="font-medium">Live class</span>
                    </Link>
                    <Link class="flex items-center gap-4 px-4 py-3 rounded-lg text-text-secondary-light dark:text-text-secondary-dark hover:bg-secondary dark:hover:bg-primary/10 hover:text-primary transition-all duration-200" :href="route('quran.surahs')">
                        <span class="material-symbols-outlined">book_2</span>
                        <span class="font-medium">Quran library</span>
                    </Link>
                </template>
                <Link class="flex items-center gap-4 px-4 py-3 rounded-lg bg-primary text-white shadow-md" :href="route('profile.edit')">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-medium">Settings</span>
                </Link>
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
                    <span class="text-text-primary-light dark:text-text-primary-dark">Profile</span>
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
                <div class="max-w-4xl mx-auto bg-surface-light dark:bg-surface-dark shadow sm:rounded-lg p-4 sm:p-6 md:p-8 border border-border-light dark:border-border-dark">
                    <h2 class="text-2xl font-bold mb-4 sm:mb-6">Profile Information</h2>

                    <!-- Status Message -->
                    <div v-if="status || localStatus" class="mb-4 font-medium text-sm text-green-600">
                        {{ status || localStatus }}
                    </div>

                    <!-- Profile Picture Section -->
                    <div class="flex flex-col items-center sm:flex-row gap-4 sm:gap-8 mb-6 sm:mb-8 pb-6 sm:pb-8 border-b">
                        <div class="relative">
                            <img
                                v-if="photoPreview"
                                :src="photoPreview"
                                alt="Profile Preview"
                                class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover shadow-lg"
                            >
                            <img
                                v-else-if="$page.props.auth.user.profile_photo_url"
                                :src="$page.props.auth.user.profile_photo_url"
                                alt="Profile"
                                class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover shadow-lg"
                            >
                            <div
                                v-else
                                class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-lg sm:text-xl"
                            >
                                {{ $page.props.auth.user.name ? $page.props.auth.user.name.charAt(0).toUpperCase() : '' }}
                            </div>
                            
                            <label class="absolute bottom-0 right-0 bg-white text-teal-600 p-2 rounded-full shadow hover:bg-gray-100 cursor-pointer">
                                <input
                                    type="file"
                                    name="photo"
                                    accept="image/*"
                                    class="hidden"
                                    @change="selectProfilePhoto"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                        </div>

                        <div class="flex-1 text-center sm:text-left mt-2 sm:mt-0">
                            <h3 class="text-lg sm:text-xl font-semibold">{{ $page.props.auth.user.name }}</h3>
                            <p class="text-sm sm:text-base text-gray-600">{{ $page.props.auth.user.email }}</p>

                            <div v-if="selectedPhoto" class="mt-3">
                                <button @click="updateProfilePhoto" class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm hover:bg-teal-700">
                                    Save Photo
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Update Profile Information Form -->
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="mb-6 sm:mb-8"
                    />

                    <!-- Update Password Form -->
                    <div class="border-t pt-6 sm:pt-8 mb-6 sm:mb-8">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6">Update Password</h3>
                        <UpdatePasswordForm class="max-w-full sm:max-w-xl" />
                    </div>

                    <!-- Notification Settings -->
                    <div class="border-t pt-6 sm:pt-8 mb-6 sm:mb-8">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6">Notification Settings</h3>
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-0 mb-3 sm:mb-4">
                            <p class="text-sm sm:text-base text-gray-700 mb-1 sm:mb-0">Class reminders via email</p>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    v-model="notificationSettings.class_reminders"
                                    class="sr-only peer"
                                >
                                <div class="w-9 h-5 bg-gray-300 rounded-full peer peer-checked:bg-teal-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-0">
                            <p class="text-sm sm:text-base text-gray-700 mb-1 sm:mb-0">Announcements</p>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    v-model="notificationSettings.announcements"
                                    class="sr-only peer"
                                >
                                <div class="w-9 h-5 bg-gray-300 rounded-full peer peer-checked:bg-teal-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="border-t pt-6 sm:pt-8">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6">Delete Account</h3>
                        <DeleteUserForm class="max-w-full sm:max-w-xl" />
                    </div>
                </div>
            </div>
        </main>
    </body>
</template>
