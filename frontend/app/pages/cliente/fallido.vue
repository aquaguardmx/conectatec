<script setup lang="ts">
import Header from '../../components/cliente/Header.vue';
import { onMounted, ref } from 'vue';
import { useProductos } from '~/composables/useProductos';

const { obtenerTodosProductos, isLoading } = useProductos();
const suggestedProducts = ref([]);

onMounted(async () => {
    const response = await obtenerTodosProductos();
    if (response.success && response.data?.data) {
        // Mostrar 5 productos aleatorios como sugerencias
        suggestedProducts.value = response.data.data
            .sort(() => 0.5 - Math.random())
            .slice(0, 5)
            .map((p: any) => ({
                id: p.id_producto,
                name: p.Nombre,
                price: parseFloat(p.Precio),
                description: p.Descripcion,
                image: p.Imagenes
                    ? (p.Imagenes.startsWith('http') ? p.Imagenes : `http://127.0.0.1:8000/storage/${p.Imagenes}`)
                    : 'https://placehold.co/600x400?text=Tecnología'
            }));
    }
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(price);
};
</script>

<template>
    <div class="bg-background text-on-surface antialiased min-h-screen">
        <Header />

        <main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
            <section class="flex flex-col items-center justify-center text-center py-16 px-4">
                <div class="relative mb-8">
                    <div class="absolute inset-0 bg-primary/5 rounded-full blur-3xl scale-150"></div>
                    <div
                        class="relative w-64 h-64 md:w-80 md:h-80 mx-auto bg-surface-container-lowest rounded-full p-8 flex items-center justify-center shadow-[0px_12px_32px_rgba(13,28,46,0.06)]">
                        <img alt="No results found" class="w-full h-full object-contain rounded-full opacity-90"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuC2iB9rHEaK8i-dpwksN4ZMK8UOlAGY-VLsqtWepvWufxv4RSvKEhOH21Fwmfvi1-eSQ5hrWrE-Xqbu7Zbfrdgxltw-MjZiJCI2D2sKQhJAfazSDMKtkwEqvuRpZ69nVjznbT5JD1EjneYl8VvtapNwMiX2kZ__3yuqUHgT0ApaOMjDGD8RiHgSj6JmS4urP3Q1NsDuEoxGxnHmiCgCKP28V2OzVv1iHUo3MSejQRrlc5EFgv5z47nsdbo1qln44kAp6Z7ku1y40NU" />
                    </div>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight mb-4">No encontramos lo
                    que buscas</h1>
                <p class="text-on-surface-variant text-lg max-w-lg mx-auto mb-10 leading-relaxed">
                    No hay coincidencias para tu búsqueda. ¡Pero no te detengas aquí! Intenta ajustar tus términos para
                    encontrar el equipo perfecto.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-4xl mb-20">
                    <div class="bg-surface-container-low p-6 rounded-xl text-left border border-outline-variant/10">
                        <span class="material-symbols-outlined text-primary mb-3">spellcheck</span>
                        <h3 class="font-bold text-on-surface mb-2">Revisa la ortografía</h3>
                        <p class="text-sm text-on-surface-variant">Un pequeño error tipográfico puede cambiarlo todo.
                            Verifica los nombres técnicos.</p>
                    </div>
                    <div class="bg-surface-container-low p-6 rounded-xl text-left border border-outline-variant/10">
                        <span class="material-symbols-outlined text-primary mb-3">public</span>
                        <h3 class="font-bold text-on-surface mb-2">Términos generales</h3>
                        <p class="text-sm text-on-surface-variant">En lugar de un modelo específico, intenta buscar por
                            categoría como "Laptop" o "Monitor".</p>
                    </div>
                    <div class="bg-surface-container-low p-6 rounded-xl text-left border border-outline-variant/10">
                        <span class="material-symbols-outlined text-primary mb-3">filter_alt_off</span>
                        <h3 class="font-bold text-on-surface mb-2">Quita los filtros</h3>
                        <p class="text-sm text-on-surface-variant">Si aplicaste muchos filtros, intenta eliminarlos para
                            ampliar el rango de búsqueda.</p>
                    </div>
                </div>
            </section>

            <section class="mt-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-on-surface tracking-tight">Tal vez te interese</h2>
                    <NuxtLink class="text-primary font-semibold text-sm flex items-center gap-1 hover:underline"
                        to="/cliente/explore">
                        Ver todo el catálogo <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </NuxtLink>
                </div>

                <div v-if="suggestedProducts.length > 0" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Producto Destacado (el primero) -->
                    <div
                        class="md:col-span-2 md:row-span-2 group relative overflow-hidden rounded-xl bg-surface-container-lowest shadow-[0px_12px_32px_rgba(13,28,46,0.06)] border border-outline-variant/10">
                        <div class="aspect-square md:aspect-auto md:h-full relative">
                            <img :alt="suggestedProducts[0].name"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                :src="suggestedProducts[0].image" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-transparent to-transparent">
                            </div>
                            <div class="absolute bottom-0 p-8 text-white">
                                <div
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-tertiary/20 backdrop-blur-md border border-tertiary-fixed-dim/30 text-tertiary-fixed-dim text-xs font-bold mb-4">
                                    <span class="material-symbols-outlined text-xs mr-1">verified</span> Recomendado
                                </div>
                                <h3 class="text-2xl font-bold mb-2">{{ suggestedProducts[0].name }}</h3>
                                <p class="text-white/80 text-sm mb-6 max-w-sm">{{ suggestedProducts[0].description }}
                                </p>
                                <NuxtLink to="/cliente/explore"
                                    class="bg-primary text-white px-6 py-2.5 rounded-lg font-bold text-sm active:scale-95 transition-transform inline-block">
                                    Ver más</NuxtLink>
                            </div>
                        </div>
                    </div>

                    <!-- Otros productos sugeridos -->
                    <div v-for="p in suggestedProducts.slice(1)" :key="p.id"
                        class="bg-surface-container-lowest rounded-xl p-4 shadow-[0px_12px_32px_rgba(13,28,46,0.06)] border border-outline-variant/10 flex flex-col group">
                        <div class="aspect-video mb-4 overflow-hidden rounded-lg bg-surface-container-low">
                            <img :alt="p.name"
                                class="w-full h-full object-cover transition-transform group-hover:scale-110"
                                :src="p.image" />
                        </div>
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-on-surface truncate">{{ p.name }}</h4>
                            <span class="text-primary font-bold">{{ formatPrice(p.price) }}</span>
                        </div>
                        <p class="text-xs text-on-surface-variant mb-4 line-clamp-1">{{ p.description }}</p>
                        <button
                            class="mt-auto w-full py-2 rounded-lg bg-secondary-container text-on-secondary-container font-semibold text-xs hover:opacity-80 transition-opacity">Añadir
                            al carrito</button>
                    </div>
                </div>
            </section>
        </main>

        <footer
            class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center h-16 bg-[#ffffff] dark:bg-[#0d1c2e] shadow-[0px_-4px_16px_rgba(13,28,46,0.04)] px-4 pb-safe z-50 rounded-t-2xl border-t border-[#c3c5d9]/20">
            <NuxtLink
                class="flex flex-col items-center justify-center text-[#434656] dark:text-[#c3c5d9] hover:opacity-80"
                to="/cliente/main">
                <span class="material-symbols-outlined">home</span>
                <span class="text-[11px] font-medium font-['Inter']">Inicio</span>
            </NuxtLink>
            <NuxtLink
                class="flex flex-col items-center justify-center text-[#003ec7] dark:text-[#0052ff] bg-[#d3e4fe] dark:bg-[#003ec7]/20 rounded-xl px-3 py-1 scale-110 transition-transform"
                to="/cliente/explore">
                <span class="material-symbols-outlined">search</span>
                <span class="text-[11px] font-medium font-['Inter']">Explorar</span>
            </NuxtLink>
            <NuxtLink
                class="flex flex-col items-center justify-center text-[#434656] dark:text-[#c3c5d9] hover:opacity-80"
                to="/favoritos">
                <span class="material-symbols-outlined">favorite</span>
                <span class="text-[11px] font-medium font-['Inter']">Favoritos</span>
            </NuxtLink>
            <NuxtLink
                class="flex flex-col items-center justify-center text-[#434656] dark:text-[#c3c5d9] hover:opacity-80"
                to="/perfil">
                <span class="material-symbols-outlined">person</span>
                <span class="text-[11px] font-medium font-['Inter']">Perfil</span>
            </NuxtLink>
        </footer>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.glass-header {
    backdrop-filter: blur(12px);
}
</style>