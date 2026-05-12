<template>
    <aside class="fixed left-0 top-0 h-full w-64 bg-[#f8f9ff] dark:bg-[#0d1c2e] flex flex-col py-6 px-4 gap-2 z-50">
        <div class="mb-10 px-4">
            <h1 class="text-2xl font-black bg-gradient-to-br from-[#003ec7] to-[#0052ff] bg-clip-text text-transparent">
                Conectatec
            </h1>
            <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant/60">
                Precision Marketplace
            </p>
        </div>

        <nav class="flex-1 space-y-1">
            <NuxtLink v-for="item in menuItems" :key="item.name" :to="item.path"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all hover:translate-x-1 duration-200"
                :class="isActive(item.path)
                    ? 'text-[#003ec7] dark:text-[#f8f9ff] bg-[#eff4ff] dark:bg-[#1a2b3c] font-bold'
                    : 'text-[#434656] dark:text-[#c3c5d9] hover:text-[#003ec7] dark:hover:text-[#0052ff] hover:bg-[#eff4ff] dark:hover:bg-[#1a2b3c] font-medium'
                    ">
                <span class="material-symbols-outlined">{{ item.icon }}</span>
                <span class="font-['Inter'] text-sm">{{ item.name }}</span>
            </NuxtLink>
        </nav>

        <div class="mt-auto px-2">
            <NuxtLink to="/vendedor/add-product"
                class="w-full bg-gradient-to-br from-[#003ec7] to-[#0052ff] text-white font-bold py-4 rounded-xl shadow-lg active:scale-95 transition-all duration-200 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">add_circle</span>
                Publicar Producto
            </NuxtLink>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'

// 1. Obtenemos la información de la ruta actual
const route = useRoute()

// 2. Definimos las rutas y datos de tu menú
const menuItems = [
    { name: 'Dashboard', icon: 'dashboard', path: '/vendedor/dashboard' },
    { name: 'Mis Productos', icon: 'inventory_2', path: '/vendedor/my-products' },
    { name: 'Mis Entregas', icon: 'local_shipping', path: '/vendedor/entregas' },
    { name: 'Historial Productos', icon: 'history', path: '/vendedor/historial' },
]

// 3. Función que verifica si el ítem actual coincide con la URL del navegador
const isActive = (path: string) => {
    // Excepción: Si evaluamos 'My Products' y estamos en la página de agregar producto
    if (path === '/vendedor/my-products' && route.path.startsWith('/vendedor/add-product')) {
        return true
    }

    // Comportamiento normal para el resto de las rutas
    return route.path.startsWith(path)
}
</script>