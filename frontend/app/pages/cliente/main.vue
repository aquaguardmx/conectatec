<template>
    <div
        class="bg-background text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed min-h-screen">

        <ClienteHeader />

        <main class="max-w-7xl mx-auto px-6 py-8">
            <section class="relative overflow-hidden rounded-xl mb-20">
                <div
                    class="relative z-10 grid md:grid-cols-2 gap-12 items-center p-12 lg:p-20 bg-gradient-to-br from-primary to-primary-container text-on-primary">
                    <div class="space-y-6">
                        <span
                            class="inline-block px-4 py-1.5 bg-white/10 rounded-full text-xs font-bold uppercase tracking-widest border border-white/20">Temporada
                            Universitaria</span>
                        <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tighter leading-tight font-headline">
                            Prepárate para el <span class="text-tertiary-fixed">Éxito.</span>
                        </h1>
                        <p class="text-lg text-on-primary-container/80 max-w-md font-medium leading-relaxed">
                            Descuentos exclusivos de hasta el 40% en libros de texto, laptops y suministros esenciales
                            para tu carrera.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <button
                                class="px-8 py-4 bg-surface-container-lowest text-primary font-bold rounded-xl shadow-lg hover:scale-105 active:scale-95 transition-all">
                                Ver Ofertas en Libros
                            </button>
                            <button
                                class="px-8 py-4 bg-transparent border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition-all">
                                Explorar Tecnología
                            </button>
                        </div>
                    </div>
                    <div class="relative hidden md:block group">
                        <div
                            class="absolute -inset-4 bg-white/10 rounded-3xl blur-2xl group-hover:bg-white/20 transition-all">
                        </div>
                        <img alt="Libros y café"
                            class="relative z-10 w-full h-[450px] object-cover rounded-2xl shadow-2xl transform rotate-2 group-hover:rotate-0 transition-transform duration-700"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCGbpZynnQXn8m2nVrF_KZxe2hjOtl_a6hs-xAbCHkaX2G7k93bSugErCjHrSIVlgxOxvpS44ejRNOdeDF9vhsA9O035ll6Ea9NQpIZ3vkOosUPuAlyRaalFD9VCuGdHpDRNMVbGqeHpPo_-6wJYoqxVZ5WIYLi74cMFRVfEZe0h9ND63oqk7VGblS2MGeGfUHNTcMaSsfyizCZGMbeCxz-o82pT6xLzgivEEYTD3lQ4E37kkUejcp76wIRnU2eZaTCOUCakNNnecs" />
                    </div>
                </div>
            </section>

            <section class="mb-20">
                <div class="flex justify-between items-end mb-10">
                    <div class="space-y-2">
                        <h2 class="text-3xl font-bold tracking-tight text-on-surface font-headline">Explorar Categorías
                        </h2>
                        <p class="text-on-surface-variant">Todo lo que un estudiante necesita, organizado para ti.</p>
                    </div>
                    <button class="text-primary font-semibold flex items-center gap-2 hover:gap-3 transition-all">
                        Ver todas <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    <button v-for="category in categories" :key="category.id" @click="selectCategory(category.id)"
                        :class="[
                            'group relative overflow-hidden bg-surface-container-lowest p-6 rounded-xl shadow-sm transition-all border-2 text-left',
                            selectedCategory === category.id ? 'border-primary shadow-lg ring-2 ring-primary/10' : 'border-transparent hover:border-primary/10 hover:shadow-md'
                        ]">
                        <div
                            :class="[category.bgClass, category.textClass, 'w-14 h-14 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform']">
                            <span class="material-symbols-outlined text-3xl">{{ category.icon }}</span>
                        </div>
                        <h3 class="font-bold text-lg mb-1 leading-tight">{{ category.name }}</h3>
                        <p class="text-[10px] text-on-surface-variant line-clamp-1">{{ category.description }}</p>
                    </button>
                </div>
            </section>

            <section class="mb-20">
                <h2 class="text-3xl font-bold tracking-tight text-on-surface font-headline mb-10">Productos Destacados
                </h2>
                <div v-if="isLoading" class="flex flex-col items-center justify-center py-20 gap-4">
                    <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                    <p class="text-on-surface-variant font-medium">Buscando los mejores productos...</p>
                </div>

                <div v-else-if="filteredProducts.length === 0"
                    class="flex flex-col items-center justify-center py-20 text-center">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4">inventory_2</span>
                    <h3 class="text-xl font-bold text-on-surface">No encontramos productos</h3>
                    <p class="text-on-surface-variant mb-6">Prueba seleccionando otra categoría o ajustando tu búsqueda.
                    </p>
                    <button @click="selectedCategory = 'all'"
                        class="px-6 py-2 bg-primary text-white rounded-lg font-bold">Ver todo</button>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="product in filteredProducts" :key="product.id"
                        @click="navigateTo({ path: '/cliente/detalle', query: { id: product.id } })"
                        class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-outline-variant/10 group cursor-pointer">
                        <div class="relative h-64 overflow-hidden">
                            <img :alt="product.name"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                :src="product.image" />
                            <div v-if="product.badge"
                                :class="[product.badgeColor, 'absolute top-4 left-4 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase shadow-lg']">
                                {{ product.badge }}
                            </div>
                            <div
                                class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3
                                    class="font-bold text-lg leading-tight text-on-surface group-hover:text-primary transition-colors">
                                    {{ product.name }}</h3>
                                <span class="font-bold text-primary text-xl">{{ formatPrice(product.price) }}</span>
                            </div>
                            <p class="text-on-surface-variant text-sm mb-6 line-clamp-2 h-10">{{ product.description }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1 text-tertiary">
                                    <span class="material-symbols-outlined text-sm filled-icon">star</span>
                                    <span class="text-xs font-bold">{{ product.rating }}</span>
                                </div>
                                <button @click.stop="addToCart(product)"
                                    class="px-4 py-2 bg-secondary-container text-on-secondary-container text-xs font-bold rounded-lg hover:bg-primary hover:text-white transition-all shadow-sm active:scale-95">
                                    Agregar al carrito
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-surface-container-low p-10 lg:p-16 rounded-2xl mb-20 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight text-on-surface font-headline">Comunidad
                                Verificada</h2>
                            <p class="text-on-surface-variant max-w-md">Compra con confianza a estudiantes y facultades
                                de nuestra propia universidad.</p>
                        </div>
                        <NuxtLink to="/vender"
                            class="px-6 py-3 bg-tertiary text-on-tertiary font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                            Quiero ser vendedor <span class="material-symbols-outlined">verified_user</span>
                        </NuxtLink>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="seller in sellers" :key="seller.id"
                            class="bg-white p-6 rounded-xl flex items-center gap-4 shadow-sm border border-outline-variant/10">
                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                <img :alt="'Perfil ' + seller.name" class="w-full h-full object-cover"
                                    :src="seller.image" />
                            </div>
                            <div>
                                <p class="font-bold text-sm">{{ seller.name }}</p>
                                <div class="flex items-center gap-1">
                                    <span
                                        class="material-symbols-outlined text-tertiary text-xs filled-icon">verified</span>
                                    <span
                                        class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Verificado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
            </section>
        </main>

        <footer class="w-full bg-slate-50 dark:bg-slate-900 mt-20">
            <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 max-w-7xl mx-auto gap-6">
                <div class="flex flex-col gap-2">
                    <span
                        class="text-lg font-bold text-slate-900 dark:text-white font-headline tracking-tight">Conectatec</span>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-body max-w-xs">El punto de encuentro
                        oficial para el comercio universitario seguro y local.</p>
                </div>
                <div class="flex flex-wrap justify-center gap-8">
                    <a class="text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Support</a>
                    <a class="text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Privacy Policy</a>
                    <a class="text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Terms of
                        Service</a>
                    <a class="text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Merchant Portal</a>
                </div>
                <div class="text-sm text-slate-500 dark:text-slate-400 font-body">
                    © {{ new Date().getFullYear() }} Conectatec University Marketplace
                </div>
            </div>
        </footer>

        <nav
            class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-white/90 dark:bg-slate-950/90 backdrop-blur-lg border-t border-slate-100/50 dark:border-slate-800/50 shadow-[0px_-8px_24px_rgba(0,0,0,0.04)] px-4 py-3 pb-safe flex justify-around items-center rounded-t-2xl">
            <NuxtLink v-for="nav in mobileNav" :key="nav.name" :to="nav.route"
                :class="[nav.active ? 'text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-slate-400 dark:text-slate-500 hover:bg-slate-50', 'flex flex-col items-center justify-center rounded-xl px-4 py-2 transition-all']">
                <span class="material-symbols-outlined" :class="{ 'filled-icon': nav.active }">{{ nav.icon }}</span>
                <span class="text-[10px] font-semibold uppercase tracking-wider mt-1">{{ nav.name }}</span>
            </NuxtLink>
        </nav>

        <button
            class="fixed bottom-24 right-6 md:bottom-8 md:right-8 bg-primary text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-40">
            <span class="material-symbols-outlined text-2xl">chat</span>
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import ClienteHeader from '~/components/cliente/Header.vue';
import { useProductos } from '~/composables/useProductos';
import { useCarrito } from '~/composables/useCarrito';

const router = useRouter();
const { obtenerTodosProductos, isLoading } = useProductos();
const { agregarAlCarrito } = useCarrito();
const { storageUrl } = useApiUrl();
const products = ref([]);
const sellers = ref([]);
const selectedCategory = ref('all');
const searchQueryLocal = ref('');

// --- Datos de Categorías (Adaptadas a la BD) ---
const categories = ref([
    { id: 'all', name: 'Todo', description: 'Todo el catálogo', icon: 'apps', bgClass: 'bg-primary/10', textClass: 'text-primary' },
    { id: 'LAPTOPS', name: 'Laptops', description: 'MacBooks y más', icon: 'laptop_mac', bgClass: 'bg-blue-100', textClass: 'text-blue-600' },
    { id: 'Audio', name: 'Audio', description: 'Auriculares y música', icon: 'headphones', bgClass: 'bg-purple-100', textClass: 'text-purple-600' },
    { id: 'RELOJ INTELIGENTE', name: 'Relojes', description: 'Apple Watch y más', icon: 'watch', bgClass: 'bg-orange-100', textClass: 'text-orange-600' },
    { id: 'Tablets', name: 'Tablets', description: 'iPads y accesorios', icon: 'tablet_mac', bgClass: 'bg-green-100', textClass: 'text-green-600' },
    { id: 'HERRAMIENTAS DE DESARROLLO', name: 'Dev Tools', description: 'Software y más', icon: 'code', bgClass: 'bg-slate-100', textClass: 'text-slate-600' }
]);

const fetchProducts = async () => {
    const response = await obtenerTodosProductos();
    if (response.success && response.data?.data) {
        const fetchedProducts = response.data.data;

        products.value = fetchedProducts.map(p => ({
            id: p.id_producto,
            name: p.Nombre,
            price: p.Precio,
            description: p.Descripcion,
            rating: (Math.random() * (5 - 4) + 4).toFixed(1), // Rating aleatorio entre 4 y 5
            category: p.categoria || 'Otros',
            image: p.Imagenes
                ? (p.Imagenes.startsWith('http') ? p.Imagenes : `${storageUrl}/${p.Imagenes}`)
                : 'https://placehold.co/600x400?text=Sin+Imagen',
            badge: p.estado === 'Nuevo' ? 'Nuevo' : (p.estado === 'reacondicionado' ? 'Oferta' : null),
            badgeColor: p.estado === 'Nuevo' ? 'bg-green-500' : 'bg-primary'
        }));

        // Extraer vendedores únicos
        const uniqueSellersMap = new Map();
        fetchedProducts.forEach(p => {
            if (p.vendedor && !uniqueSellersMap.has(p.vendedor.idUsuario)) {
                uniqueSellersMap.set(p.vendedor.idUsuario, {
                    id: p.vendedor.idUsuario,
                    name: p.vendedor.nombre,
                    image: `https://ui-avatars.com/api/?name=${encodeURIComponent(p.vendedor.nombre)}&background=random`
                });
            }
        });

        sellers.value = Array.from(uniqueSellersMap.values()).slice(0, 4);
    }

    // Fallback sellers
    if (sellers.value.length === 0) {
        sellers.value = [
            { id: 1, name: 'Daniel Torres', image: 'https://ui-avatars.com/api/?name=Daniel+Torres&background=random' },
            { id: 2, name: 'Ana Martínez', image: 'https://ui-avatars.com/api/?name=Ana+Martinez&background=random' },
            { id: 3, name: 'Carlos Ruiz', image: 'https://ui-avatars.com/api/?name=Carlos+Ruiz&background=random' },
            { id: 4, name: 'Lucía Méndez', image: 'https://ui-avatars.com/api/?name=Lucia+Mendez&background=random' }
        ];
    }
};

onMounted(() => {
    fetchProducts();
});

// --- Lógica de Filtrado ---
const filteredProducts = computed(() => {
    return products.value.filter(p => {
        const matchesCategory = selectedCategory.value === 'all' || p.category === selectedCategory.value;
        const matchesSearch = p.name.toLowerCase().includes(searchQueryLocal.value.toLowerCase()) ||
            p.description.toLowerCase().includes(searchQueryLocal.value.toLowerCase());
        return matchesCategory && matchesSearch;
    });
});

const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId;
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(price);
};

const addToCart = async (product) => {
    // Llamar a la API del carrito (pasamos el id_producto, cantidad 1 y nombre como selección opcional)
    const response = await agregarAlCarrito(product.id, 1, product.name);
    
    if (response.success) {
        // Redirigir a la pantalla de confirmación
        router.push({ path: '/cliente/carrito-confirmacion', query: { id: product.id } });
    } else {
        // Si hay error (por ejemplo, el usuario no está logueado)
        alert('Hubo un error al agregar el producto al carrito. Inicia sesión si no lo has hecho.');
    }
};

// --- Navegación Móvil ---
const mobileNav = ref([
    { name: 'Home', icon: 'home', route: '/cliente/main', active: true },
    { name: 'Explorar', icon: 'explore', route: '/cliente/explore', active: false },
    { name: 'Pedidos', icon: 'package_2', route: '/cliente/historial-compras', active: false },
    { name: 'Reseñas', icon: 'rate_review', route: '/cliente/review', active: false },
    { name: 'Cart', icon: 'shopping_cart', route: '/cliente/carrito', active: false }
]);
</script>

<style scoped>
/* Configuración global de los iconos para ajustarse al diseño original */
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

/* Clase de utilidad para cuando un ícono necesita estar relleno */
.filled-icon {
    font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

/* Soporte para el padding en dispositivos móviles modernos (ej. iOS con notch) */
@supports (padding-bottom: env(safe-area-inset-bottom)) {
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom);
    }
}
</style>