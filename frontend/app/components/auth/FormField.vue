<template>
  <div class="space-y-1.5">
    <label :for="id" class="block text-sm font-semibold text-[#3b415a]">
      {{ label }}
    </label>

    <div class="relative flex items-center">
      <!-- Slot for Icon Left (Prefix) -->
      <div v-if="$slots.prefix" class="absolute left-4 flex items-center pointer-events-none text-[#737a91]">
        <slot name="prefix" />
      </div>

      <input :id="id" :type="type" :value="modelValue" :placeholder="placeholder" :autocomplete="autocomplete" :class="[
        'w-full py-3.5 rounded-xl transition-all duration-200 outline-none text-[15px] font-medium placeholder:text-[#a0a6bd]',
        error
          ? 'bg-[#ffdad6]/20 border border-[#ba1a1a] text-[#ba1a1a]'
          : 'bg-[#f4f7fa] border border-transparent text-[#2c3249] focus:bg-white focus:border-[#0052ff] focus:ring-4 focus:ring-[#0052ff]/10',
        $slots.prefix ? 'pl-11' : 'px-4',
        $slots.suffix ? 'pr-12' : 'pr-4',
      ]" @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)" />

      <!-- Slot for Icon Right (Suffix) -->
      <div v-if="$slots.suffix" class="absolute right-4 flex items-center text-[#737a91]">
        <slot name="suffix" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id: string
  modelValue: string
  label: string
  type?: string
  placeholder?: string
  error?: string
  autocomplete?: string
}>()

defineEmits<{
  'update:modelValue': [value: string]
}>()
</script>
