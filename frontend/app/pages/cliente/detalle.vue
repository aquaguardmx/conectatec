<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductos } from '~/composables/useProductos';
import { useCarrito } from '~/composables/useCarrito';
import Header from '../../components/cliente/Header.vue';

const route = useRoute();
const router = useRouter();
const { obtenerProductoPublico, obtenerTodosProductos, isLoading } = useProductos();
const { agregarAlCarrito, isLoading: isCartLoading } = useCarrito();

const product = ref<any>(null);
const cantidadSeleccionada = ref(1);

const decreaseQuantity = () => {
    if (cantidadSeleccionada.value > 1) {
        cantidadSeleccionada.value--;
    }
};

const increaseQuantity = () => {
    if (product.value) {
        // Removemos el límite de stock en el botón para permitir probar la pantalla de error
        cantidadSeleccionada.value++;
    }
};

onMounted(async () => {
    const id = route.query.id;
    if (id) {
        let foundProduct = null;
        const response = await obtenerProductoPublico(id as string);

        if (response.success && response.data) {
            foundProduct = response.data.data || response.data;
        } else {
            // Fallback en caso de que el endpoint individual requiera auth o no funcione
            const allResponse = await obtenerTodosProductos();
            if (allResponse.success) {
                const allProducts = allResponse.data?.data || allResponse.data;
                if (Array.isArray(allProducts)) {
                    foundProduct = allProducts.find((p: any) => p.id_producto == id);
                }
            }
        }

        if (foundProduct) {
            product.value = foundProduct;
            if (product.value.Imagenes) {
                product.value.image = product.value.Imagenes.startsWith('http')
                    ? product.value.Imagenes
                    : `http://127.0.0.1:8000/storage/${product.value.Imagenes}`;
            } else {
                product.value.image = 'https://placehold.co/600x400?text=Sin+Imagen';
            }
            product.value.rating = (Math.random() * (5 - 4) + 4).toFixed(1);
        }
    }
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(price);
};

const handleAddToCart = async () => {
    if (!product.value) return;
    
    const stockDisponible = parseInt(product.value.Stock || product.value.stock || 99);
    
    // Si la cantidad seleccionada supera el stock, redirigimos a la pantalla de error
    if (cantidadSeleccionada.value > stockDisponible) {
        router.push({ path: '/cliente/error-stock', query: { id: product.value.id_producto } });
        return;
    }

    // Agregamos al carrito usando el API con la cantidad seleccionada
    const response = await agregarAlCarrito(product.value.id_producto, cantidadSeleccionada.value, product.value.Nombre);
    
    if (response.success) {
        // Si fue exitoso, redirigimos a la página de confirmación pasándole el ID
        router.push({ path: '/cliente/carrito-confirmacion', query: { id: product.value.id_producto } });
    } else {
        alert('Hubo un error al agregar al carrito');
    }
};
const handleBuyNow = async () => {
    if (!product.value) return;
    
    const stockDisponible = parseInt(product.value.Stock || product.value.stock || 99);
    if (cantidadSeleccionada.value > stockDisponible) {
        router.push({ path: '/cliente/error-stock', query: { id: product.value.id_producto } });
        return;
    }

    const response = await agregarAlCarrito(product.value.id_producto, cantidadSeleccionada.value, product.value.Nombre);
    
    if (response.success) {
        router.push('/cliente/seleccionar-entrega');
    } else {
        alert('Hubo un error al procesar tu solicitud');
    }
};
</script>

<template>
    <Header />
    <div
        class="bg-background text-on-background font-body selection:bg-primary-container selection:text-on-primary-container min-h-screen pb-20 md:pb-0">

        <main class="pt-32 pb-24 px-6 md:px-12 max-w-[1440px] mx-auto">
            <div v-if="isLoading" class="flex flex-col items-center justify-center py-20 gap-4">
                <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <p class="text-on-surface-variant font-medium">Cargando producto...</p>
            </div>

            <div v-else-if="!product" class="flex flex-col items-center justify-center py-20 gap-4">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4">inventory_2</span>
                <p class="text-on-surface-variant font-medium text-xl">Producto no encontrado</p>
                <NuxtLink to="/cliente/main" class="px-6 py-2 bg-primary text-white rounded-lg font-bold mt-4">Volver al
                    inicio</NuxtLink>
            </div>

            <div v-else>
                <div class="flex items-center space-x-2 text-sm text-on-surface-variant mb-8">
                    <NuxtLink to="/cliente/main" class="hover:text-primary">Marketplace</NuxtLink>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span>{{ product.categoria || 'Categoría' }}</span>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span class="text-primary font-medium">{{ product.Nombre }}</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                    <div class="lg:col-span-7 space-y-6">
                        <div
                            class="bg-surface-container-lowest rounded-xl overflow-hidden aspect-[4/3] flex items-center justify-center relative group">
                            <img :alt="product.Nombre" class="w-full h-full object-cover" :src="product.image" />
                            <div v-if="product.vendedor"
                                class="absolute top-4 right-4 bg-tertiary text-on-tertiary px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 shadow-lg">
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">verified</span>
                                Verified Merchant
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            <div
                                class="bg-surface-container-lowest rounded-lg overflow-hidden border-2 border-primary aspect-square">
                                <img :alt="product.Nombre" class="w-full h-full object-cover" :src="product.image" />
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 flex flex-col space-y-8">
                        <div class="space-y-4">
                            <span v-if="product.estado"
                                :class="[product.estado === 'Nuevo' ? 'bg-green-100 text-green-700' : 'bg-secondary-fixed text-on-secondary-fixed', 'px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase']">
                                {{ product.estado }}
                            </span>
                            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter text-on-surface">{{
                                product.Nombre }}</h1>

                            <div class="flex items-center space-x-4">
                                <div class="flex items-center text-primary">
                                    <span class="material-symbols-outlined"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined">star_half</span>
                                </div>
                                <span class="text-on-surface-variant text-sm font-medium">{{ product.rating }}
                                    (reviews)</span>
                            </div>
                        </div>

                        <div class="p-6 bg-surface-container-low rounded-xl space-y-4">
                            <div class="flex items-baseline space-x-2">
                                <span class="text-4xl font-black text-primary">{{ formatPrice(product.Precio) }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-tertiary font-semibold mb-4">
                                <span class="material-symbols-outlined">inventory_2</span>
                                <span>In Stock: {{ product.stock || product.Stock || 'Disponible' }}</span>
                            </div>
                            
                            <div class="flex items-center gap-4 py-2">
                                <span class="text-on-surface-variant font-bold text-sm uppercase tracking-wider">Cantidad:</span>
                                <div class="flex items-center bg-surface-container-lowest border border-outline-variant/30 rounded-lg overflow-hidden">
                                    <button @click="decreaseQuantity" class="px-4 py-2 hover:bg-surface-container-high text-on-surface font-bold transition-colors disabled:opacity-50" :disabled="cantidadSeleccionada <= 1">-</button>
                                    <span class="px-4 py-2 font-bold text-on-surface border-x border-outline-variant/30 w-12 text-center">{{ cantidadSeleccionada }}</span>
                                    <button @click="increaseQuantity" class="px-4 py-2 hover:bg-surface-container-high text-on-surface font-bold transition-colors">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 pt-4">
                            <button @click="handleBuyNow" :disabled="isCartLoading"
                                class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary py-5 rounded-xl font-bold text-lg shadow-[0px_12px_32px_rgba(0,62,199,0.2)] hover:scale-[1.02] transition-transform active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="isCartLoading" class="w-5 h-5 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
                                <span v-else class="material-symbols-outlined">bolt</span>
                                {{ isCartLoading ? 'Procesando...' : 'Buy Now' }}
                            </button>
                            <button @click="handleAddToCart" :disabled="isCartLoading"
                                class="w-full bg-secondary-container text-on-secondary-container py-5 rounded-xl font-bold text-lg hover:bg-surface-container-high transition-colors active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="isCartLoading" class="w-5 h-5 border-2 border-on-secondary-container border-t-transparent rounded-full animate-spin"></span>
                                <span v-else class="material-symbols-outlined">add_shopping_cart</span>
                                {{ isCartLoading ? 'Agregando...' : 'Add to Cart' }}
                            </button>
                        </div>

                        <p class="text-on-surface-variant text-sm text-center">Free express shipping for university
                            members.
                        </p>
                    </div>
                </div>

                <div class="mt-24 grid grid-cols-1 lg:grid-cols-12 gap-16">
                    <div class="lg:col-span-8 space-y-12">
                        <section>
                            <h2 class="text-3xl font-bold text-on-surface mb-6 tracking-tight">Product Description</h2>
                            <p class="text-on-surface-variant leading-relaxed text-lg whitespace-pre-wrap">{{
                                product.Descripcion || 'Sin descripción disponible.' }}</p>
                        </section>

                        <section>
                            <h2 class="text-3xl font-bold text-on-surface mb-6 tracking-tight">Technical Specifications
                            </h2>
                            <div
                                class="bg-surface-container-lowest rounded-xl overflow-hidden divide-y divide-outline-variant/10">
                                <div class="grid grid-cols-3 p-4">
                                    <span class="text-on-surface-variant font-medium">Nombre</span>
                                    <span class="col-span-2 text-on-surface font-semibold">{{ product.Nombre }}</span>
                                </div>
                                <div class="grid grid-cols-3 p-4">
                                    <span class="text-on-surface-variant font-medium">Categoría</span>
                                    <span class="col-span-2 text-on-surface font-semibold">{{ product.categoria || 'N/A'
                                        }}</span>
                                </div>
                                <div class="grid grid-cols-3 p-4">
                                    <span class="text-on-surface-variant font-medium">Estado</span>
                                    <span class="col-span-2 text-on-surface font-semibold">{{ product.estado || 'N/A'
                                        }}</span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="lg:col-span-4 space-y-8" v-if="product.vendedor">
                        <div
                            class="bg-surface-container-lowest p-8 rounded-2xl shadow-sm border border-outline-variant/5">
                            <h3 class="font-bold text-xl mb-6">Seller Information</h3>
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center text-primary font-black text-xl overflow-hidden">
                                    <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(product.vendedor.nombre)}&background=random`"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface">{{ product.vendedor.nombre }}</p>
                                    <p class="text-sm text-on-surface-variant">Vendedor Verificado</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-on-surface-variant">Success Rate</span>
                                    <span class="text-tertiary font-bold">99.8%</span>
                                </div>
                                <div class="w-full bg-surface-container h-1 rounded-full">
                                    <div class="bg-tertiary h-1 w-[99.8%] rounded-full"></div>
                                </div>
                                <button
                                    class="w-full py-3 text-primary font-bold text-sm hover:bg-primary/5 rounded-lg transition-colors">Contact
                                    Seller</button>
                            </div>
                        </div>

                        <div class="bg-primary p-8 rounded-2xl text-on-primary relative overflow-hidden">
                            <div class="relative z-10">
                                <span class="material-symbols-outlined text-4xl mb-4">security</span>
                                <h3 class="font-bold text-xl mb-2">Buyer Protection</h3>
                                <p class="text-on-primary-container text-sm leading-relaxed">Your purchase is protected
                                    from
                                    payment to delivery. Full refund if item is not as described.</p>
                            </div>
                            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <nav
            class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-white dark:bg-[#0d1c2e] border-t border-[#eff4ff]/20 dark:border-[#1a2b42]/20 shadow-[0_-8px_24px_rgba(13,28,46,0.04)] pb-safe">
            <div class="flex justify-around items-center px-4 py-3">
                <NuxtLink to="/cliente/explore"
                    class="flex flex-col items-center justify-center text-[#434656] dark:text-[#a0a4b8] opacity-70 hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined">grid_view</span>
                    <span class="text-[11px] font-semibold font-['Inter'] uppercase tracking-wider mt-1">Explore</span>
                </NuxtLink>
                <button
                    class="flex flex-col items-center justify-center text-[#434656] dark:text-[#a0a4b8] opacity-70 hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined">search</span>
                    <span class="text-[11px] font-semibold font-['Inter'] uppercase tracking-wider mt-1">Search</span>
                </button>
                <button
                    class="flex flex-col items-center justify-center text-[#434656] dark:text-[#a0a4b8] opacity-70 hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    <span class="text-[11px] font-semibold font-['Inter'] uppercase tracking-wider mt-1">Cart</span>
                </button>
                <NuxtLink to="/perfil"
                    class="flex flex-col items-center justify-center text-[#434656] dark:text-[#a0a4b8] opacity-70 hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined">person</span>
                    <span class="text-[11px] font-semibold font-['Inter'] uppercase tracking-wider mt-1">Profile</span>
                </NuxtLink>
            </div>
        </nav>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.backdrop-blur-xl {
    backdrop-filter: blur(20px);
}
</style>