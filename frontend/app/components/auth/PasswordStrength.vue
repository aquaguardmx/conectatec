<template>
  <div class="space-y-2">
    <div class="flex items-center justify-between text-xs">
      <span class="text-[#434656] font-medium">Fuerza de la contraseña:</span>
      <span :class="scoreColorClass" class="font-bold">{{ scoreLabel }}</span>
    </div>

    <!-- Barras de fuerza -->
    <div class="flex gap-1.5 h-1.5">
      <div v-for="i in 4" :key="i" class="flex-1 rounded-full transition-all duration-300" :class="[
        i <= score ? scoreBgClass : 'bg-[#e6eeff]'
      ]" />
    </div>

    <!-- Requisitos -->
    <div class="grid grid-cols-2 gap-2 mt-3">
      <div v-for="(req, index) in requirements" :key="index" class="flex items-center gap-1.5 text-xs"
        :class="req.met ? 'text-[#005761]' : 'text-[#737688]'">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path v-if="req.met" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          <circle v-else cx="12" cy="12" r="4" fill="currentColor" stroke="none" />
        </svg>
        <span>{{ req.label }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  password?: string
}>()

const requirements = computed(() => {
  const pwd = props.password || ''
  return [
    { label: '8+ caracteres', met: pwd.length >= 8 },
    { label: 'Un número', met: /\d/.test(pwd) },
    { label: 'Una mayúscula', met: /[A-Z]/.test(pwd) },
    { label: 'Un carácter especial', met: /[!@#$%^&*(),.?":{}|<>]/.test(pwd) }
  ]
})

const score = computed<number>(() => {
  if (!props.password) return 0
  return requirements.value.filter(r => r.met).length
})

const scoreLabel = computed<string>(() => {
  switch (score.value) {
    case 0: return ''
    case 1: return 'Débil'
    case 2: return 'Regular'
    case 3: return 'Buena'
    case 4: return 'Fuerte'
    default: return ''
  }
})

const scoreColorClass = computed<string>(() => {
  switch (score.value) {
    case 1: return 'text-[#ba1a1a]'
    case 2: return 'text-[#c6955e]'
    case 3: return 'text-[#005761]'
    case 4: return 'text-[#003ec7]'
    default: return 'text-[#737688]'
  }
})

const scoreBgClass = computed<string>(() => {
  switch (score.value) {
    case 1: return 'bg-[#ba1a1a]'
    case 2: return 'bg-[#c6955e]'
    case 3: return 'bg-[#005761]'
    case 4: return 'bg-[#003ec7]'
    default: return 'bg-[#e6eeff]'
  }
})
</script>
