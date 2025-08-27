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
    tipe: 'kartupelajar',
})

const props = defineProps({
    tableData: Object
})

const nameInput = ref(null);

watch(isOpen, async (val) => {
    if (val) {
        await nextTick();
        nameInput.value?.focus();
    }
});

const handleSubmit = () => {
    form.post(route('agency.store'), {
        onSuccess: () => {
            isOpen.value = false
            form.reset()
        }
    })

    // console.log('Data Form:', form.value)
    // Bisa kirim ke server pakai axios/fetch di sini
    isOpen.value = false
}

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-6">
                <button @click="isOpen = true"
                    class="px-4 py-2 bg-green-600 text-sm text-white rounded hover:bg-green-700 mb-3">
                    Tambah Instansi
                </button>

                <!-- Tabel Responsif -->
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200 bg-white">
                        <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Nama
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Tipe
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">link
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Data
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Foto
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="(item, index) in tableData" :key="index" class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ item.name }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-center">{{ item.tipe }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-center">
                                    <Link :href="`/switchuser/${item.slug}`" class="text-blue-500 hover:underline">
                                    Test Login
                                    </Link>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-center">
                                    <Link :href="`/files/${item.id}/data`" class="text-blue-500 hover:underline">
                                    Ambil Data
                                    </Link>
                                </td>
                                <td class="px-6 py-4 text-sm text-center">
                                    <Link :href="`/kolektif/${item.id}/queue`" class="text-blue-500 hover:underline">
                                    Proses Foto
                                    </Link>
                                </td>
                                <td class="px-6 py-4 text-sm text-center">
                                    <Link :href="`/kolektif/${item.id}/data`" class="text-blue-500 hover:underline">
                                    Detail
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Modal :show="isOpen" @close="isOpen = false">
                    <form @submit.prevent="handleSubmit" class="space-y-4">
                        <h2 class="text-lg font-bold text-center">Form Instansi Baru</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                            <input ref="nameInput" v-model="form.name" type="text"
                                class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Instansi</label>
                            <input v-model="form.kode" type="text"
                                class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe</label>
                            <select v-model="form.tipe" name="tipe" id="tipe"
                                class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                required>
                                <option value="single">Single</option>
                                <option value="idcard">ID Card</option>
                                <option value="kartupelajar" selected>Kartu Pelajar</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="isOpen = false"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </Modal>
            </div>
        </div>
    </AppLayout>
</template>
