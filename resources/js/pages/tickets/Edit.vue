<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Ticket {
    id: number;
    name: string;
    status: string;
}

interface EnumCase {
    name: string;
    value: string;
}

const props = defineProps<{
    ticket: Ticket;
    statuses: EnumCase[];
}>();

const form = useForm({
    status: props.ticket.status,
});

function submit() {
    form.put(`/tickets/${props.ticket.id}`);
}
</script>

<template>
    <Head :title="`Edit Ticket #${ticket.id}`" />

    <AppLayout>
        <div class="p-6 max-w-xl">
            <h1 class="text-2xl font-semibold mb-6">Edit Ticket #{{ ticket.id }}</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block mb-1">Status</label>
                    <select v-model="form.status" class="w-full border rounded p-2 dark:bg-gray-800">
                        <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.name }}</option>
                    </select>
                </div>

                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                    Update
                </button>
            </form>
        </div>
    </AppLayout>
</template>

