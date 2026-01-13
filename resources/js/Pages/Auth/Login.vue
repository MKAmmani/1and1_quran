<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
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

            <!-- Right: Login Form -->
            <div class="max-w-md w-full mx-auto lg:mx-0">
                <!-- Logo (closest matching logo with Quran book and crescent moon) -->
                <div class="text-right mb-1">
                    <img
                        src="/images/app_logo.jpg"
                        alt="1-on-1 Quran Classes Logo"
                        class="mx-auto w-48 max-w-full h-auto"
                    />
                </div>

                <!-- Login Card -->
                <div
                    class="bg-white rounded-2xl border border-gray-200 p-6 mt-0 w-full"
                >
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">
                        Log in with your email
                    </h2>
                    <p class="text-gray-600 mb-6 text-sm">
                        Use your email to log in to your account
                    </p>

                    <!-- Google Login -->
                    <button
                        type="button"
                        class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-xl py-3 px-6 hover:bg-gray-50 transition mb-6 font-medium"
                    >
                        <img
                            src="https://developers.google.com/identity/images/g-logo.png"
                            alt="Google"
                            class="w-5 h-5"
                        />
                        <span class="text-sm">Log in with Google</span>
                    </button>

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
                        <div class="mb-4">
                            <label
                                class="block text-xs font-semibold text-gray-900 mb-1"
                                >Email</label
                            >
                            <input
                                type="email"
                                v-model="form.email"
                                placeholder="yourname@gmail.com"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 transition text-sm"
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between mb-1">
                                <label
                                    class="text-xs font-semibold text-gray-900"
                                    >Password</label
                                >
                                <Link
                                    :href="route('password.request')"
                                    class="text-xs font-medium text-teal-600 hover:text-teal-700"
                                    >Forgot password?</Link
                                >
                                
                            </div>
                            <div class="relative">
                                <input
                                    type="password"
                                    v-model="form.password"
                                    placeholder="Enter your password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 pr-12 transition text-sm"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    title="Toggle password visibility"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
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
                                </button>
                            </div>
                            <InputError :message="form.errors.password" />
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-teal-600 text-white font-bold py-3 rounded-xl hover:bg-teal-700 transition shadow-lg text-sm"
                        >
                            Log in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>

    <!--<Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
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
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form> -->
</template>
