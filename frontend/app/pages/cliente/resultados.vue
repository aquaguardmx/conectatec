<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import Header from '../../components/cliente/Header.vue';
import { useProductos } from '~/composables/useProductos';

const route = useRoute();
const { obtenerTodosProductos, isLoading } = useProductos();
const { storageUrl } = useApiUrl();

const products = ref([]);
const searchQuery = ref(route.query.q || '');
const selectedCategories = ref<string[]>([]);
const maxPrice = ref(100000);
const selectedStatus = ref('Todos');
const sortBy = ref('Relevancia');
const showFilters = ref(false);

const dynamicCategories = computed(() => {
    const cats = [...new Set(products.value.map((p: any) => p.category).filter(Boolean))];
    return cats.sort();
});

const fetchProducts = async () => {
    const response = await obtenerTodosProductos();
    if (response.success && response.data?.data) {
        products.value = response.data.data.map((p: any) => ({
            id: p.id_producto,
            name: p.Nombre || 'Sin nombre',
            price: parseFloat(p.Precio) || 0,
            description: p.Descripcion || '',
            category: p.categoria || 'Sin categoría',
            status: p.estado || 'Nuevo',
            image: p.Imagenes
                ? (p.Imagenes.startsWith('http') ? p.Imagenes : `${storageUrl}/${p.Imagenes}`)
                : 'https://placehold.co/600x400?text=Sin+Imagen',
            rating: parseFloat((Math.random() * (5 - 4.5) + 4.5).toFixed(1)),
            reviews: Math.floor(Math.random() * 200) + 10
        }));
    }
};

onMounted(() => {
    fetchProducts();
});

// Sincronizar con el cambio de query en la URL
watch(() => route.query.q, (newVal) => {
    searchQuery.value = newVal as string || '';
});

const filteredProducts = computed(() => {
    let result = products.value.filter((p: any) => {
        const q = (searchQuery.value || '').toLowerCase();
        const matchesSearch = !q ||
            (p.name || '').toLowerCase().includes(q) ||
            (p.description || '').toLowerCase().includes(q) ||
            (p.category || '').toLowerCase().includes(q);

        const matchesCategory = selectedCategories.value.length === 0 || selectedCategories.value.includes(p.category);
        const matchesPrice = p.price <= maxPrice.value;
        const matchesStatus = selectedStatus.value === 'Todos' || p.status === selectedStatus.value;

        return matchesSearch && matchesCategory && matchesPrice && matchesStatus;
    });

    // Ordenamiento (copia para no mutar)
    const sorted = [...result];
    if (sortBy.value === 'Menor precio') {
        sorted.sort((a, b) => a.price - b.price);
    } else if (sortBy.value === 'Mayor precio') {
        sorted.sort((a, b) => b.price - a.price);
    } else if (sortBy.value === 'Mejor calificados') {
        sorted.sort((a, b) => b.rating - a.rating);
    }

    return sorted;
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(price);
};
</script>

<template>
    <div class="bg-background text-on-surface antialiased min-h-screen">
        <Header />

        <main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
            <section class="mb-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Resultados para "{{
                            searchQuery }}"</h1>
                        <p class="text-on-surface-variant font-medium">Se encontraron <span class="text-primary">{{
                            filteredProducts.length }}</span> productos disponibles</p>
                    </div>

                    <div class="flex flex-wrap gap-3 overflow-x-auto pb-2 no-scrollbar">
                        <button @click="showFilters = !showFilters" :class="[
                            'flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold shadow-lg transition-all active:scale-95',
                            showFilters ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-highest'
                        ]">
                            <span class="material-symbols-outlined text-[20px]">tune</span>
                            {{ showFilters ? 'Ocultar Filtros' : 'Filtrar' }}
                        </button>

                        <div
                            class="flex items-center gap-2 px-5 py-2 bg-surface-container-high rounded-full text-sm font-bold border border-outline-variant/10">
                            <span class="text-on-surface-variant font-medium">Ordenar:</span>
                            <select v-model="sortBy"
                                class="bg-transparent border-none text-sm font-bold focus:ring-0 p-0 pr-6 text-primary cursor-pointer">
                                <option>Relevancia</option>
                                <option>Menor precio</option>
                                <option>Mayor precio</option>
                                <option>Mejor calificados</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Panel de Filtros Expandible -->
                <transition enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform -translate-y-4 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-4 opacity-0">
                    <div v-if="showFilters"
                        class="mt-6 p-8 bg-surface-container-low rounded-2xl border border-outline-variant/10 grid grid-cols-1 md:grid-cols-3 gap-8 shadow-inner">
                        <div class="space-y-4">
                            <h3 class="text-xs font-black uppercase tracking-widest text-primary">Categorías</h3>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="cat in dynamicCategories" :key="cat"
                                    @click="selectedCategories.includes(cat) ? selectedCategories = selectedCategories.filter(c => c !== cat) : selectedCategories.push(cat)"
                                    :class="[
                                        'px-3 py-1.5 rounded-lg text-xs font-bold border transition-all',
                                        selectedCategories.includes(cat) ? 'bg-primary border-primary text-on-primary' : 'bg-surface border-outline-variant/30 text-on-surface-variant hover:border-primary'
                                    ]">
                                    {{ cat }}
                                </button>
                                <div v-if="dynamicCategories.length === 0"
                                    class="text-xs italic text-on-surface-variant opacity-50">Cargando...</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black uppercase tracking-widest text-primary">Precio Máximo</h3>
                            <div class="space-y-3">
                                <input v-model="maxPrice" type="range" min="0" max="100000" step="1000"
                                    class="w-full h-2 bg-surface-container-highest rounded-lg appearance-none cursor-pointer accent-primary" />
                                <div class="flex justify-between text-xs font-bold text-on-surface">
                                    <span>$0</span>
                                    <span class="text-primary">{{ formatPrice(maxPrice) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black uppercase tracking-widest text-primary">Estado</h3>
                            <div class="flex gap-2">
                                <button v-for="status in ['Todos', 'Nuevo', 'Usado', 'Reacondicionado']" :key="status"
                                    @click="selectedStatus = status" :class="[
                                        'flex-1 py-2 rounded-lg text-xs font-bold border transition-all',
                                        selectedStatus === status ? 'bg-primary border-primary text-on-primary' : 'bg-surface border-outline-variant/30 text-on-surface-variant hover:border-primary'
                                    ]">
                                    {{ status }}
                                </button>
                            </div>
                            <button @click="selectedCategories = []; maxPrice = 100000; selectedStatus = 'Todos'"
                                class="w-full py-2 text-xs font-black text-error uppercase tracking-widest hover:underline">Limpiar
                                Todo</button>
                        </div>
                    </div>
                </transition>
            </section>

            <div class="bento-grid">
                <div v-if="isLoading" class="col-span-full flex flex-col items-center justify-center py-20 gap-4">
                    <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                    <p class="text-on-surface-variant font-medium">Buscando productos...</p>
                </div>

                <div v-else-if="filteredProducts.length === 0" class="col-span-full">
                    <section class="flex flex-col items-center justify-center text-center py-12 px-4">
                        <div class="relative mb-8">
                            <div class="absolute inset-0 bg-primary/5 rounded-full blur-3xl scale-150"></div>
                            <div
                                class="relative w-48 h-48 md:w-64 md:h-64 mx-auto bg-surface-container-lowest rounded-full p-6 flex items-center justify-center shadow-lg border border-outline-variant/10">
                                <img alt="No results found" class="w-full h-full object-contain rounded-full opacity-90"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuC2iB9rHEaK8i-dpwksN4ZMK8UOlAGY-VLsqtWepvWufxv4RSvKEhOH21Fwmfvi1-eSQ5hrWrE-Xqbu7Zbfrdgxltw-MjZiJCI2D2sKQhJAfazSDMKtkwEqvuRpZ69nVjznbT5JD1EjneYl8VvtapNwMiX2kZ__3yuqUHgT0ApaOMjDGD8RiHgSj6JmS4urP3Q1NsDuEoxGxnHmiCgCKP28V2OzVv1iHUo3MSejQRrlc5EFgv5z47nsdbo1qln44kAp6Z7ku1y40NU" />
                            </div>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-4">No
                            encontramos lo que buscas</h2>
                        <p class="text-on-surface-variant text-base max-w-lg mx-auto mb-10 leading-relaxed">
                            No hay coincidencias para "{{ searchQuery }}". Intenta ajustar tus términos o quitar algunos
                            filtros para encontrar el equipo perfecto.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-4xl">
                            <div
                                class="bg-surface-container-low p-6 rounded-xl text-left border border-outline-variant/10">
                                <span class="material-symbols-outlined text-primary mb-3">spellcheck</span>
                                <h3 class="font-bold text-on-surface mb-2 text-sm">Revisa la ortografía</h3>
                                <p class="text-xs text-on-surface-variant">Un pequeño error tipográfico puede cambiarlo
                                    todo. Verifica los nombres técnicos.</p>
                            </div>
                            <div
                                class="bg-surface-container-low p-6 rounded-xl text-left border border-outline-variant/10">
                                <span class="material-symbols-outlined text-primary mb-3">public</span>
                                <h3 class="font-bold text-on-surface mb-2 text-sm">Términos generales</h3>
                                <p class="text-xs text-on-surface-variant">En lugar de un modelo específico, intenta
                                    buscar por categoría como "Laptop" o "Monitor".</p>
                            </div>
                            <div
                                class="bg-surface-container-low p-6 rounded-xl text-left border border-outline-variant/10">
                                <span class="material-symbols-outlined text-primary mb-3">filter_alt_off</span>
                                <h3 class="font-bold text-on-surface mb-2 text-sm">Quita los filtros</h3>
                                <p class="text-xs text-on-surface-variant">Si aplicaste muchos filtros, intenta
                                    eliminarlos para ampliar el rango de búsqueda.</p>
                            </div>
                        </div>

                        <div class="mt-12">
                            <button
                                @click="selectedCategories = []; maxPrice = 100000; selectedStatus = 'Todos'; searchQuery = ''"
                                class="px-8 py-3 bg-primary text-on-primary rounded-xl font-bold shadow-lg hover:shadow-primary/20 transition-all active:scale-95">
                                Limpiar búsqueda y filtros
                            </button>
                        </div>
                    </section>
                </div>

                <div v-else v-for="product in filteredProducts" :key="product.id"
                    class="group bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_4px_16px_rgba(13,28,46,0.04)] hover:shadow-[0px_12px_32px_rgba(13,28,46,0.08)] transition-all duration-300 flex flex-col">
                    <div class="relative aspect-square overflow-hidden bg-surface-container-low">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            :src="product.image" />
                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            <span
                                class="px-2.5 py-1 bg-tertiary-container text-white text-[10px] font-bold rounded-md uppercase tracking-wider">{{
                                    product.status }}</span>
                        </div>
                        <button
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-primary shadow-sm hover:bg-white transition-colors">
                            <span class="material-symbols-outlined text-[20px]">favorite</span>
                        </button>
                    </div>
                    <div class="p-5 flex-grow flex flex-col">
                        <div class="flex items-center gap-1 mb-2">
                            <span class="material-symbols-outlined text-yellow-500 text-[14px]"
                                style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="text-xs font-bold text-on-surface">{{ product.rating }}</span>
                            <span class="text-[10px] text-on-surface-variant">({{ product.reviews }} reseñas)</span>
                        </div>
                        <h3
                            class="text-on-surface font-bold text-lg mb-1 leading-tight group-hover:text-primary transition-colors">
                            {{ product.name }}</h3>
                        <p class="text-xs text-on-surface-variant mb-4 line-clamp-2">{{ product.description }}</p>
                        <div class="mt-auto flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-primary font-extrabold text-xl">{{ formatPrice(product.price)
                                    }}</span>
                            </div>
                            <button
                                class="w-10 h-10 rounded-lg bg-primary text-on-primary flex items-center justify-center hover:bg-primary-container active:scale-95 transition-all shadow-md shadow-primary/10">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 flex items-center justify-center gap-2">
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                </button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-on-primary font-bold">1</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-surface-container-low transition-colors">2</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-surface-container-low transition-colors">3</button>
                <span class="px-2 text-on-surface-variant">...</span>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-surface-container-low transition-colors">12</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                </button>
            </div>
        </main>

        <footer
            class="md:hidden fixed bottom-0 left-0 w-full bg-[#ffffff] dark:bg-[#0d1c2e] flex justify-around items-center h-16 px-4 pb-safe z-50 rounded-t-2xl shadow-[0px_-4px_16px_rgba(13,28,46,0.04)] border-t border-[#c3c5d9]/20">
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

        <div class="fixed bottom-20 md:bottom-8 right-6 z-40">
            <div class="flex items-center gap-3 px-4 py-3 bg-tertiary text-white rounded-full shadow-2xl">
                <span class="material-symbols-outlined text-[20px] text-tertiary-fixed-dim" data-icon="verified_user"
                    style="font-variation-settings: 'FILL' 1;">verified_user</span>
                <span class="text-xs font-bold whitespace-nowrap">Vendedor Verificado</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.glass-header {
    background: rgba(248, 249, 255, 0.8);
    backdrop-filter: blur(20px);
}

.bento-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
}

/* Ocultar la barra de desplazamiento en la sección de filtros (clase 'no-scrollbar') */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    /* IE y Edge */
    scrollbar-width: none;
    /* Firefox */
}
</style>