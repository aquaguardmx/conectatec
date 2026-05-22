<script setup>
// Redireccionar al login al acceder a la raíz del sitio
definePageMeta({
  middleware: [
    function (to, from) {
      return navigateTo('/auth/login')
    }
  ]
})
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-background">
    <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
  </div>
</template>
