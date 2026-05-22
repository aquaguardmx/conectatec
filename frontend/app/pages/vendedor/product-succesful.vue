<template>
    <div class="bg-background text-on-background min-h-screen flex flex-col relative z-0">
        <main class="flex-grow flex items-center justify-center p-6 md:p-20 z-10">
            <div class="max-w-4xl w-full flex flex-col items-center gap-12">

                <div class="text-center space-y-6 max-w-2xl">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-primary to-primary-container shadow-[0px_12_32px_rgba(0,62,199,0.2)]">
                        <span class="material-symbols-outlined text-on-primary text-4xl"
                            style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-5xl font-black tracking-tight text-on-background leading-tight">
                            ¡Listo para el Mercado!
                        </h1>
                        <p class="text-on-surface-variant text-lg font-medium leading-relaxed">
                            Tu producto **{{ producto.Nombre }}** ha sido validado y ya se encuentra visible para miles
                            de compradores.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full items-start">

                    <div
                        class="bg-surface-container-lowest rounded-xl p-4 shadow-[0px_12px_32px_rgba(13,28,46,0.06)] group">
                        <div class="relative overflow-hidden rounded-lg aspect-square mb-4 bg-surface-container-low">
                            <img :src="getImageUrl" :alt="producto.Nombre"
                                class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105" />

                            <div class="absolute top-3 left-3 flex gap-2">
                                <span
                                    class="bg-tertiary/90 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]"
                                        style="font-variation-settings: 'FILL' 1;">verified</span>
                                    Verificado
                                </span>
                            </div>
                        </div>
                        <div class="px-2 pb-2 space-y-3">
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <h3 class="text-title-lg font-bold text-on-surface tracking-tight leading-tight">
                                        {{ producto.Nombre }}
                                    </h3>
                                    <p class="text-on-surface-variant text-sm font-medium">
                                        {{ producto.Categoria }}
                                    </p>
                                </div>
                                <span class="text-primary font-black text-lg shrink-0">
                                    ${{ Number(producto.Precio).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
                                </span>
                            </div>
                            <div class="bg-surface-container-low rounded-lg p-3 flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary"
                                    style="font-variation-settings: 'FILL' 1;">inventory_2</span>
                                <div class="text-xs">
                                    <p class="text-on-surface font-bold uppercase tracking-tighter">Stock Actual</p>
                                    <p class="text-on-surface-variant">{{ producto.Stock }} Unidades Disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8 flex flex-col justify-center h-full">
                        <div class="bg-surface-container-low rounded-xl p-8 space-y-6">
                            <h4 class="text-on-surface font-bold text-xl tracking-tight">Siguientes Pasos</h4>
                            <div class="space-y-4">
                                <button
                                    class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold py-4 px-6 rounded-lg flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-95 shadow-lg shadow-primary/20">
                                    <span class="material-symbols-outlined">visibility</span>
                                    Ver Publicación en Vivo
                                </button>
                                <NuxtLink to="/vendedor/dashboard"
                                    class="w-full bg-secondary-container text-on-secondary-container font-bold py-4 px-6 rounded-lg flex items-center justify-center gap-2 hover:bg-secondary-container/80 transition-all active:scale-95 text-center">
                                    <span class="material-symbols-outlined">dashboard</span>
                                    Ir al Panel de Dashboard
                                </NuxtLink>
                            </div>
                            <div class="pt-4 border-t border-outline-variant/20 space-y-3">
                                <p class="text-on-surface-variant text-sm">Otras opciones:</p>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        class="text-primary font-bold text-sm flex items-center gap-1 hover:underline">
                                        <span class="material-symbols-outlined text-sm">share</span> Compartir
                                    </button>
                                    <button
                                        class="text-primary font-bold text-sm flex items-center gap-1 hover:underline">
                                        <span class="material-symbols-outlined text-sm">edit</span> Editar Detalles
                                    </button>
                                    <NuxtLink to="/vendedor/add-product"
                                        class="text-primary font-bold text-sm flex items-center gap-1 hover:underline">
                                        <span class="material-symbols-outlined text-sm">add_box</span> Publicar Otro
                                    </NuxtLink>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 p-4 border-l-4 border-tertiary bg-tertiary-fixed/10 rounded-r-lg">
                            <span class="material-symbols-outlined text-tertiary"
                                style="font-variation-settings: 'FILL' 1;">lightbulb</span>
                            <p class="text-sm text-on-tertiary-fixed-variant leading-snug">
                                <strong class="block mb-1">Tip de Precisión:</strong>
                                ¡Buen trabajo! Tu producto ahora usa el campo `TEXT` en la base de datos para almacenar
                                la imagen correctamente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'

const { storageUrl } = useApiUrl()

const producto = ref({
    Nombre: 'Producto',
    Precio: 0,
    Stock: 0,
    Categoria: 'General',
    Imagenes: null
})

const getImageUrl = computed(() => {
    if (!producto.value.Imagenes) return 'https://via.placeholder.com/400'
    // Si la imagen ya es una URL completa
    if (producto.value.Imagenes.startsWith('http') || producto.value.Imagenes.startsWith('data:')) {
        return producto.value.Imagenes
    }
    // Asumiendo que el backend de Laravel expone Storage local en el puerto 8000
    return `${storageUrl}/${producto.value.Imagenes}`
})

onMounted(() => {
    // Recuperamos la data enviada por el router
    if (window.history.state?.producto) {
        producto.value = window.history.state.producto
    }
})
</script>

<style scoped>
/* Importación de Google Fonts y Material Symbols */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

/* Aplicando la fuente globalmente al contenedor de este componente */
div {
    font-family: 'Inter', sans-serif;
}

/* Configuración de iconos Material de Google */
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>