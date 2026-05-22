<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import Header from '../../components/cliente/Header.vue';
import { useProductos } from '~/composables/useProductos';

const route = useRoute();
const { obtenerTodosProductos, isLoading } = useProductos();
const { storageUrl } = useApiUrl();

const products = ref([]);
const favorites = ref([]);
const searchQuery = ref(route.query.q || '');
const selectedCategories = ref([]);
const maxPrice = ref(100000);
const selectedStatus = ref('Todos');
const sortBy = ref('Más recientes');

// Categorías dinámicas extraídas de los productos
const dynamicCategories = computed(() => {
    const cats = [...new Set(products.value.map(p => p.category).filter(Boolean))];
    return cats.sort().map(c => ({
        id: c,
        name: c
    }));
});

const fetchProducts = async () => {
    const response = await obtenerTodosProductos();
    if (response.success && response.data?.data) {
        products.value = response.data.data.map(p => ({
            id: p.id_producto,
            name: p.Nombre,
            price: parseFloat(p.Precio),
            description: p.Descripcion,
            category: p.categoria,
            status: p.estado || 'Nuevo',
            image: p.Imagenes
                ? (p.Imagenes.startsWith('http') ? p.Imagenes : `${storageUrl}/${p.Imagenes}`)
                : 'https://placehold.co/600x400?text=Sin+Imagen',
            rating: (Math.random() * (5 - 4) + 4).toFixed(1),
            isFeatured: Math.random() > 0.8 // Algunos destacados aleatoriamente para demo
        }));
    }
};

onMounted(() => {
    fetchProducts();
    // Cargar favoritos de localStorage
    const savedFavs = localStorage.getItem('conectatec_favorites');
    if (savedFavs) favorites.value = JSON.parse(savedFavs);
});

// Sincronizar con la búsqueda del header
watch(() => route.query.q, (newVal) => {
    searchQuery.value = newVal || '';
});

// Lógica de filtrado y ordenamiento
const filteredProducts = computed(() => {
    let result = products.value.filter(p => {
        const matchesSearch = p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            p.description.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesCategory = selectedCategories.value.length === 0 || selectedCategories.value.includes(p.category);
        const matchesPrice = p.price <= maxPrice.value;
        const matchesStatus = selectedStatus.value === 'Todos' || p.status === selectedStatus.value;

        return matchesSearch && matchesCategory && matchesPrice && matchesStatus;
    });

    // Ordenamiento
    if (sortBy.value === 'Menor precio') {
        result.sort((a, b) => a.price - b.price);
    } else if (sortBy.value === 'Mayor precio') {
        result.sort((a, b) => b.price - a.price);
    } else if (sortBy.value === 'Popularidad') {
        result.sort((a, b) => b.rating - a.rating);
    }

    return result;
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(price);
};

const resetFilters = () => {
    selectedCategories.value = [];
    maxPrice.value = 100000;
    selectedStatus.value = 'Todos';
    searchQuery.value = '';
};

const toggleFavorite = (productId) => {
    const index = favorites.value.indexOf(productId);
    if (index === -1) {
        favorites.value.push(productId);
    } else {
        favorites.value.splice(index, 1);
    }
    localStorage.setItem('conectatec_favorites', JSON.stringify(favorites.value));
};

const isFavorite = (productId) => favorites.value.includes(productId);
</script>

<template>
    <Header />
    <div class="bg-background text-on-surface min-h-screen pb-20 md:pb-0 pt-16">



        <main class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
            <aside class="lg:col-span-3 space-y-6">
                <div class="sticky top-24 space-y-6">

                    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.04)]">
                        <h2 class="text-on-surface font-bold text-lg mb-4 tracking-tight">Búsqueda</h2>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline"
                                data-icon="search">search</span>
                            <input v-model="searchQuery"
                                class="w-full pl-10 pr-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary text-body-md transition-all"
                                placeholder="¿Qué estás buscando?" type="text" />
                        </div>
                    </div>

                    <div
                        class="bg-surface-container-lowest p-6 rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.04)] space-y-6">
                        <div>
                            <h3 class="text-on-surface font-semibold text-sm mb-3 uppercase tracking-wider">Categoría
                            </h3>
                            <div class="space-y-2">
                                <label v-for="cat in dynamicCategories" :key="cat.id"
                                    class="flex items-center space-x-3 cursor-pointer group">
                                    <input v-model="selectedCategories" :value="cat.id"
                                        class="rounded text-primary focus:ring-primary border-outline-variant"
                                        type="checkbox" />
                                    <span class="text-on-surface-variant text-sm group-hover:text-primary">{{ cat.name
                                        }}</span>
                                </label>
                                <div v-if="dynamicCategories.length === 0"
                                    class="text-xs text-on-surface-variant italic">
                                    Cargando categorías...
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-on-surface font-semibold text-sm mb-3 uppercase tracking-wider">Precio</h3>
                            <div class="space-y-4">
                                <input v-model="maxPrice"
                                    class="w-full h-2 bg-surface-container-low rounded-lg appearance-none cursor-pointer accent-primary"
                                    max="100000" min="0" step="500" type="range" />
                                <div class="flex justify-between text-xs font-medium text-on-surface-variant">
                                    <span>$0</span>
                                    <span>{{ formatPrice(maxPrice) }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-on-surface font-semibold text-sm mb-3 uppercase tracking-wider">Estado</h3>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="status in ['Todos', 'Nuevo', 'Usado', 'Reacondicionado']" :key="status"
                                    @click="selectedStatus = status" :class="[
                                        'px-4 py-1.5 rounded-full text-xs font-medium transition-colors',
                                        selectedStatus === status ? 'bg-primary text-on-primary' : 'bg-secondary-fixed text-on-secondary-fixed hover:bg-secondary-container'
                                    ]">
                                    {{ status }}
                                </button>
                            </div>
                        </div>
                        <button @click="resetFilters"
                            class="w-full py-3 border-2 border-primary text-primary font-bold rounded-lg hover:bg-primary/5 active:scale-95 transition-all">
                            Limpiar Filtros
                        </button>
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-9 space-y-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface">Explorar Productos</h1>
                        <p class="text-on-surface-variant mt-1">Mostrando {{ filteredProducts.length }} resultados</p>
                    </div>
                    <div class="flex items-center bg-surface-container-lowest px-4 py-2 rounded-lg shadow-sm">
                        <span class="text-sm text-on-surface-variant mr-3">Ordenar por:</span>
                        <select v-model="sortBy"
                            class="bg-transparent border-none text-sm font-semibold focus:ring-0 text-primary cursor-pointer">
                            <option>Más recientes</option>
                            <option>Menor precio</option>
                            <option>Mayor precio</option>
                            <option>Popularidad</option>
                        </select>
                    </div>
                </div>

                <div v-if="isLoading" class="flex flex-col items-center justify-center py-20 gap-4">
                    <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                    <p class="text-on-surface-variant font-medium">Cargando catálogo...</p>
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
                            No hay coincidencias para tu selección actual. Intenta ajustar los filtros o el buscador.
                        </p>
                        <div class="mt-4">
                            <button
                                @click="selectedCategories = []; maxPrice = 100000; selectedStatus = 'Todos'; searchQuery = ''"
                                class="px-8 py-3 bg-primary text-on-primary rounded-xl font-bold shadow-lg hover:shadow-primary/20 transition-all active:scale-95">
                                Ver todo el catálogo
                            </button>
                        </div>
                    </section>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                    <!-- Producto Destacado (el primero de la lista filtrada) -->
                    <div v-if="filteredProducts[0]"
                        @click="navigateTo({ path: '/cliente/detalle', query: { id: filteredProducts[0].id } })"
                        class="md:col-span-2 bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(13,28,46,0.04)] group flex flex-col md:flex-row border-2 border-tertiary/10 cursor-pointer">
                        <div class="md:w-1/2 relative overflow-hidden">
                            <img class="w-full h-64 md:h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                :src="filteredProducts[0].image" />
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span
                                    class="bg-tertiary text-on-tertiary text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Destacado</span>
                                <button @click.stop="toggleFavorite(filteredProducts[0].id)" :class="[
                                    'p-1.5 rounded-full backdrop-blur shadow-sm transition-colors',
                                    isFavorite(filteredProducts[0].id) ? 'bg-red-500 text-white' : 'bg-white/80 text-on-surface-variant hover:text-red-500'
                                ]">
                                    <span class="material-symbols-outlined text-sm filled-icon"
                                        v-if="isFavorite(filteredProducts[0].id)">favorite</span>
                                    <span class="material-symbols-outlined text-sm" v-else>favorite</span>
                                </button>
                            </div>
                        </div>
                        <div class="p-8 flex flex-col justify-center space-y-4 md:w-1/2">
                            <div class="flex items-center text-tertiary font-bold text-xs uppercase tracking-widest">
                                <span class="material-symbols-outlined text-sm mr-1">verified</span>
                                Vendedor Verificado
                            </div>
                            <h3 class="text-2xl font-bold text-on-surface leading-tight">{{ filteredProducts[0].name }}
                            </h3>
                            <p class="text-on-surface-variant text-sm line-clamp-3">{{ filteredProducts[0].description
                                }}</p>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-3xl font-extrabold text-primary">{{
                                    formatPrice(filteredProducts[0].price) }}</span>
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button
                                    @click.stop="navigateTo({ path: '/cliente/detalle', query: { id: filteredProducts[0].id } })"
                                    class="flex-1 bg-primary text-on-primary py-3 rounded-lg font-bold hover:opacity-90 transition-all active:scale-95">Comprar
                                    ahora</button>
                            </div>
                        </div>
                    </div>

                    <!-- Resto de productos -->
                    <div v-for="(product, index) in filteredProducts.slice(1)" :key="product.id"
                        @click="navigateTo({ path: '/cliente/detalle', query: { id: product.id } })"
                        class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(13,28,46,0.04)] group border-b-4 border-transparent hover:border-primary transition-all duration-300 cursor-pointer">
                        <div class="aspect-square relative overflow-hidden bg-surface-container-low">
                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                :src="product.image" />
                            <button @click.stop="toggleFavorite(product.id)" :class="[
                                'absolute top-3 right-3 p-2 rounded-full backdrop-blur transition-colors',
                                isFavorite(product.id) ? 'bg-red-500 text-white' : 'bg-white/80 text-on-surface-variant hover:text-red-500'
                            ]">
                                <span class="material-symbols-outlined text-xl filled-icon"
                                    v-if="isFavorite(product.id)">favorite</span>
                                <span class="material-symbols-outlined text-xl" v-else>favorite</span>
                            </button>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] font-bold text-outline uppercase tracking-widest">{{
                                        product.category }}</span>
                                    <h4 class="font-bold text-on-surface mt-1 line-clamp-1">{{ product.name }}</h4>
                                </div>
                                <span :class="[
                                    'text-[10px] px-2 py-0.5 rounded font-bold',
                                    product.status === 'Nuevo' ? 'bg-green-100 text-green-700' : 'bg-secondary-fixed text-on-secondary-fixed'
                                ]">{{ product.status }}</span>
                            </div>
                            <div class="text-xl font-extrabold text-primary">{{ formatPrice(product.price) }}</div>
                            <button @click.stop="navigateTo({ path: '/cliente/detalle', query: { id: product.id } })"
                                class="w-full py-2 bg-surface-container-high text-on-surface font-semibold rounded-lg hover:bg-primary hover:text-on-primary transition-all">Ver
                                detalles</button>
                        </div>
                    </div>

                    <div
                        class="md:col-span-3 bg-primary overflow-hidden rounded-xl relative p-8 flex flex-col md:flex-row items-center justify-between shadow-xl">
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                                <path d="M0 100 L100 0 L100 100 Z" fill="white"></path>
                            </svg>
                        </div>
                        <div class="relative z-10 text-center md:text-left">
                            <h2 class="text-on-primary text-2xl font-black italic tracking-tighter">¿TIENES ALGO QUE
                                VENDER?</h2>
                            <p class="text-on-primary-container font-medium mt-1 opacity-90">Únete a nuestra comunidad
                                de 10,000+ vendedores técnicos.</p>
                        </div>
                        <div class="relative z-10 mt-6 md:mt-0">
                            <button
                                class="bg-on-primary text-primary px-8 py-3 rounded-lg font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg">Publicar
                                Gratis</button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center items-center space-x-2 pt-8">
                    <button
                        class="p-2 rounded-lg bg-surface-container-low text-on-surface-variant hover:bg-primary-fixed transition-colors">
                        <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                    </button>
                    <button class="w-10 h-10 rounded-lg bg-primary text-on-primary font-bold">1</button>
                    <button
                        class="w-10 h-10 rounded-lg bg-surface-container-low text-on-surface font-bold hover:bg-primary-fixed transition-colors">2</button>
                    <button
                        class="w-10 h-10 rounded-lg bg-surface-container-low text-on-surface font-bold hover:bg-primary-fixed transition-colors">3</button>
                    <span class="text-on-surface-variant px-2">...</span>
                    <button
                        class="w-10 h-10 rounded-lg bg-surface-container-low text-on-surface font-bold hover:bg-primary-fixed transition-colors">12</button>
                    <button
                        class="p-2 rounded-lg bg-surface-container-low text-on-surface-variant hover:bg-primary-fixed transition-colors">
                        <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                    </button>
                </div>
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
    </div>
</template>

<style scoped>
/* Pegamos los estilos base que tenías en el head */
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    display: inline-block;
    line-height: 1;
    text-transform: none;
    letter-spacing: normal;
    word-wrap: normal;
    white-space: nowrap;
    direction: ltr;
}

body {
    font-family: 'Inter', sans-serif;
}

.glass-effect {
    backdrop-filter: blur(20px);
}
</style>