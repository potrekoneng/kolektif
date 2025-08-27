<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    status?: string;
    instansi: string;
    slug: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    username: '',
    password: 'password',
    slug: props.slug,
    remember: false,
});

const submit = () => {
    form.post(route('login', { slug: props.slug }), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase :title="instansi" description="NISN / NIK / NIP untuk Masuk">

        <Head title="Log in" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="username">NISN / NIK / NIP</Label>
                    <Input id="username" type="text" required autofocus :tabindex="1" v-model="form.username"
                        placeholder="7498237498723984729" />
                    <InputError :message="form.errors.username" />
                    <div v-if="status" class="mb-4 text-center text-sm font-medium text-red-600">
                        {{ status }}
                    </div>
                </div>

                <InputError :message="form.errors.password" />

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" v-model="form.remember" :tabindex="3" />
                        <span>Remember me</span>
                    </Label>
                </div>

                <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Log in
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
