<template>

    <Head title="Antrian Foto" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-4 space-y-4">
                <div class="flex items-center justify-between w-full">
                    <h1>Data akan di-update setiap 5 detik</h1>
                    <a href="/latest-preview"
                        class="px-4 py-2 bg-black text-white rounded-lg hover:bg-blue-700 transition duration-300 text-sm ml-auto"
                        target="_blank" rel="noopener noreferrer">
                        Preview Last Photo
                    </a>
                </div>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-300 via-purple-300 to-pink-300">
                            <th class="border px-2 py-1 text-center">No.</th>
                            <th class="border px-2 py-1 text-center">Nama</th>
                            <th class="border px-2 py-1 text-center">Alamat</th>
                            <th class="border px-2 py-1 text-center">Kelas</th>
                            <th class="border px-2 py-1 text-center">NISN</th>
                            <th class="border px-2 py-1 text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="data" v-for="(user, index) in data?.users" :key="user.id || index"
                            :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-yellow-100 transition-colors']">
                            <td class="border px-2 py-1">{{ index + 1 }}</td>
                            <td class="border px-2 py-1">{{ user.name }}
                                <form @submit.prevent="unlockUser(user.id)">
                                    <button type="submit"
                                        class="btn-submit flex items-center justify-center w-8 h-8 rounded-full bg-green-200 text-black hover:bg-green-600  hover:text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors"
                                        title="Buka Kunci">
                                        <LockKeyholeOpen class="icon w-4 h-4 stroke-3" />
                                    </button>
                                </form>
                            </td>
                            <td class="border px-2 py-1">{{ user.alamat }}</td>
                            <td class="border px-2 py-1">{{ user.kelas }}</td>
                            <td class="border px-2 py-1">{{ user.nisn }}</td>
                            <td class="border px-2 py-1 text-center">
                                <!-- <button @click="isOpen = true">detail</button> -->
                                <button @click="openModal(user)"
                                    class="px-2 py-1 text-sm bg-blue-500 text-white font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transform transition duration-150 ease-in-out hover:scale-100">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="6" class="border px-2 py-1 text-center">Data sedang dimuat...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
    <!-- Modal Konfirmasi -->
    <!-- <Modal :show="isOpen" @close="isOpen = false"> -->
    <Modal :show="isModalOpen" @close="closeModal">
        <!-- Background dimmed saat modal muncul -->
        <div
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center transition-all duration-300 transform">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-full mx-auto transform transition-all duration-300 ease-out scale-100">
                <!-- Heading -->
                <h2 class="text-xl font-semibold text-center text-gray-800">Proses Foto Peserta</h2>

                <!-- User Info -->
                <p class="text-lg text-gray-600 mt-2">
                    Nama : <strong class="font-semibold">{{ selectedUser?.name }}</strong>
                </p>
                <p class="text-lg text-gray-600 mt-2">
                    Alamat : {{ selectedUser?.alamat }}
                </p>
                <p class="text-lg text-gray-600 mt-2">
                    Kelas : {{ selectedUser?.kelas }}
                </p>
                <p class="text-lg text-gray-600 mt-2">
                    NISN : {{ selectedUser?.nisn }}
                </p>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 mt-4">
                    <button type="button" @click="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="button" @click="processData" :disabled="isProcessing"
                        class="px-4 py-2 rounded-md text-white font-semibold transition-all duration-300 ease-in-out"
                        :class="[
                            isProcessing ? 'bg-green-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700',
                        ]">
                        <span v-if="isProcessing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Memproses...
                        </span>
                        <span v-else>Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </Modal>

</template>

<script lang="ts" setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

import { router, Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import Modal from '@/components/Modal.vue'
import { LockKeyholeOpen } from 'lucide-vue-next';

// import { defineProps } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
]
const isProcessing = ref(false);

// Definisikan tipe untuk agency
interface Agency {
    id: number;
    name?: string;
}

// Definisikan tipe untuk user dan response
interface User {
    id: number;
    name: string;
    alamat: string;
    kelas: string;
    nis: number;
    nisn: number;
    locked: string;
}

interface ApiResponse {
    users: User[];
    agency: Agency;
    updated_at: string;
}

// Ambil props dari Inertia
const props = defineProps<{
    agency: Agency;
}>();

// State untuk menyimpan data dari API dan modal
const data = ref<ApiResponse | null>(null);
const isModalOpen = ref(false);
const selectedUser = ref<User | null>(null);

const isOpen = ref(false)

// Fungsi untuk mengambil data dari server
const fetchData = async () => {
    try {
        const response = await axios.get<ApiResponse>(`/kolektif/${props.agency.id}/foto`);
        data.value = response.data;
    } catch (error) {
        console.error('Terjadi kesalahan saat mengambil data:', error);
    }
};

// Fungsi untuk membuka modal dan memilih user
const openModal = (user: User) => {
    selectedUser.value = user;
    isModalOpen.value = true;
};

// Fungsi untuk menutup modal
const closeModal = () => {
    isModalOpen.value = false;
    selectedUser.value = null;
};

// Fungsi untuk memproses data yang dipilih
const processData = async () => {
    if (selectedUser.value) {
        isProcessing.value = true;
        try {
            // Proses data yang dipilih, misalnya kirim request ke server untuk update
            const response = await axios.post(`/prosesfoto/${selectedUser.value.id}`);
            // Lakukan sesuatu setelah proses berhasil
            // console.log('Data diproses:', response.data);
            closeModal();
        } catch (error) {
            console.error('Terjadi kesalahan saat memproses data:', error);
        } finally {
            isProcessing.value = false;
        }
    }
};

const form = useForm({
    name: '',
    nis: '',
    nisn: '',
})

const unlockUser = (userId: number) => {
    form.post(route('unlock.user', { user_id: userId }), {
        onSuccess: () => {
            // isOpen.value = false
            // form.reset()
        }
    })

    // console.log('Data Form:', form.value)
    // Bisa kirim ke server pakai axios/fetch di sini
    // isOpen.value = false
}

// Interval ID
let intervalId: number;

// Jalankan saat component mount
onMounted(() => {
    fetchData();
    intervalId = window.setInterval(fetchData, 2000);
});

// Bersihkan interval saat komponen di-unmount
onBeforeUnmount(() => {
    clearInterval(intervalId);
});
</script>

<style scoped>
/* Styling tambahan jika diperlukan */
</style>
