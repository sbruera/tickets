<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Bell } from 'lucide-vue-next';

interface Notification {
    id: string;
    data: {
        message: string;
        ticket_id?: number;
    };
    created_at: string;
}

const page = usePage();
const notifications = computed(() => (page.props.notifications as Notification[]) || []);
const open = ref(false);

function markAsRead(id: string) {
    router.post(`/notifications/${id}/read`, {}, { preserveScroll: true });
}

function markAllAsRead() {
    router.post('/notifications/read-all', {}, { preserveScroll: true });
    open.value = false;
}

function goToTicket(notification: Notification) {
    markAsRead(notification.id);
    if (notification.data.ticket_id) {
        router.visit(`/tickets/${notification.data.ticket_id}`);
    }
}
</script>

<template>
    <div class="relative">
        <button @click="open = !open" class="relative p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
            <Bell class="w-5 h-5" />
            <span v-if="notifications.length" class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                {{ notifications.length }}
            </span>
        </button>

        <div v-if="open" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-900 border rounded-lg shadow-lg z-50">
            <div class="flex justify-between items-center p-3 border-b">
                <span class="font-semibold">Notifications</span>
                <button v-if="notifications.length" @click="markAllAsRead" class="text-sm text-blue-600 hover:underline">
                    Mark all read
                </button>
            </div>
            
            <div class="max-h-64 overflow-y-auto">
                <div 
                    v-for="n in notifications" 
                    :key="n.id" 
                    @click="goToTicket(n)"
                    class="p-3 border-b hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer"
                >
                    <p class="text-sm">{{ n.data.message }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ new Date(n.created_at).toLocaleString() }}</p>
                </div>
                <p v-if="!notifications.length" class="p-3 text-gray-500 text-sm">No new notifications</p>
            </div>
        </div>
    </div>
</template>

