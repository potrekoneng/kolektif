<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

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
});

const submit = () => {
    form.patch(route('user.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and everything" />

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Nama Lengkap</Label>
                        <Input id="name" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="kelas">Kelas</Label>
                        <Input id="kelas" v-model="form.kelas" required placeholder="Kelas" />
                        <InputError class="mt-2" :message="form.errors.kelas" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="alamat">Alamat</Label>
                        <Input id="alamat" v-model="form.alamat" maxlength="25" required
                            placeholder="Alamat - max 25 huruf" />
                        <InputError class="mt-2" :message="form.errors.alamat" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="darah">Darah</Label>
                        <select id="darah" v-model="form.darah" required
                            class="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition duration-150 ease-in-out focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
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
                        <Input id="agama" v-model="form.agama" required placeholder="Agama" />
                        <InputError class="mt-2" :message="form.errors.agama" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="kelamin">Kelamin</Label>
                        <select id="kelamin" v-model="form.kelamin" required
                            class="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition duration-150 ease-in-out focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.kelamin" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="nis">NIS</Label>
                        <Input id="nis" v-model="form.nis" required placeholder="NIS" readonly />
                        <InputError class="mt-2" :message="form.errors.nis" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="nisn">NISN</Label>
                        <Input id="nisn" v-model="form.nisn" required placeholder="NISN" readonly />
                        <InputError class="mt-2" :message="form.errors.nisn" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="tmp_lahir">Tempat Lahir</Label>
                        <Input id="tmp_lahir" v-model="form.tmp_lahir" required placeholder="Tempat Lahir" />
                        <InputError class="mt-2" :message="form.errors.tmp_lahir" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="tgl_lahir">Tanggal Lahir</Label>
                        <Input id="tgl_lahir" type="date" v-model="form.tgl_lahir" required />
                        <InputError class="mt-2" :message="form.errors.tgl_lahir" />
                    </div>

                    <div class="md:col-span-2 flex items-center gap-4">
                        <Button :disabled="form.processing">Simpan</Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Tersimpan.</p>
                        </Transition>
                        <!-- Tombol Kunci -->
                        <button :disabled="form.processing" @click="openModal"
                            class="inline-block px-6 py-2 text-sm text-white bg-orange-500 rounded-lg text-center hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed ml-auto">
                            Kunci
                        </button>
                    </div>
                </form>
                <!-- Modal -->
                <div v-if="modalVisible"
                    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-80">
                        <h3 class="text-xl mb-4">Are you sure you want to continue?</h3>
                        <div class="flex justify-end">
                            <button @click="closeModal"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Cancel</button>
                            <button @click="handleConfirm"
                                class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <DeleteUser /> -->
        </SettingsLayout>
    </AppLayout>
</template>
