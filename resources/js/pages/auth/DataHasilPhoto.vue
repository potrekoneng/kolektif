<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, watch, nextTick } from 'vue'
import Modal from '@/components/Modal.vue'
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
const isOpen = ref(false)

const form = useForm({
    name: '',
    kode: '',
    tipe: '',
})

const props = defineProps({
    tableData: {
        type: Object,
        required: true
    }
})

const nameInput = ref(null);

watch(isOpen, async (val) => {
    if (val) {
        await nextTick();
        nameInput.value?.focus();
    }
});

const handleSubmit = () => {
    // form.post(route('agency.store'), {
    //     onSuccess: () => {
    //         isOpen.value = false
    //         form.reset()
    //     }
    // })

    // console.log('Data Form:', form.value)
    // Bisa kirim ke server pakai axios/fetch di sini
    isOpen.value = false
}

const formatTanggal = (tanggal) => {
    const date = new Date(tanggal)
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(date)
}

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-6">
                <!-- <button @click="isOpen = true"
                    class="px-4 py-2 bg-green-600 text-sm text-white rounded hover:bg-green-700 mb-3">
                    Tambah Instansi
                </button> -->

                <!-- Tabel Responsif -->
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200 bg-white">
                        <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">#</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="(item, index) in tableData.data" :key="index" class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-center">
                                    <a :href="`/export/${tableData.agency_id}/${item.date}/data`"
                                        class="text-blue-600 hover:underline">
                                        {{ formatTanggal(item.date) }}
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
