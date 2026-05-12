<template>
  <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/15">
    <span class="text-xl leading-none mt-0.5">{{ icon }}</span>
    <div>
      <p class="text-white font-semibold text-sm">{{ title }}</p>
      <p class="text-white/65 text-xs leading-relaxed mt-0.5">{{ description }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  icon: string
  title: string
  description: string
}>()
</script>
