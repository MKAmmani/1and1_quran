<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const roles = [
    { value: "student", name: "Student" },
    { value: "teacher", name: "Teacher" },
];

const genders = [
    { value: "male", name: "Male" },
    { value: "female", name: "Female" },
];

const form = useForm({
    first_name: "",
    last_name: "",
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    gender: "",
    role: "",
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const toggleConfirmPasswordVisibility = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};

const submit = () => {
    form.name = form.first_name + " " + form.last_name;
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <body
        class="bg-white min-h-screen flex items-center justify-center px-4 py-12"
    >
        <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-40 items-center">
            <!-- Left: Animated Illustration -->
            <div class="relative flex justify-end lg:justify-end items-end">
                <img
                    src="/images/amico.png"
                    alt="Online Quran teacher teaching student via laptop"
                    class="relative z-10 max-w-full h-auto -mb-40"
                />
            </div>

            <!-- Right: Registration Form -->
            <div class="max-w-md w-full mx-auto lg:mx-0">
                <!-- Logo (closest matching logo with Quran book and crescent moon) -->
                <div class="text-right mb-1">
                    <img
                        src="/images/app_logo.jpg"
                        alt="1-on-1 Quran Classes Logo"
                        class="mx-auto w-48 max-w-full h-auto"
                    />
                </div>

                <!-- Registration Card -->
                <div
                    class="bg-white rounded-2xl border border-gray-200 p-6 mt-0 w-full"
                >
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">
                        Create your account
                    </h2>
                    <p class="text-gray-600 mb-6 text-sm">
                        Sign up to get started with our platform
                    </p>

                    <!-- Google Registration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                        <Link
                            :href="route('google.redirect', { role: 'student' })"
                            class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-xl py-3 px-4 hover:bg-gray-50 transition font-medium"
                        >
                            <img
                                src="https://developers.google.com/identity/images/g-logo.png"
                                alt="Google"
                                class="w-5 h-5"
                            />
                            <span class="text-sm">As a Student</span>
                        </Link>
                        <Link
                            :href="route('google.redirect', { role: 'teacher' })"
                            class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-xl py-3 px-4 hover:bg-gray-50 transition font-medium"
                        >
                            <img
                                src="https://developers.google.com/identity/images/g-logo.png"
                                alt="Google"
                                class="w-5 h-5"
                            />
                            <span class="text-sm">As a Teacher</span>
                        </Link>
                    </div>

                    <!-- OR Separator -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span
                                class="px-4 bg-white text-gray-500 font-semibold text-xs"
                                >OR</span
                            >
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit">
                        <!-- Name Fields -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-900 mb-1"
                                    >First Name</label
                                >
                                <input
                                    type="text"
                                    placeholder="First name"
                                    v-model="form.first_name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 transition text-sm"
                                />
                                <InputError :message="form.errors.first_name" />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-900 mb-1"
                                    >Last Name</label
                                >
                                <input
                                    type="text"
                                    placeholder="Last name"
                                    v-model="form.last_name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 transition text-sm"
                                />
                                <InputError :message="form.errors.last_name" />
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label
                                class="block text-xs font-semibold text-gray-900 mb-1"
                                >Email</label
                            >
                            <input
                                type="email"
                                placeholder="yourname@gmail.com"
                                v-model="form.email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 transition text-sm"
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label
                                class="block text-xs font-semibold text-gray-900 mb-1"
                                >Password</label
                            >
                            <div class="relative">
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    placeholder="Enter your password"
                                    v-model="form.password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 pr-12 transition text-sm"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    title="Toggle password visibility"
                                    @click="togglePasswordVisibility"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        v-if="!showPassword"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        v-else
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <InputError :message="form.errors.password" />
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label
                                class="block text-xs font-semibold text-gray-900 mb-1"
                                >Confirm Password</label
                            >
                            <div class="relative">
                                <input
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    placeholder="Confirm your password"
                                    v-model="form.password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 pr-12 transition text-sm"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    title="Toggle password visibility"
                                    @click="toggleConfirmPasswordVisibility"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        v-if="!showConfirmPassword"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        v-else
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <InputError
                                :message="form.errors.password_confirmation"
                            />
                        </div>
                        <!-- Gender Selection -->
                        <div class="mb-4">
                            <label
                                class="block text-xs font-semibold text-gray-900 mb-2"
                                >Gender</label
                            >
                            <div class="flex gap-6">
                                <label
                                    v-for="gender in genders"
                                    :key="gender.value"
                                    class="flex items-center gap-2 cursor-pointer"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.gender"
                                        :value="gender.value"
                                        class="w-4 h-4 text-teal-600 focus:ring-teal-500"
                                    />
                                    <span class="text-sm text-gray-700">{{
                                        gender.name
                                    }}</span>
                                </label>
                            </div>
                            <InputError :message="form.errors.gender" />
                        </div>

                        <!-- Account Type Selection -->
                        <div class="mb-6">
                            <label
                                class="block text-xs font-semibold text-gray-900 mb-2"
                                >Account type</label
                            >
                            <div class="flex gap-6">
                                <label
                                    v-for="role in roles"
                                    :key="role.value"
                                    class="flex items-center gap-2 cursor-pointer"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.role"
                                        :value="role.value"
                                        class="w-4 h-4 text-teal-600 focus:ring-teal-500"
                                    />
                                    <span class="text-sm text-gray-700">{{
                                        role.name
                                    }}</span>
                                </label>
                            </div>
                            <InputError :message="form.errors.role" />
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-teal-600 text-white font-bold py-3 rounded-xl hover:bg-teal-700 transition shadow-lg text-sm"
                        >
                            Sign up
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-600">
                        Already have an account?
                        <Link
                            :href="route('login')"
                            class="font-semibold leading-6 text-teal-600 hover:text-teal-500"
                        >
                            Log in
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </body>
    <!--<GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div>
                <InputLabel for="phone_number" value="phone_number" />

                <TextInput
                    id="phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                    required
                    autofocus
                    autocomplete="phone_number"
                />

                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>

            <div>
                <InputLabel for="role" value="role" />
                <select v-model="form.role" class="mt-1 block w-full">
                    <option value="">Select User role</option>
                    <option
                        v-for="role in roles"
                        :key="role.value"
                        :value="role.value"
                    >
                        {{ role.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout> -->
</template>
