<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Document {
    id: number;
    type: string;
    created_at: string;
}

interface DocumentRequest {
    id: number;
    document_type: string;
    fulfilled_at: string | null;
    created_at: string;
}

interface Ticket {
    id: number;
    name: string;
    status: string;
    type: number;
    transport_mode: string;
    product: string;
    country: string;
    created_at: string;
    documents: Document[];
    document_requests: DocumentRequest[];
}

interface StatusOption {
    name: string;
    value: string;
}

const props = defineProps<{
    ticket: Ticket;
    canRequestDocuments: boolean;
    canUpdateStatus: boolean;
    statuses: StatusOption[];
}>();

const fileInput = ref<HTMLInputElement | null>(null);
const form = useForm({
    document: null as File | null,
});

const requestForm = useForm({
    document_type: '',
});

const statusForm = useForm({
    status: props.ticket.status,
});

watch(() => props.ticket.status, (newStatus) => {
    statusForm.status = newStatus;
});

function destroy(id: number) {
    if (confirm('Delete this ticket?')) {
        router.delete(`/tickets/${id}`);
    }
}

function deleteDocument(id: number) {
    if (confirm('Delete this document?')) {
        router.delete(`/documents/${id}`);
    }
}

function uploadDocument(ticketId: number) {
    if (!form.document) return;
    form.post(`/tickets/${ticketId}/documents`, {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            if (fileInput.value) fileInput.value.value = '';
        },
    });
}

function onFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    form.document = target.files?.[0] || null;
}

function submitDocumentRequest(ticketId: number) {
    if (!requestForm.document_type) return;
    requestForm.post(`/tickets/${ticketId}/document-requests`, {
        onSuccess: () => requestForm.reset(),
    });
}

function deleteDocumentRequest(id: number) {
    if (confirm('Delete this request?')) {
        router.delete(`/document-requests/${id}`);
    }
}

function fulfillRequest(id: number) {
    router.post(`/document-requests/${id}/fulfill`);
}

function updateStatus() {
    statusForm.put(`/tickets/${props.ticket.id}`);
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
                    <dd v-if="!canUpdateStatus">{{ ticket.status }}</dd>
                    <form v-else @submit.prevent="updateStatus" class="flex gap-2 items-center">
                        <select v-model="statusForm.status" class="border rounded p-1 dark:bg-gray-800">
                            <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.name }}</option>
                        </select>
                        <button 
                            type="submit" 
                            :disabled="statusForm.processing || statusForm.status === ticket.status"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 text-sm"
                        >
                            Update
                        </button>
                    </form>
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

            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Documents</h2>
                
                <form @submit.prevent="uploadDocument(ticket.id)" class="flex gap-2 mb-4">
                    <input 
                        ref="fileInput"
                        type="file" 
                        @change="onFileChange"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                        class="flex-1 border rounded p-2 dark:bg-gray-800"
                    />
                    <button 
                        type="submit" 
                        :disabled="!form.document || form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        Upload
                    </button>
                </form>

                <ul v-if="ticket.documents?.length" class="space-y-2">
                    <li v-for="doc in ticket.documents" :key="doc.id" class="flex justify-between items-center p-2 border rounded">
                        <a :href="`/documents/${doc.id}`" class="text-blue-600 hover:underline">{{ doc.type }}</a>
                        <button @click="deleteDocument(doc.id)" class="text-red-600 text-sm hover:underline">Delete</button>
                    </li>
                </ul>
                <p v-else class="text-gray-500">No documents uploaded.</p>
            </div>

            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Document Requests</h2>
                
                <form v-if="canRequestDocuments" @submit.prevent="submitDocumentRequest(ticket.id)" class="flex gap-2 mb-4">
                    <input 
                        v-model="requestForm.document_type"
                        type="text"
                        placeholder="Document type to request"
                        class="flex-1 border rounded p-2 dark:bg-gray-800"
                    />
                    <button 
                        type="submit" 
                        :disabled="!requestForm.document_type || requestForm.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        Request
                    </button>
                </form>

                <ul v-if="ticket.document_requests?.length" class="space-y-2">
                    <li v-for="req in ticket.document_requests" :key="req.id" class="flex justify-between items-center p-2 border rounded">
                        <span :class="{ 'line-through text-gray-400': req.fulfilled_at }">{{ req.document_type }}</span>
                        <div class="space-x-2">
                            <span v-if="req.fulfilled_at" class="text-green-600 text-sm">Fulfilled</span>
                            <button v-else @click="fulfillRequest(req.id)" class="text-green-600 text-sm hover:underline">Mark Fulfilled</button>
                            <button v-if="canRequestDocuments" @click="deleteDocumentRequest(req.id)" class="text-red-600 text-sm hover:underline">Delete</button>
                        </div>
                    </li>
                </ul>
                <p v-else class="text-gray-500">No document requests.</p>
            </div>

            <Link href="/tickets" class="inline-block mt-6 text-blue-600 hover:underline">← Back to tickets</Link>
        </div>
    </AppLayout>
</template>
