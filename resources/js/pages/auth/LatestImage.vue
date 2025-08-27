<template>
    <div v-if="image">
        <!-- Menampilkan gambar jika ada -->
        <img :src="image" alt="Latest Image" />
    </div>
    <div v-else>
        <!-- Pesan jika tidak ada gambar -->
        <p>No image found.</p>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';

export default defineComponent({
    name: 'LatestImage',
    props: {
        image: {
            type: String as PropType<string | null>,
            required: false,
        },
    },
});
</script>

<style scoped>
img {
    width: 100%;
    height: auto;
}
</style>
