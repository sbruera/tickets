<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

interface Ticket {
    id: number;
    name: string;
    status: string;
    type: number;
    transport_mode: string;
    product: string;
    country: string;
    created_at: string;
}

defineProps<{
    ticket: Ticket;
}>();

function destroy(id: number) {
    if (confirm('Delete this ticket?')) {
        router.delete(`/tickets/${id}`);
    }
}
</script>

<template>
    <Head :title="`Ticket #${ticket.id}`" />

    <AppLayout>
        <div class="p-6 max-w-xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Ticket #{{ ticket.id }}</h1>
                <div class="space-x-2">
                    <Link :href="`/tickets/${ticket.id}/edit`" class="px-3 py-1 border rounded hover:bg-gray-100 dark:hover:bg-gray-800">Edit</Link>
                    <button @click="destroy(ticket.id)" class="px-3 py-1 border border-red-600 text-red-600 rounded hover:bg-red-50">Delete</button>
                </div>
            </div>

            <dl class="space-y-3">
                <div>
                    <dt class="text-gray-500 text-sm">Name</dt>
                    <dd>{{ ticket.name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-sm">Status</dt>
                    <dd>{{ ticket.status }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-sm">Type</dt>
                    <dd>{{ ticket.type }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-sm">Transport Mode</dt>
                    <dd>{{ ticket.transport_mode }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-sm">Product</dt>
                    <dd>{{ ticket.product || '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-sm">Country</dt>
                    <dd>{{ ticket.country || '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-sm">Created</dt>
                    <dd>{{ new Date(ticket.created_at).toLocaleString() }}</dd>
                </div>
            </dl>

            <Link href="/tickets" class="inline-block mt-6 text-blue-600 hover:underline">← Back to tickets</Link>
        </div>
    </AppLayout>
</template>

