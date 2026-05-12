<script setup>
import { ref } from 'vue';
import { useRouter, useCookie } from '#app';

const router = useRouter();
const searchQuery = ref('');

// Cookies para validar sesión
const token = useCookie('auth_token');
const userRole = useCookie('user_role');
const userName = useCookie('user_name');

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ path: '/cliente/resultados', query: { q: searchQuery.value } });
  }
};

const navigateToCart = () => {
  router.push('/cliente/carrito');
};

const navigateToAccount = () => {
  if (token.value) {
    if (userRole.value === 'Vendedor') {
      router.push('/vendedor/dashboard');
    } else {
      router.push('/cliente/main');
    }
  } else {
    router.push('/auth/login');
  }
};

const logout = () => {
  // Limpiar cookies
  token.value = null;
  userRole.value = null;
  userName.value = null;

  // Redirigir a login
  router.push('/auth/login');
};
</script>

<template>
  <header
    class="sticky top-0 w-full bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl z-[60] shadow-[0px_12px_32px_rgba(13,28,46,0.06)]">
    <nav class="flex items-center justify-between max-w-7xl mx-auto px-6 h-20">
      <div class="flex items-center gap-8">
        <span
          class="text-2xl font-bold tracking-tighter text-blue-700 dark:text-blue-500 font-headline">Conectatec</span>
        <div class="hidden md:flex items-center gap-6">
          <NuxtLink to="/cliente/main" class="text-blue-700 dark:text-blue-400 font-semibold transition-colors">Home
          </NuxtLink>
          <NuxtLink to="/cliente/explore"
            class="text-slate-500 dark:text-slate-400 font-medium hover:text-blue-600 dark:hover:text-blue-300 transition-colors">
            Explorar</NuxtLink>
          <NuxtLink v-if="token" to="/cliente/historial-compras"
            class="text-slate-500 dark:text-slate-400 font-medium hover:text-blue-600 dark:hover:text-blue-300 transition-colors">
            Mis Pedidos</NuxtLink>
          <NuxtLink v-if="token" to="/cliente/review"
            class="text-slate-500 dark:text-slate-400 font-medium hover:text-blue-600 dark:hover:text-blue-300 transition-colors">
            Mis Reseñas</NuxtLink>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <div
          class="hidden lg:flex items-center bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant/20 focus-within:bg-white focus-within:ring-2 focus-within:ring-primary/20 transition-all">
          <span class="material-symbols-outlined text-on-surface-variant text-xl">search</span>
          <input v-model="searchQuery" @keyup.enter="handleSearch"
            class="bg-transparent border-none focus:ring-0 text-sm w-64 placeholder:text-on-surface-variant/60 outline-none"
            placeholder="Buscar libros, tecnología..." type="text" />
        </div>
        <div class="flex items-center gap-2">
          <button @click="navigateToCart" class="p-2 text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">shopping_cart</span>
          </button>
          <button @click="navigateToAccount" class="p-2 text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">account_circle</span>
          </button>
          <!-- Botón de Cerrar Sesión (solo visible si hay un token) -->
          <button v-if="token" @click="logout" class="p-2 text-red-500 hover:text-red-700 transition-colors"
            title="Cerrar sesión">
            <span class="material-symbols-outlined">logout</span>
          </button>
        </div>
      </div>
    </nav>
  </header>
</template>