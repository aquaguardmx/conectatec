<script setup>
// Asegúrate de incluir el bloque de 'headers' dentro de useFetch
const { data, pending, error } = await useFetch('http://127.0.0.1:9000/store/products', {
  headers: {
    'x-publishable-api-key': 'pk_9e2a5edbe41d5fee7817a37b73d14e89cd9922293575a8c68ea2edf38f511514'
  }
})
</script>

<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Catálogo CONECTATEC</h1>
    
    <NuxtLink to="/auth/register" class="inline-block mb-6 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
      Ir al Formulario de Registro
    </NuxtLink>

    <div v-if="pending">
      <p>Cargando productos...</p>
    </div>

    <div v-else-if="error">
      <p style="color: red;">Error: {{ error.message }}</p>
      <pre style="background: #222; color: #ffeb3b; padding: 10px;">{{ error.data }}</pre>
    </div>

    <div v-else-if="data && data.products && data.products.length > 0">
      <p>Tenemos {{ data.products.length }} productos disponibles.</p>
    </div>

    <div v-else>
      <p>Aún no hay productos registrados en la tienda.</p>
    </div>
  </div>
</template>
