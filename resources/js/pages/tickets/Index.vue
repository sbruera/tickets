<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Ticket {
    id: number;
    name: string;
    status: string;
    type: number;
    created_at: string;
}

defineProps<{
    tickets: Ticket[];
}>();
</script>

<template>
    <Head title="Tickets" />

    <AppLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Tickets</h1>
                <Link href="/tickets/create" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    New Ticket
                </Link>
            </div>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">ID</th>
                        <th class="text-left p-3">Name</th>
                        <th class="text-left p-3">Status</th>
                        <th class="text-left p-3">Created</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ticket in tickets" :key="ticket.id" class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-3">{{ ticket.id }}</td>
                        <td class="p-3">{{ ticket.name }}</td>
                        <td class="p-3">{{ ticket.status }}</td>
                        <td class="p-3">{{ new Date(ticket.created_at).toLocaleDateString() }}</td>
                        <td class="p-3">
                            <Link :href="`/tickets/${ticket.id}`" class="text-blue-600 hover:underline">View</Link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p v-if="tickets.length === 0" class="text-gray-500 mt-4">No tickets found.</p>
        </div>
    </AppLayout>
</template>

