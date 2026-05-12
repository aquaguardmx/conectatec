<!-- StarRating.vue -->
<template>
    <div class="flex gap-1">
        <button v-for="star in 5" :key="star" type="button" @click="$emit('update:modelValue', star)"
            class="group transition-transform active:scale-90">
            <span class="material-symbols-outlined"
                :class="[star <= modelValue ? 'star-active' : 'text-outline-variant', sizeClass]">
                star
            </span>
        </button>
    </div>
</template>

<script setup>
defineProps({
    modelValue: Number,
    sizeClass: { type: String, default: 'text-4xl' }
});
defineEmits(['update:modelValue']);
</script>

<style scoped>
.star-active {
    font-variation-settings: 'FILL' 1;
    color: #ffb400;
}
</style>