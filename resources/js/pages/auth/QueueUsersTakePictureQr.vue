<template>

    <Head title="Peserta" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-4 space-y-4">
                <!-- Tombol buka modal dan input pencarian -->
                <div class="flex items-center justify-between gap-4">
                    <input v-model="search" ref="searchInput" @input="fetchData" placeholder="Cari NISN / NIK..."
                        class="border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    <a href="/latest-preview"
                        class="px-4 py-2 bg-black text-white rounded-lg hover:bg-blue-700 transition duration-300 text-sm ml-auto"
                        target="_blank" rel="noopener noreferrer">
                        Preview Last Photo
                    </a>
                </div>

                <!-- Table Data -->
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-300 via-purple-300 to-pink-300">
                            <th class="border px-2 py-1 text-center">No.</th>
                            <th class="border px-2 py-1 text-center">Nama</th>
                            <th class="border px-2 py-1 text-center">Kelas</th>
                            <th class="border px-2 py-1 text-center">Alamat</th>
                            <th class="border px-2 py-1 text-center">NISN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in props.users.data" :key="user.id || index"
                            :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-yellow-100 transition-colors']">
                            <td class="border px-2 py-1">{{ (props.users.current_page - 1) * props.users.per_page +
                                index + 1 }}</td>
                            <td class="border px-2 py-1">{{ user.name }}</td>
                            <td class="border px-2 py-1">{{ user.kelas }}</td>
                            <td class="border px-2 py-1">{{ user.alamat }}</td>
                            <td class="border px-2 py-1">{{ user.nisn }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-center mt-4 space-x-1">
                    <button v-for="page in paginationLinks" :key="page.label" :disabled="!page.url"
                        @click="fetchPage(page.url)"
                        class="px-3 py-1 border rounded transition-colors hover:bg-blue-100" :class="{
                            'bg-blue-200 text-blue-900': page.active,
                            'text-gray-500 bg-gray-100': !page.url,
                        }">
                        {{ formatPageLabel(page.label) }}
                    </button>
                </div>

                <!-- Modal Data Ditemukan -->
                <div v-if="showModal"
                    class="fixed inset-0 flex justify-center items-center bg-black/30 bg-opacity-50 backdrop-blur-md">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-96 z-10">
                        <h2 class="text-xl font-semibold mb-4">Data Peserta</h2>
                        <div v-if="userData" class="space-y-2">
                            <p><strong>Nama:</strong> {{ userData.name }}</p>
                            <p><strong>NISN:</strong> {{ userData.nisn }}</p>
                            <p><strong>Kelas:</strong> {{ userData.kelas }}</p>
                            <p><strong>Alamat:</strong> {{ userData.alamat }}</p>
                        </div>
                        <div class="mt-4 flex justify-between gap-4">
                            <button @click="closeModal" class="bg-gray-300 text-black px-4 py-2 rounded">Tutup</button>
                            <form @submit.prevent="unlockUser(userData.id)">
                                <button type="submit"
                                    class="btn-submit flex items-center justify-center w-8 h-8 rounded-full bg-green-200 text-black hover:bg-green-600  hover:text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors"
                                    title="Buka Kunci">
                                    <LockKeyholeOpen class="icon w-4 h-4 stroke-3" />
                                </button>
                            </form>
                            <!-- <button @click="processData"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Proses</button> -->
                            <button type="button" @click="processData" :disabled="isProcessing"
                                class="px-4 py-2 rounded-md text-white font-semibold transition-all duration-300 ease-in-out"
                                :class="[
                                    isProcessing ? 'bg-green-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700',
                                ]">
                                <span v-if="isProcessing" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
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

                <!-- Modal Data Tidak Ditemukan -->
                <div v-if="showModalEmpty"
                    class="fixed inset-0 flex justify-center items-center bg-black/30 bg-opacity-50 backdrop-blur-md">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-96 z-10">
                        <h2 class="text-xl font-semibold mb-4">Data Peserta Tidak Ditemukan</h2>
                        <div class="mt-4 flex justify-center gap-4">
                            <button @click="closeModalEmpty"
                                class="bg-gray-300 text-black px-4 py-2 rounded">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script lang="ts" setup>
import { ref, nextTick, computed, onMounted } from 'vue'
import { router, Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { LockKeyholeOpen } from 'lucide-vue-next';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
]
const isProcessing = ref(false);

const searchInput = ref<HTMLInputElement | null>(null)

onMounted(() => {
    nextTick(() => {
        searchInput.value?.focus()
    })
})

const props = defineProps<{
    agency: { id: number, name: string },
    users: {
        data: {
            id: number
            name: string
            kelas: string
            alamat: string
            nisn: number
            photo: string
            locked: string
        }[] // Hanya mengambil data peserta yang ditemukan
        links: { url: string | null; label: string; active: boolean }[]
        current_page: number
        per_page: number
    }
    filters: { search?: string }
}>()

// Variabel untuk mencari dan menampilkan data modal
const search = ref(props.filters.search || '')
const showModal = ref(false) // untuk menampilkan modal
const showModalEmpty = ref(false) // untuk menampilkan modal
const userData = ref<any>(null) // Menyimpan data yang ditemukan setelah pencarian

// Fungsi pencarian
function fetchData() {
    // Lakukan request tanpa menggunakan .then()
    router.get(route('kolektif.queue', props.agency.id), { search: search.value }, {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            // Setelah data berhasil diterima, lakukan pencarian dalam data
            const foundUser = props.users.data.find(user =>
                user.name.toLowerCase().includes(search.value.toLowerCase()) ||
                user.nisn.toString().includes(search.value)
            )

            if (foundUser) {
                userData.value = foundUser // Menyimpan data yang ditemukan
                showModal.value = true // Tampilkan modal
            } else {
                userData.value = null // Tidak ada data ditemukan
                showModalEmpty.value = true // Jangan tampilkan modal
            }
        },
    })
}

function fetchPage(url: string) {
    router.visit(url, {
        data: { search: search.value },
        preserveState: true,
        replace: true,
    })
}

// Fungsi untuk menutup modal
const closeModal = () => {
    search.value = '';
    nextTick(() => {
        searchInput.value?.focus();  // Fokus ke input pencarian setelah render ulang
    });
    showModal.value = false
}
const closeModalEmpty = () => {
    search.value = '';
    nextTick(() => {
        searchInput.value?.focus();  // Fokus ke input pencarian setelah render ulang
    });
    showModalEmpty.value = false
}

// Fungsi untuk memproses data yang dipilih
const processData = async () => {
    if (userData.value) {
        isProcessing.value = true;
        try {
            // console.log('Data yang akan diproses:', userData.value.id)
            const response = await axios.post(`/prosesfoto/${userData.value.id}`);
            userData.value = response.data;
            search.value = '';
            nextTick(() => {
                searchInput.value?.focus();  // Fokus ke input pencarian setelah render ulang
            });
            closeModal()
        } catch (error) {
            console.error('Terjadi kesalahan saat memproses data:', error);
        } finally {
            isProcessing.value = false;
        }
    }
};

const unlockUser = (userId: number) => {
    form.post(route('unlock.user', { user_id: userId }), {
        onSuccess: () => {
            search.value = '';
            nextTick(() => {
                searchInput.value?.focus();  // Fokus ke input pencarian setelah render ulang
            });
            closeModal()
        }
    })

    // console.log('Data Form:', form.value)
    // Bisa kirim ke server pakai axios/fetch di sini
    // isOpen.value = false
}

// Pagination dan lainnya (dapat digunakan jika diperlukan)
const paginationLinks = computed(() => props.users.links ?? [])

const form = useForm({
    name: '',
    nis: '',
    nisn: '',
})

function formatPageLabel(label: string): string {
    if (label === '&laquo; Previous') return '«'
    if (label === 'Next &raquo;') return '»'
    return label.replace(/&[^;]+;/g, '') // hapus semua entitas HTML (opsional)
}
</script>

<style scoped>
/* Modal dan styling lainnya */
.fixed {
    z-index: 999;
}

/* Menambahkan efek backdrop blur */
.backdrop-blur-md {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    /* Safari */
}

/* Styling lainnya */
.bg-white {
    background-color: white;
}

input {
    width: 100%;
    max-width: 300px;
}

/* Styling untuk gambar */
img {
    object-fit: cover;
}
</style>
