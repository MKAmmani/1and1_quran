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
                        <h1 class="text-2xl font-semibold">
                            Assalaikum Alaykum, Ustadha
                            {{
                                $page.props.auth.user?.first_name?.split(
                                    " "
                                )[0] || "Teacher"
                            }}
                        </h1>
                        <p class="text-gray-500">
                            {{
                                new Date().toLocaleDateString("en-US", {
                                    weekday: "long",
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                })
                            }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <img
                            src="/images/student_2.PNG"
                            alt="Profile Picture"
                            class="w-12 h-12 bg-gray-200 rounded-full border-2 border-dashed object-cover"
                        />
                        <div class="text-left">
                            <p class="font-medium">
                                {{ $page.props.auth.user.first_name }}
                                {{ $page.props.auth.user.last_name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </p>
                        </div>
                    </div>
                </header>

                <!-- Today's Session -->
                <section class="bg-white rounded-2xl shadow-sm p-8 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Today's Session</h2>
                        <button
                            @click="startLiveSession"
                            class="bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition"
                        >
                            Start class
                        </button>
                    </div>

                    <h3 class="text-xl font-semibold mb-6">My students</h3>

                    <div class="space-y-4">
                        <!-- Student Row 1 -->
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <div class="col-span-3">
                                <p class="font-medium text-lg">Salmah</p>
                                <p class="text-sm text-gray-500">
                                    13 of 114 surahs
                                </p>
                            </div>
                            <div class="col-span-6">
                                <div
                                    class="w-full bg-gray-200 rounded-full h-3"
                                >
                                    <div
                                        class="bg-teal-600 h-3 rounded-full"
                                        style="width: 11%"
                                    ></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    11% completed
                                </p>
                            </div>
                            <div class="col-span-2 flex justify-end">
                                <span
                                    class="flex items-center gap-2 text-green-600"
                                    ><span
                                        class="w-3 h-3 bg-green-600 rounded-full"
                                    ></span>
                                    Online</span
                                >
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <button
                                    class="text-gray-400 text-xl leading-none"
                                >
                                    ⋮
                                </button>
                            </div>
                        </div>

                        <!-- Student Row 2 -->
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <div class="col-span-3">
                                <p class="font-medium text-lg">Ali</p>
                                <p class="text-sm text-gray-500">
                                    33 of 114 surahs
                                </p>
                            </div>
                            <div class="col-span-6">
                                <div
                                    class="w-full bg-gray-200 rounded-full h-3"
                                >
                                    <div
                                        class="bg-teal-600 h-3 rounded-full"
                                        style="width: 21%"
                                    ></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    21% completed
                                </p>
                            </div>
                            <div class="col-span-2 flex justify-end">
                                <span
                                    class="flex items-center gap-2 text-green-600"
                                    ><span
                                        class="w-3 h-3 bg-green-600 rounded-full"
                                    ></span>
                                    Online</span
                                >
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <button
                                    class="text-gray-400 text-xl leading-none"
                                >
                                    ⋮
                                </button>
                            </div>
                        </div>

                        <!-- Student Row 3 -->
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <div class="col-span-3">
                                <p class="font-medium text-lg">Rashad</p>
                                <p class="text-sm text-gray-500">
                                    16 of 114 surahs
                                </p>
                            </div>
                            <div class="col-span-6">
                                <div
                                    class="w-full bg-gray-200 rounded-full h-3"
                                >
                                    <div
                                        class="bg-teal-600 h-3 rounded-full"
                                        style="width: 16%"
                                    ></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    16% completed
                                </p>
                            </div>
                            <div class="col-span-2 flex justify-end">
                                <span
                                    class="flex items-center gap-2 text-red-600"
                                    ><span
                                        class="w-3 h-3 bg-red-600 rounded-full"
                                    ></span>
                                    Offline</span
                                >
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <button
                                    class="text-gray-400 text-xl leading-none"
                                >
                                    ⋮
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Quick Stats -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold mb-6">Quick stats</h2>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="bg-white rounded-xl p-6 shadow-sm border">
                            <p class="text-4xl font-bold text-teal-600">11</p>
                            <p class="text-gray-600">Total students</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-sm border">
                            <p class="text-4xl font-bold text-teal-600">4</p>
                            <p class="text-gray-600">Classes this week</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-sm border">
                            <p class="text-4xl font-bold text-teal-600">2</p>
                            <p class="text-gray-600">This week</p>
                        </div>
                    </div>
                </section>

                <!-- Recent Activities & Upcoming Classes -->
                <div class="grid grid-cols-2 gap-8">
                    <section class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold mb-6">
                            Recent Activities
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-3 h-3 bg-teal-600 rounded-full"
                                ></span>
                                <p>Class completed with khadijah</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-3 h-3 bg-teal-600 rounded-full"
                                ></span>
                                <p>Class completed with Rashad and uthman</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-3 h-3 bg-teal-600 rounded-full"
                                ></span>
                                <p>
                                    Ismail joined joined class Suratul waqiah.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold mb-6">
                            Upcoming Classes
                        </h2>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium">
                                        Suratul yasin with new st..
                                    </p>
                                    <p class="text-sm text-gray-500">5:30pm</p>
                                </div>
                                <button
                                    class="bg-teal-600 text-white px-4 py-2 rounded-full text-sm hover:bg-teal-700"
                                >
                                    Start
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium">
                                        suratul fatiha with khadijah
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        12-12-2025
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium">
                                        suratul fatiha with khadijah
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        12-12-2025
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>

        <!-- Mobile Layout -->
        <div class="lg:hidden flex flex-col min-h-screen">
            <!-- Mobile Header with Hamburger on left -->
            <div
                class="bg-white shadow-md px-4 py-3 flex items-center"
            >
                <button
                    id="menuToggle"
                    class="text-gray-700 focus:outline-none mr-4"
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
                <img
                    src="/images/app_logo.jpg"
                    alt="1-on-1 Quran Classes Logo"
                    class="w-24"
                />
            </div>

            <!-- Sidebar (Hidden by default) - Slide in from left -->
            <aside
                id="mobileNav"
                class="hidden fixed top-0 left-0 h-full w-64 bg-white shadow-lg flex-col z-50 transform -translate-x-full transition-transform duration-300 ease-in-out"
            >
                <!-- Navigation -->
                <nav class="px-4 pt-2 pb-4 flex flex-col">
                    <div class="space-y-2">
                        <Link
                            :href="route('teacher.index')"
                            :class="{'bg-teal-600 text-white': isCurrentRoute('teacher.index'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('teacher.index')}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm"
                            @click="closeMobileMenu"
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
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                />
                            </svg>
                            Home
                        </Link>
                        <Link
                            :href="route('prepre_class')"
                            :class="{'bg-teal-600 text-white': isCurrentRoute('prepre_class'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('prepre_class')}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm"
                            @click="closeMobileMenu"
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
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                            Prepare
                        </Link>
                        <a
                            href="#"
                            class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm"
                            @click="closeMobileMenu"
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            Students
                        </a>
                        <a
                            href="#"
                            class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm"
                            @click="closeMobileMenu"
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
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                                />
                            </svg>
                            Live class
                        </a>
                        <a
                            href="#"
                            class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm"
                            @click="closeMobileMenu"
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
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            Quran Pages
                        </a>
                        <Link
                            :href="route('announcement')"
                            :class="{'bg-teal-600 text-white': isCurrentRoute('announcement'), 'text-gray-700 hover:bg-gray-100': !isCurrentRoute('announcement')}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm"
                            @click="closeMobileMenu"
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
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"
                                />
                            </svg>
                            Announcement
                        </Link>
                        <a
                            href="#"
                            class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm"
                            @click="closeMobileMenu"
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
                        <button
                            @click="logout"
                            class="flex items-center gap-3 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 text-sm w-full text-left"
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
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                />
                            </svg>
                            Logout
                        </button>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-4">
                <!-- Header -->
                <header class="flex flex-col gap-4 mb-6">
                    <div>
                        <h1 class="text-lg font-semibold">
                            Assalaikum Alaykum, Ustadha
                            {{
                                $page.props.auth.user?.first_name?.split(
                                    " "
                                )[0] || "Teacher"
                            }}
                        </h1>
                        <p class="text-gray-500 text-sm">
                            {{
                                new Date().toLocaleDateString("en-US", {
                                    weekday: "long",
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                })
                            }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <img
                            src="/images/student_2.PNG"
                            alt="Profile Picture"
                            class="w-10 h-10 bg-gray-200 rounded-full border-2 border-dashed object-cover"
                        />
                        <div class="text-left">
                            <p class="font-medium text-sm">
                                {{ $page.props.auth.user.first_name }}
                                {{ $page.props.auth.user.last_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </p>
                        </div>
                    </div>
                </header>

                <!-- Today's Session -->
                <section class="bg-white rounded-2xl shadow-sm p-4 mb-6">
                    <div class="flex flex-col gap-4 mb-4">
                        <h2 class="text-lg font-bold">Today's Session</h2>
                        <button
                            @click="startLiveSession"
                            class="bg-teal-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-teal-700 transition text-sm"
                        >
                            Start class
                        </button>
                    </div>

                    <h3 class="text-base font-semibold mb-4">My students</h3>

                    <div class="space-y-4">
                        <!-- Student Row 1 -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-sm">Salmah</p>
                                    <p class="text-xs text-gray-500">
                                        13 of 114 surahs
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="flex items-center gap-1 text-green-600 text-xs"
                                        ><span
                                            class="w-2 h-2 bg-green-600 rounded-full"
                                        ></span>
                                        Online</span
                                    >
                                    <button
                                        class="text-gray-400 text-lg leading-none"
                                    >
                                        ⋮
                                    </button>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-teal-600 h-2 rounded-full"
                                    style="width: 11%"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-600">11% completed</p>
                        </div>

                        <!-- Student Row 2 -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-sm">Ali</p>
                                    <p class="text-xs text-gray-500">
                                        33 of 114 surahs
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="flex items-center gap-1 text-green-600 text-xs"
                                        ><span
                                            class="w-2 h-2 bg-green-600 rounded-full"
                                        ></span>
                                        Online</span
                                    >
                                    <button
                                        class="text-gray-400 text-lg leading-none"
                                    >
                                        ⋮
                                    </button>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-teal-600 h-2 rounded-full"
                                    style="width: 21%"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-600">21% completed</p>
                        </div>

                        <!-- Student Row 3 -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-sm">Rashad</p>
                                    <p class="text-xs text-gray-500">
                                        16 of 114 surahs
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="flex items-center gap-1 text-red-600 text-xs"
                                        ><span
                                            class="w-2 h-2 bg-red-600 rounded-full"
                                        ></span>
                                        Offline</span
                                    >
                                    <button
                                        class="text-gray-400 text-lg leading-none"
                                    >
                                        ⋮
                                    </button>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-teal-600 h-2 rounded-full"
                                    style="width: 16%"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-600">16% completed</p>
                        </div>
                    </div>
                </section>

                <!-- Quick Stats -->
                <section class="mb-6">
                    <h2 class="text-lg font-bold mb-4">Quick stats</h2>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white rounded-xl p-4 shadow-sm border">
                            <p class="text-2xl font-bold text-teal-600">11</p>
                            <p class="text-gray-600 text-xs">Total students</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border">
                            <p class="text-2xl font-bold text-teal-600">4</p>
                            <p class="text-gray-600 text-xs">
                                Classes this week
                            </p>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border">
                            <p class="text-2xl font-bold text-teal-600">2</p>
                            <p class="text-gray-600 text-xs">This week</p>
                        </div>
                    </div>
                </section>

                <!-- Recent Activities & Upcoming Classes -->
                <div class="space-y-6">
                    <section class="bg-white rounded-2xl shadow-sm p-4">
                        <h2 class="text-lg font-bold mb-4">
                            Recent Activities
                        </h2>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-2 h-2 bg-teal-600 rounded-full flex-shrink-0"
                                ></span>
                                <p class="text-sm">
                                    Class completed with khadijah
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-2 h-2 bg-teal-600 rounded-full flex-shrink-0"
                                ></span>
                                <p class="text-sm">
                                    Class completed with Rashad and uthman
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-2 h-2 bg-teal-600 rounded-full flex-shrink-0"
                                ></span>
                                <p class="text-sm">
                                    Ismail joined joined class Suratul waqiah.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white rounded-2xl shadow-sm p-4">
                        <h2 class="text-lg font-bold mb-4">Upcoming Classes</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-sm">
                                        Suratul yasin with new st..
                                    </p>
                                    <p class="text-xs text-gray-500">5:30pm</p>
                                </div>
                                <button
                                    class="bg-teal-600 text-white px-3 py-1.5 rounded-full text-xs hover:bg-teal-700"
                                >
                                    Start
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-sm">
                                        suratul fatiha with khadijah
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        12-12-2025
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-sm">
                                        suratul fatiha with khadijah
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        12-12-2025
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </body>
</template>

<script setup>
import { onMounted } from "vue";
import { router, Link } from "@inertiajs/vue3";
import axios from 'axios';

const logout = () => {
    router.post(route("logout"));
};

// Function to start a live session
const startLiveSession = async () => {
    try {
        // Use the new route to start or get a session
        const response = await axios.post(route('live-class.start'));

        if (response.data && response.data.success) {
            // Redirect to the live class page
            router.visit(route('live'));
        } else {
            console.error('Failed to start live session:', response.data.message);
            alert('Failed to start live session. Please try again.');
        }
    } catch (error) {
        console.error('Error starting live session:', error);
        alert('Failed to start live session. Please try again.');
    }
};

// Check if current route is the prepare class route
const isCurrentRoute = (routeName) => {
    return route().current(routeName);
};

// Close mobile menu function
const closeMobileMenu = () => {
    const mobileNav = document.getElementById("mobileNav");
    if (mobileNav && !mobileNav.classList.contains("-translate-x-full") && !mobileNav.classList.contains("hidden")) {
        mobileNav.classList.add("-translate-x-full");
        // Hide after animation completes
        setTimeout(() => {
            mobileNav.classList.add("hidden");
        }, 300);
    }
};

onMounted(() => {
    // Mobile menu toggle
    const menuToggle = document.getElementById("menuToggle");
    const mobileNav = document.getElementById("mobileNav");

    menuToggle.addEventListener("click", function () {
        mobileNav.classList.remove("hidden");
        // Trigger the slide-in animation
        setTimeout(() => {
            mobileNav.classList.remove("-translate-x-full");
        }, 10);
    });

    // Close menu when clicking a link
    const navLinks = mobileNav.querySelectorAll("a");
    navLinks.forEach((link) => {
        link.addEventListener("click", function () {
            closeMobileMenu();
        });
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (event) {
        const isClickInside =
            mobileNav.contains(event.target) ||
            menuToggle.contains(event.target);
        if (!isClickInside && !mobileNav.classList.contains("-translate-x-full") && !mobileNav.classList.contains("hidden")) {
            closeMobileMenu();
        }
    });
});
</script>
