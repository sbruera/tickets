<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface EnumCase {
    name: string;
    value: string | number;
}

defineProps<{
    types: EnumCase[];
    transportModes: EnumCase[];
}>();

const form = useForm({
    name: '',
    type: '',
    transport_mode: '',
    product: '',
    country: '',
    documents: [] as File[],
});

function onFilesChange(e: Event) {
    const target = e.target as HTMLInputElement;
    form.documents = target.files ? Array.from(target.files) : [];
}

function submit() {
    form.post('/tickets', { forceFormData: true });
}
</script>

<template>
    <Head title="Create Ticket" />

    <AppLayout>
        <div class="p-6 max-w-xl">
            <h1 class="text-2xl font-semibold mb-6">Create Ticket</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block mb-1">Name</label>
                    <input v-model="form.name" type="text" class="w-full border rounded p-2 dark:bg-gray-800" required />
                </div>

                <div>
                    <label class="block mb-1">Type</label>
                    <select v-model="form.type" class="w-full border rounded p-2 dark:bg-gray-800" required>
                        <option value="">Select type</option>
                        <option v-for="t in types" :key="t.value" :value="t.value">{{ t.name }}</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Transport Mode</label>
                    <select v-model="form.transport_mode" class="w-full border rounded p-2 dark:bg-gray-800" required>
                        <option value="">Select mode</option>
                        <option v-for="m in transportModes" :key="m.value" :value="m.value">{{ m.name }}</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Product</label>
                    <input v-model="form.product" type="text" class="w-full border rounded p-2 dark:bg-gray-800" />
                </div>

                <div>
                    <label class="block mb-1">Country</label>
                    <select v-model="form.country" class="w-full border rounded p-2 dark:bg-gray-800" required>
                        <option value="">Select country</option>
                        <option value="Argentina">Argentina</option>
                        <option value="USA">USA</option>
                        <option value="Canada">Canada</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Documents</label>
                    <input 
                        type="file" 
                        multiple
                        @change="onFilesChange"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                        class="w-full border rounded p-2 dark:bg-gray-800"
                    />
                </div>

                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                    Create
                </button>
            </form>
        </div>
    </AppLayout>
</template>

