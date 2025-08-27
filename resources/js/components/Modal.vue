<template>
    <teleport to="body">
        <transition name="modal-fade">
            <div v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm w-full"
                @click.self="close">
                <div
                    class="bg-white p-6 rounded-lg shadow-lg transform transition-all duration-300 scale-95 w-full max-w-2xl mx-2">
                    <slot />
                </div>
            </div>
        </transition>
    </teleport>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
const props = defineProps({ show: Boolean })
const emit = defineEmits(['close'])

const close = () => emit('close')

const handleKeydown = (e) => {
    if (e.key === 'Escape') close()
}

onMounted(() => {
    window.addEventListener('keydown', handleKeydown)
})
onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
