<template>
    <header
        class="fixed top-0 right-0 left-64 h-16 bg-white/80 backdrop-blur-md z-40 flex items-center justify-between px-8 shadow-[0px_12px_32px_rgba(13,28,46,0.06)] border-b border-surface-container">
        <div class="flex items-center flex-1 max-w-xl">
            <div class="relative w-full group">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 group-focus-within:text-primary transition-colors">search</span>
                <input
                    class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary focus:bg-white transition-all"
                    placeholder="Search analytics or orders..." type="text" />
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button
                class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors active:scale-95">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <div class="h-8 w-[1px] bg-outline-variant/20 mx-2"></div>
            
            <div class="flex items-center gap-3 pl-2">
                <div class="text-right">
                    <p class="text-xs font-bold text-on-surface">{{ userName }}</p>
                    <p class="text-[10px] text-on-surface-variant font-medium">{{ userRole }}</p>
                </div>
                
                <button 
                    @click="handleLogout"
                    title="Cerrar sesión"
                    class="p-1 text-[#003ec7] rounded-full hover:bg-red-50 hover:text-red-500 transition-colors group">
                    <span class="material-symbols-outlined text-3xl group-hover:hidden">account_circle</span>
                    <span class="material-symbols-outlined text-3xl hidden group-hover:block">logout</span>
                </button>
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

// 1. Leemos las cookies que Nuxt maneja
const nameCookie = useCookie('user_name')
const roleCookie = useCookie('user_role')

// 2. Creamos variables reactivas, si no hay dato ponemos un valor por defecto
// Usamos .value porque useCookie devuelve una ref
const userName = ref(nameCookie.value || 'Usuario')
const userRole = ref(roleCookie.value ? String(roleCookie.value).toUpperCase() : 'ROOL NO DEFINIDO')

// 3. Función para cerrar sesión
async function handleLogout() {
    // Confirmación opcional
    if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) return;

    // Limpiamos las cookies
    const tokenCookie = useCookie('auth_token')
    
    tokenCookie.value = null
    nameCookie.value = null
    roleCookie.value = null

    // Redirigimos al usuario a la pantalla de login
    await navigateTo('/auth/login')
}
</script>