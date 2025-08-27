<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/user',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    // email: user.email,
    kelas: user.kelas,
    alamat: user.alamat,
    darah: user.darah,
    agama: user.agama,
    kelamin: user.kelamin,
    nis: user.nis,
    nisn: user.nisn,
    tgl_lahir: user.tgl_lahir,
    tmp_lahir: user.tmp_lahir,
    locked: user.locked,
    domain: window.location.hostname
});

// Fungsi untuk menyimpan data
const submit = () => {
    // Pengiriman data untuk "Simpan"
    form.patch(route('user.update'), {
        preserveScroll: true,
        onSuccess: () => {
            //   form.recentlySuccessful = true;
            //   setTimeout(() => {
            //     form.recentlySuccessful = false;
            //   }, 2000);
            window.location.reload(); // reload seluruh halaman
        },
        onError: (errors) => {
            //   console.error(errors);
        }
    });
};

// Fungsi untuk mengirim data
const handleConfirm = () => {
    // Pengiriman data untuk "Kirim"
    form.patch(route('user.lock'), {
        preserveScroll: true,
        onSuccess: () => {
            //   form.recentlySuccessful = true;
            //   setTimeout(() => {
            //     form.recentlySuccessful = false;
            //   }, 2000);
            window.location.reload(); // reload seluruh halaman
        },
        onError: (errors) => {
            //   console.error(errors);
        }
    });
};

// Fungsi handleSubmit untuk form submit (opsional)
const handleSubmit = (e: Event) => {
    e.preventDefault();  // Mencegah form default submit jika diperlukan
};

const modalVisible = ref(false); // State for modal visibility
const openModal = () => {
    modalVisible.value = true;
};
const closeModal = () => {
    modalVisible.value = false;
};
// const handleConfirm = async () => {
//     // Handle confirm logic (e.g., submitting something, etc.)
//     await form.patch(route('user.lock'), {
//         preserveScroll: true,
//     });
//     closeModal();
// };

// Fungsi untuk transisi
const beforeEnter = (el: HTMLElement) => {
    el.style.opacity = '0';
    el.style.transform = 'scale(0.9)';
};

const enter = (el: HTMLElement, done: () => void) => {
    el.offsetHeight; // Trigger reflow to ensure transition starts
    el.style.transition = 'opacity 0.5s, transform 0.5s';
    el.style.opacity = '1';
    el.style.transform = 'scale(1)';
    done(); // Menyelesaikan transisi
};

const leave = (el: HTMLElement, done: () => void) => {
    el.style.transition = 'opacity 0.5s, transform 0.5s';
    el.style.opacity = '0';
    el.style.transform = 'scale(0.9)';
    done(); // Menyelesaikan transisi keluar
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <img v-if="form.locked === 'locked'"
                    :src="`http://${form.domain}/storage/profile/qrcodes/${form.nisn}.png`" alt="NISN QRCODE"
                    class="imageqr" />
                <HeadingSmall title="Profile information" description="Update your name and everything" />

                <!-- <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6"> -->
                <form @submit.prevent="handleSubmit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="grid gap-2">
                        <Label for="name">NAMA LENGKAP (Jika terlalu panjang, bisa disingkat secukupnya;
                            Maksimal. 25 karakter)</Label>
                        <Input id="name" maxlength="25" v-model="form.name" required autocomplete="name"
                            placeholder="Full name" :disabled="form.locked == 'locked'"
                            @input="form.name = form.name.toUpperCase()" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="kelas">Kelas dan Jurusan (Contoh : X-TKN; XI-KKB; XII-TKR)</Label>
                        <Input id="kelas" v-model="form.kelas" required placeholder="X-TKN; XI-KKB; XII-TKR"
                            :disabled="form.locked == 'locked'" />
                        <InputError class="mt-2" :message="form.errors.kelas" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="alamat">ALAMAT (Silahkan ketik secukupnya, maksimal 30 karakter)</Label>
                        <Input id="alamat" v-model="form.alamat" maxlength="30" required
                            placeholder="Alamat - max 25 huruf" :disabled="form.locked == 'locked'" />
                        <InputError class="mt-2" :message="form.errors.alamat" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="darah">Golongan Darah</Label>
                        <select id="darah" v-model="form.darah" required
                            class="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition duration-150 ease-in-out focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-neutral-700 dark:bg-neutral-900 dark:text-white"
                            :disabled="form.locked == 'locked'">
                            <option value="">Pilih jenis Darah</option>
                            <option value="-">Belum Tau</option>
                            <option value="O">O</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O+">O+</option>
                            <option value="A+">A+</option>
                            <option value="B+">B+</option>
                            <option value="AB+">AB+</option>
                            <option value="O-">O-</option>
                            <option value="A-">A-</option>
                            <option value="B-">B-</option>
                            <option value="AB-">AB-</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.kelamin" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="agama">Agama</Label>
                        <Input id="agama" v-model="form.agama" required placeholder="Agama"
                            :disabled="form.locked == 'locked'" />
                        <InputError class="mt-2" :message="form.errors.agama" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="kelamin">Jenis Kelamin</Label>
                        <select id="kelamin" v-model="form.kelamin" :disabled="form.locked == 'locked'" required
                            class="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition duration-150 ease-in-out focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.kelamin" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="nis">NIS / NIPD</Label>
                        <Input id="nis" v-model="form.nis" required placeholder="NIS / NIPD"
                            :disabled="form.locked == 'locked'" />
                        <InputError class="mt-2" :message="form.errors.nis" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="nisn">Nomer Induk (NISN / NIP / NIK)</Label>
                        <Input id="nisn" v-model="form.nisn" required placeholder="NISN / NIP / NIK" readonly
                            :disabled="form.locked == 'locked'" />
                        <InputError class="mt-2" :message="form.errors.nisn" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="tmp_lahir">Kota Kelahiran</Label>
                        <Input id="tmp_lahir" v-model="form.tmp_lahir" required placeholder="Kota Kelahiran"
                            :disabled="form.locked == 'locked'" />
                        <InputError class="mt-2" :message="form.errors.tmp_lahir" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="tgl_lahir">Tanggal Lahir</Label>
                        <Input id="tgl_lahir" type="date" v-model="form.tgl_lahir" required
                            :disabled="form.locked == 'locked'" />
                        <InputError class="mt-2" :message="form.errors.tgl_lahir" />
                    </div>

                    <div class="md:col-span-2 flex items-center gap-4">
                        <Button v-if="user.locked === 'unlocked'" :disabled="form.processing"
                            @click="submit">Simpan</Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Tersimpan.</p>
                        </Transition>
                        <!-- Tombol Kunci -->
                        <button
                            v-if="user.locked === 'unlocked' && user.alamat != '' && user.tmp_lahir != '' && (user.tgl_lahir !== null) && (user.kelamin === 'Laki-laki' || user.kelamin === 'Perempuan') && user.darah != ''"
                            @click="openModal" type="button"
                            class="inline-block px-6 py-2 text-sm text-white bg-orange-500 rounded-lg text-center hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed ml-auto font-bold">
                            Kunci
                        </button>
                    </div>

                    <!-- Modal with Transition -->
                    <transition name="modal" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                        <div v-if="modalVisible"
                            class="fixed inset-0 bg-transparent bg-opacity-80 flex justify-center items-center z-50 backdrop-blur-sm">
                            <div class="bg-gray-50 p-6 rounded-lg shadow-lg w-1/2">
                                <h3 class="text-xl mb-4 text-red-500 font-bold">Pastikan data anda benar.!</h3>
                                <p class="text-sm text-neutral-600 mb-4 font-bold">
                                    Anda akan mengunci data ini. <br><br>
                                    Pastikan semua informasi yang Anda masukkan sudah benar. Setelah dikunci, Anda tidak
                                    dapat mengubah data lagi.<br><br>
                                    Anda otomatis keluar dari halaman ini.
                                </p>
                                <div class="flex justify-end">
                                    <button @click="closeModal"
                                        class="px-4 py-2 bg-white rounded-md hover:bg-gray-600">Batal</button>
                                    <button @click="handleConfirm"
                                        class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 font-bold">Kunci</button>
                                </div>
                            </div>
                        </div>
                    </transition>
                </form>
                <img v-if="form.locked === 'locked'"
                    :src="`http://${form.domain}/storage/profile/barcodes/${form.nisn}.png`" alt="NISN QRCODE"
                    class="image" />
            </div>
            <!-- <DeleteUser /> -->
        </SettingsLayout>
    </AppLayout>
</template>
<style scoped>
/* Transisi modal */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.5s, transform 0.5s;
}

.modal-enter,
.modal-leave-to

/* .modal-leave-active in <2.1.8 */
    {
    opacity: 0;
    transform: scale(0.9);
}

.modal-enter-to {
    opacity: 1;
    transform: scale(1);
}

.image-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    /* Membuat gambar terletak di tengah */
    background-color: #f0f4f8;
    /* Latar belakang lembut */
}

.image {
    width: 200px;
    height: auto;
    /* border-radius: 12px; */
    /* Membuat sudut gambar lebih lembut */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    /* Animasi untuk efek hover */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    /* Efek bayangan */
    object-fit: cover;
    /* Mengatur gambar supaya tetap terpotong dengan baik */
}

.image:hover {
    transform: scale(1.05);
    /* Membesarkan gambar saat hover */
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.2);
    /* Bayangan lebih besar saat hover */
}

.imageqr-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    /* Membuat gambar terletak di tengah */
    background-color: #f0f4f8;
    /* Latar belakang lembut */
}

.imageqr {
    width: 120px;
    height: auto;
    /* border-radius: 12px; */
    /* Membuat sudut gambar lebih lembut */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    /* Animasi untuk efek hover */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    /* Efek bayangan */
    object-fit: cover;
    /* Mengatur gambar supaya tetap terpotong dengan baik */
}

.imageqr:hover {
    transform: scale(1.05);
    /* Membesarkan gambar saat hover */
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.2);
    /* Bayangan lebih besar saat hover */
}
</style>