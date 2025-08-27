<template>

    <Head title="Peserta" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-4 space-y-4">
                <!-- Tombol buka modal dan input pencarian -->
                <div class="flex items-center justify-between gap-4">
                    <button @click="isOpen = true"
                        class="px-4 py-2 bg-blue-600 text-sm text-white rounded hover:bg-blue-700 mb-3">
                        Tambah Peserta {{ props.agency.name }}
                    </button>

                    <Link :href="`/kolektif/${props.agency.id}/nonenisn/spesific`"
                        class="px-2 py-1 bg-cyan-300 text-sm text-gray-500 font-medium rounded hover:bg-fuchsia-500 mb-3 hover:text-white">
                    NISN by Sistem
                    </Link>

                    <Link :href="`/kolektif/${props.agency.id}/pending/spesific`"
                        class="px-2 py-1 bg-orange-300 text-sm text-gray-500 font-medium rounded hover:bg-orange-500 mb-3 hover:text-white">
                    Belum Foto
                    </Link>

                    <input v-model="search" ref="searchInput" @input="fetchData" placeholder="Cari Nama / NISN / NIK..."
                        class="border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <form v-if="props.users.data.length === 0" @submit.prevent="importFile" class="space-y-4">
                    <input type="file" @change="handleFileChange" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
              file:rounded file:border-0 file:text-sm file:font-semibold
              file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <button type="submit"
                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                        Import
                    </button>
                </form>
                <Modal :show="isOpen" @close="isOpen = false">
                    <form @submit.prevent="handleSubmit" class="space-y-4">
                        <h2 class="text-lg font-bold text-center">Form Peserta Baru</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Peserta
                                (Pegawai/Pelajar)</label>
                            <input ref="nameInput" v-model="form.name" type="text"
                                class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIS</label>
                            <input v-model="form.nis" type="text"
                                class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomer Induk (NISN / NIP /
                                NIK)</label>
                            <input v-model="form.nisn" type="text"
                                class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                required />
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
                <!-- Tabel Data Pegawai -->
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-300 via-purple-300 to-pink-300">
                            <th class="border px-2 py-1 text-center">No.</th>
                            <th class="border px-2 py-1 text-center">Nama</th>
                            <th class="border px-2 py-1 text-center">Kelas</th>
                            <th class="border px-2 py-1 text-center">Alamat</th>
                            <th class="border px-2 py-1 text-center">Darah</th>
                            <th class="border px-2 py-1 text-center">Agama</th>
                            <th class="border px-2 py-1 text-center">Kelamin</th>
                            <th class="border px-2 py-1 text-center">NIS</th>
                            <th class="border px-2 py-1 text-center">NISN</th>
                            <th class="border px-2 py-1 text-center">Tmp Lahir</th>
                            <th class="border px-2 py-1 text-center">Tgl Lahir</th>
                            <th class="border px-2 py-1 text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in props.users.data" :key="user.id || index"
                            :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-yellow-100 transition-colors']">
                            <td class="border px-2 py-1">{{ (props.users.current_page - 1) * props.users.per_page +
                                index +
                                1 }}</td>
                            <td class="border px-2 py-1 flex justify-between">
                                <span>{{ user.name }}</span>
                                <span v-if="user.photo == null"
                                    class="flex items-center justify-center text-center px-2 py-0.5 bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-white text-xs rounded-full border-2 border-transparent hover:border-white transition duration-300">
                                    Belum Foto
                                </span>
                            </td>
                            <td class="border px-2 py-1">{{ user.kelas }}</td>
                            <td class="border px-2 py-1">{{ user.alamat }}</td>
                            <td class="border px-2 py-1">{{ user.darah }}</td>
                            <td class="border px-2 py-1">{{ user.agama }}</td>
                            <td class="border px-2 py-1">{{ user.kelamin }}</td>
                            <td class="border px-2 py-1">{{ user.nis }}</td>
                            <td class="border px-2 py-1">{{ user.nisn }}</td>
                            <td class="border px-2 py-1">{{ user.tmp_lahir }}</td>
                            <td class="border px-2 py-1">{{ user.tgl_lahir }}</td>
                            <td v-if="user.locked === 'unlocked'" class="border px-2 py-1"></td>
                            <td v-if="user.locked === 'locked'" class="border px-2 py-1 text-center">
                                <form @submit.prevent="unlockUser(user.id)">
                                    <button type="submit"
                                        class="btn-submit flex items-center justify-center w-8 h-8 rounded-full bg-green-200 text-black hover:bg-green-600  hover:text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors"
                                        title="Buka Kunci">
                                        <LockKeyholeOpen class="icon w-4 h-4 stroke-3" />
                                    </button>
                                </form>
                            </td>
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
            </div>
        </div>
    </AppLayout>
</template>

<script lang="ts" setup>
import { ref, watch, nextTick, computed, onMounted } from 'vue'
import { router, Head, useForm, Link } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia';
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import Modal from '@/components/Modal.vue'
import { LockKeyholeOpen } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
]
const isOpen = ref(false)

const searchInput = ref<HTMLInputElement | null>(null)

onMounted(() => {
    nextTick(() => {
        searchInput.value?.focus()
    })
})

// Props dari server
const props = defineProps<{
    agency: { id: number, name: string },
    users: {
        data: {
            id: number
            name: string
            kelas: string
            alamat: string
            darah: string
            agama: string
            kelamin: string
            nis: number
            nisn: number
            tmp_lahir: string
            tgl_lahir: string
            photo: string
            locked: string
        }[]
        links: { url: string | null; label: string; active: boolean }[]
        current_page: number
        per_page: number
    }
    filters: { search?: string }
}>()

// Search dan pagination
const search = ref(props.filters.search || '')
// function fetchData() {
//     router.get(route('kolektif.data'), { search: search.value }, { preserveState: true, replace: true })
// }
function fetchData() {
    router.get(route('kolektif.data', props.agency.id), { search: search.value }, {
        preserveState: true,
        replace: true
    })
}

// function fetchPage(url: string) {
//     router.visit(url, { preserveState: true })
// }
function fetchPage(url: string) {
    router.visit(url, {
        data: { search: search.value },
        preserveState: true,
        replace: true,
    })
}

const paginationLinks = computed(() => props.users.links ?? [])

// Import file
const file = ref<File | null>(null)
const tipe = ref('item')

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement
    if (target.files && target.files.length > 0) {
        file.value = target.files[0]
    }
}

function importFile() {
    if (!file.value) {
        alert('Mohon pilih file terlebih dahulu.')
        return
    }

    const formData = new FormData()
    formData.append('file', file.value)
    formData.append('agencyId', String(props.agency.id))

    router.post('/kolektif', formData, {
        forceFormData: true,
        onSuccess: () => {
            alert('Import berhasil!')
        }
    })
}

const form = useForm({
    name: '',
    nis: '',
    nisn: '',
})

const nameInput = ref(null);

watch(isOpen, async (val) => {
    if (val) {
        await nextTick();
        nameInput.value?.focus();
    }
});

const handleSubmit = () => {
    form.post(route('user.store', { agency_id: props.agency.id }), {
        onSuccess: () => {
            isOpen.value = false
            form.reset()
        }
    })

    // console.log('Data Form:', form.value)
    // Bisa kirim ke server pakai axios/fetch di sini
    isOpen.value = false
}

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

function formatPageLabel(label: string): string {
    if (label === '&laquo; Previous') return '«';
    if (label === 'Next &raquo;') return '»';
    return label.replace(/&[^;]+;/g, ''); // hapus semua entitas HTML (opsional)
}
</script>
