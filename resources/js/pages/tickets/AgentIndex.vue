<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

interface Ticket {
    id: number;
    name: string;
    status: string;
    assigned_agent_id: number | null;
    created_at: string;
}

defineProps<{
    tickets: Ticket[];
}>();

function assignToMe(id: number) {
    router.post(`/tickets/${id}/assign`);
}
</script>

<template>
    <Head title="All Tickets" />

    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-semibold mb-6">All Tickets</h1>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">ID</th>
                        <th class="text-left p-3">Name</th>
                        <th class="text-left p-3">Status</th>
                        <th class="text-left p-3">Assigned</th>
                        <th class="text-left p-3">Created</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ticket in tickets" :key="ticket.id" class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-3">{{ ticket.id }}</td>
                        <td class="p-3">{{ ticket.name }}</td>
                        <td class="p-3">{{ ticket.status }}</td>
                        <td class="p-3">{{ ticket.assigned_agent_id ? 'Yes' : 'No' }}</td>
                        <td class="p-3">{{ new Date(ticket.created_at).toLocaleDateString() }}</td>
                        <td class="p-3 space-x-2">
                            <Link :href="`/tickets/${ticket.id}`" class="text-blue-600 hover:underline">View</Link>
                            <button v-if="!ticket.assigned_agent_id" @click="assignToMe(ticket.id)" class="text-green-600 hover:underline">Assign to me</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p v-if="tickets.length === 0" class="text-gray-500 mt-4">No tickets found.</p>
        </div>
    </AppLayout>
</template>

