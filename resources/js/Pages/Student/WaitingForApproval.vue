<script setup>
import { onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const page = usePage();
const studentId = page.props.auth.user.id;

const props = defineProps({
    liveSession: Object,
    joinRequest: Object,
});

let pollingInterval = null;

onMounted(() => {
    console.log('WaitingForApproval page mounted.');
    const studentId = page.props.auth.user.id;
    console.log('Student ID for channel:', studentId);
    console.log('Auth user data:', page.props.auth.user);
    console.log('Join request data:', props.joinRequest);

    // Setup real-time listener if Echo is available
    if (window.Echo) {
        console.log('Laravel Echo is available. Attempting to connect...');

        // Debug: Check if Echo is properly configured
        console.log('Echo object:', window.Echo);
        console.log('Echo connector:', window.Echo.connector);

        const channel = window.Echo.private(`student.${studentId}`);

        console.log('Attempting to subscribe to channel:', `student.${studentId}`);

        channel.subscribed(() => {
            console.log('Successfully subscribed to the private student channel!');
        });

        channel.listen('.join.request.approved', (e) => {
            console.log('JoinRequestApproved event received:', e);
            console.log('Event payload details:', {
                hasJoinRequest: !!e.joinRequest,
                hasLiveSessionId: e.joinRequest && e.joinRequest.live_session_id,
                liveSessionId: e.joinRequest ? e.joinRequest.live_session_id : undefined,
                fullPayload: e
            });

            if (e.joinRequest && e.joinRequest.live_session_id) {
                const liveSessionId = e.joinRequest.live_session_id;
                console.log('Redirecting to live session via real-time event:', liveSessionId);

                // Clear the polling interval if it exists
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }

                // Debug: Log the route we're trying to navigate to
                const routeUrl = route('live-session.join', { liveSession: liveSessionId });
                console.log('Generated route URL:', routeUrl);

                router.get(routeUrl);
            } else {
                console.error('Event payload is missing required data:', e);
                console.error('Expected properties:', {
                    hasJoinRequest: !!e.joinRequest,
                    hasLiveSessionId: e.joinRequest && e.joinRequest.live_session_id
                });
            }
        });

        channel.error((error) => {
            console.error('Laravel Echo channel error:', error);
            if (error.status === 403) {
                console.error('Channel authorization failed. Check routes/channels.php and your authentication.');
            }
        });

        console.log('Event listener for ".join.request.approved" is set up.');

    } else {
        console.warn('Laravel Echo is not available. Using polling as fallback.');
        console.log('Available global objects:', {
            Echo: typeof window.Echo,
            Pusher: typeof window.Pusher
        });
    }

    // Start polling as a fallback mechanism
    startPolling();
});

// Polling function to check approval status
const startPolling = () => {
    console.log('Starting polling to check approval status...');

    // Check immediately
    checkApprovalStatus();

    // Then poll every 3 seconds
    pollingInterval = setInterval(checkApprovalStatus, 3000);
};

const checkApprovalStatus = async () => {
    try {
        console.log('Checking join request status via polling...');

        const response = await axios.get(`/join-requests/${props.joinRequest.id}/status`);
        const { approved, live_session_id } = response.data;

        console.log('Polling response:', response.data);

        if (approved) {
            console.log('Join request approved via polling! Redirecting to session:', live_session_id);

            // Clear the polling interval
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }

            // Navigate to the join page
            router.get(route('live-session.join', { liveSession: live_session_id }));
        }
    } catch (error) {
        console.error('Error checking join request status:', error);

        // Stop polling if there's an authentication error
        if (error.response && error.response.status === 403) {
            console.error('Authentication error, stopping polling');
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }
    }
};

onUnmounted(() => {
    // Clean up polling when component is unmounted
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
});
</script>

<template>
    <Head title="Waiting for Approval" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Waiting Room</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 mb-4">Waiting for Approval</h1>
                        <p class="text-gray-600">Your request to join the session has been sent. Please wait for the teacher to approve it.</p>
                        <div class="mt-6">
                            <svg class="animate-spin h-8 w-8 text-teal-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
