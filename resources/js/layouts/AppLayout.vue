<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div>
            <div v-if="$page.props.flash.success" class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ $page.props.flash.success }}
            </div>
        </div>
        <slot />
    </AppLayout>
</template>
