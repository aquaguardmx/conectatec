<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useCarrito } from '~/composables/useCarrito';
import Header from '../../components/cliente/Header.vue';

const router = useRouter();
const { obtenerCarrito, actualizarCantidad, eliminarDelCarrito, isLoading } = useCarrito();
const { storageUrl } = useApiUrl();

const cartItems = ref<any[]>([]);
const isProcessing = ref(false);

const fetchCart = async () => {
    const response = await obtenerCarrito();
    if (response.success && response.data) {
        // Laravel devuelve los items en response.data
        cartItems.value = response.data.map((item: any) => {
            // Aseguramos que la imagen tenga ruta completa
            if (item.producto && item.producto.Imagenes) {
                item.producto.image = item.producto.Imagenes.startsWith('http')
                    ? item.producto.Imagenes
                    : `${storageUrl}/${item.producto.Imagenes}`;
            } else if (item.producto) {
                item.producto.image = 'https://placehold.co/400?text=Sin+Imagen';
            }
            return item;
        });
    }
};

onMounted(() => {
    fetchCart();
});

const increaseQuantity = async (item: any) => {
    if (isProcessing.value) return;
    isProcessing.value = true;
    
    const maxStock = parseInt(item.producto?.Stock || item.producto?.stock || 99);
    const newQty = item.Cantidad + 1;
    
    if (newQty > maxStock) {
        isProcessing.value = false;
        router.push({ path: '/cliente/error-stock', query: { id: item.producto?.id_producto || item.id_producto } });
        return;
    }
    
    const res = await actualizarCantidad(item.idCarrito, newQty);
    if (res.success) {
        item.Cantidad = newQty;
    }
    
    isProcessing.value = false;
};

const decreaseQuantity = async (item: any) => {
    if (isProcessing.value) return;
    
    if (item.Cantidad > 1) {
        isProcessing.value = true;
        const newQty = item.Cantidad - 1;
        const res = await actualizarCantidad(item.idCarrito, newQty);
        if (res.success) {
            item.Cantidad = newQty;
        }
        isProcessing.value = false;
    }
};

const removeItem = async (item: any) => {
    if (confirm('¿Estás seguro de eliminar este producto del carrito?')) {
        isProcessing.value = true;
        const res = await eliminarDelCarrito(item.idCarrito);
        if (res.success) {
            cartItems.value = cartItems.value.filter(i => i.idCarrito !== item.idCarrito);
        }
        isProcessing.value = false;
    }
};

const subtotal = computed(() => {
    return cartItems.value.reduce((acc, item) => {
        const price = parseFloat(item.producto?.Precio || 0);
        return acc + (price * item.Cantidad);
    }, 0);
});

const envio = computed(() => {
    // Envío gratis si el subtotal es mayor a $500, si no, $99
    return subtotal.value > 500 || subtotal.value === 0 ? 0 : 99;
});

const total = computed(() => {
    return subtotal.value + envio.value;
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(price);
};
</script>

<template>
    <div class="bg-background text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed min-h-screen">
        <Header />

        <main class="pt-32 pb-24 px-6 md:px-12 max-w-7xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter leading-tight font-headline mb-10 text-on-surface">
                Tu Carrito de Compras
            </h1>

            <div v-if="isLoading && cartItems.length === 0" class="flex flex-col items-center justify-center py-20 gap-4">
                <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <p class="text-on-surface-variant font-medium">Cargando tu carrito...</p>
            </div>

            <div v-else-if="cartItems.length === 0" class="flex flex-col items-center justify-center py-20 text-center bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-sm">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4">remove_shopping_cart</span>
                <h3 class="text-2xl font-bold text-on-surface font-headline mb-2">Tu carrito está vacío</h3>
                <p class="text-on-surface-variant mb-8 max-w-md">Parece que aún no has agregado nada. ¡Explora nuestro catálogo y encuentra lo que necesitas!</p>
                <NuxtLink to="/cliente/main" class="px-8 py-4 bg-primary text-on-primary rounded-xl font-bold hover:shadow-lg transition-shadow">
                    Explorar Productos
                </NuxtLink>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Lista de Productos -->
                <div class="lg:col-span-8 space-y-6">
                    <div v-for="item in cartItems" :key="item.idCarrito" class="bg-surface-container-lowest p-6 rounded-2xl flex gap-6 items-start shadow-sm border border-outline-variant/10 transition-all hover:shadow-md">
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-xl overflow-hidden shrink-0 border border-outline-variant/20 bg-surface-container">
                            <img :src="item.producto?.image" :alt="item.producto?.Nombre || 'Producto'" class="w-full h-full object-cover" />
                        </div>
                        
                        <div class="flex-1 flex flex-col h-full justify-between min-h-[128px] md:min-h-[160px]">
                            <div class="flex justify-between items-start gap-4 mb-4">
                                <div>
                                    <h3 class="font-bold text-lg md:text-xl text-on-surface line-clamp-2 leading-tight mb-1">{{ item.producto?.Nombre || item.Seleccion }}</h3>
                                    <p class="text-sm text-on-surface-variant font-medium">{{ item.producto?.categoria || 'Marketplace' }}</p>
                                </div>
                                <span class="font-black text-xl md:text-2xl text-primary shrink-0">{{ formatPrice(item.producto?.Precio || 0) }}</span>
                            </div>

                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex items-center bg-surface-container-low border border-outline-variant/30 rounded-lg overflow-hidden">
                                    <button @click="decreaseQuantity(item)" :disabled="item.Cantidad <= 1 || isProcessing" class="w-10 h-10 flex items-center justify-center hover:bg-surface-container-high text-on-surface font-bold transition-colors disabled:opacity-50 material-symbols-outlined">remove</button>
                                    <span class="w-12 h-10 flex items-center justify-center font-bold text-on-surface border-x border-outline-variant/30 text-sm">{{ item.Cantidad }}</span>
                                    <button @click="increaseQuantity(item)" :disabled="isProcessing" class="w-10 h-10 flex items-center justify-center hover:bg-surface-container-high text-on-surface font-bold transition-colors disabled:opacity-50 material-symbols-outlined">add</button>
                                </div>

                                <button @click="removeItem(item)" :disabled="isProcessing" class="text-error hover:bg-error/10 px-3 py-2 md:px-4 rounded-lg transition-colors flex items-center gap-2 disabled:opacity-50">
                                    <span class="material-symbols-outlined">delete</span>
                                    <span class="text-sm font-bold hidden sm:inline">Eliminar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen de Pago -->
                <div class="lg:col-span-4">
                    <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-lg border border-outline-variant/10 sticky top-32">
                        <h2 class="text-2xl font-bold font-headline mb-6 text-on-surface border-b border-outline-variant/20 pb-4">Resumen del Pedido</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Subtotal ({{ cartItems.length }} productos)</span>
                                <span class="font-semibold text-on-surface">{{ formatPrice(subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Envío</span>
                                <span class="font-semibold text-on-surface">
                                    <span v-if="envio === 0" class="text-green-600 font-bold bg-green-100 px-2 py-0.5 rounded text-xs tracking-wider uppercase">Gratis</span>
                                    <span v-else>{{ formatPrice(envio) }}</span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="border-t border-outline-variant/20 pt-6 mb-8">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-on-surface">Total</span>
                                <span class="text-3xl font-black text-primary">{{ formatPrice(total) }}</span>
                            </div>
                            <p v-if="envio > 0" class="text-xs text-on-surface-variant mt-2 text-right">Faltan {{ formatPrice(500 - subtotal) }} para envío gratis.</p>
                        </div>
                        
                        <button @click="router.push('/cliente/seleccionar-entrega')" class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2 shadow-[0px_8px_16px_rgba(0,62,199,0.2)] text-lg">
                            <span class="material-symbols-outlined">location_on</span>
                            Elegir Punto de Entrega
                        </button>

                        <div class="mt-6 flex justify-center gap-4 opacity-60">
                            <span class="material-symbols-outlined text-3xl" title="Pagos Seguros">payments</span>
                            <span class="material-symbols-outlined text-3xl" title="Entrega Rápida">local_shipping</span>
                            <span class="material-symbols-outlined text-3xl" title="Soporte 24/7">support_agent</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.font-headline,
.font-body {
    font-family: 'Inter', sans-serif;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
