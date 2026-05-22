<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useProductos } from '~/composables/useProductos';
import { useCarrito } from '~/composables/useCarrito';

import Header from '../../components/cliente/Header.vue';

const router = useRouter();
const route = useRoute();
const { obtenerProductoPublico } = useProductos();
const { agregarAlCarrito } = useCarrito();
const { storageUrl } = useApiUrl();

const product = ref({
    id: null,
    name: 'Cargando producto...',
    sku: '...',
    image: 'https://placehold.co/400?text=Cargando...',
    maxStock: 1
});

const quantity = ref(1);

onMounted(async () => {
    const productId = route.query.id;
    if (productId) {
        const response = await obtenerProductoPublico(productId);
        if (response.success && response.data) {
            const data = response.data.data || response.data;
            const stock = parseInt(data.Stock || data.stock || 1);
            product.value = {
                id: data.id_producto,
                name: data.Nombre,
                sku: `SKU-${data.id_producto}`, // El backend no tiene sku, improvisamos uno
                image: data.Imagenes
                    ? (data.Imagenes.startsWith('http') ? data.Imagenes : `${storageUrl}/${data.Imagenes}`)
                    : 'https://placehold.co/400?text=Sin+Imagen',
                maxStock: stock
            };
            quantity.value = stock; // Empezamos en el límite máximo sugerido
        }
    }
});

const increment = () => {
    if (quantity.value < product.value.maxStock) {
        quantity.value++;
    }
};

const decrement = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const isMaxReached = computed(() => quantity.value >= product.value.maxStock);

const updateOrder = async () => {
    if (product.value.id) {
        const response = await agregarAlCarrito(product.value.id, quantity.value, product.value.name);
        if (response.success) {
            router.push({ path: '/cliente/carrito-confirmacion', query: { id: product.value.id } });
        } else {
            alert('Hubo un error al agregar al carrito');
        }
    }
};

const returnToCart = () => {
    router.push('/cliente/carrito');
};

const contactSupport = () => {
    alert('Contactando a soporte técnico...');
};
</script>

<template>
    <Header />
    <div class="bg-background font-body text-on-surface antialiased min-h-screen">

        <main class="flex items-center justify-center px-4 pt-28 pb-24">
            <div class="max-w-2xl w-full">
                <div class="bg-surface-container-lowest rounded-xl shadow-custom overflow-hidden">

                    <div class="bg-error-container px-8 py-10 flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-on-error flex items-center justify-center rounded-full mb-6 shadow-sm">
                            <span class="material-symbols-outlined text-error text-4xl">warning</span>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight text-on-error-container mb-2">
                            Cantidad no disponible, ajuste su pedido
                        </h1>
                        <p class="text-on-error-container/80 font-medium">
                            Lo sentimos, no podemos procesar la cantidad solicitada en este momento.
                        </p>
                    </div>

                    <div class="p-8 space-y-8">
                        <div
                            class="flex flex-col md:flex-row gap-6 items-center p-6 bg-surface-container-low rounded-lg">
                            <div class="w-32 h-32 flex-shrink-0 bg-white rounded-lg overflow-hidden">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex-grow text-center md:text-left">
                                <h2 class="text-xl font-bold text-on-surface mb-1">{{ product.name }}</h2>
                                <p class="text-on-surface-variant text-sm mb-3">SKU: {{ product.sku }}</p>
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-error/10 text-error rounded-full font-bold text-sm">
                                    <span class="material-symbols-outlined text-sm filled">inventory_2</span>
                                    Solo quedan {{ product.maxStock }} unidades
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label
                                class="block text-sm font-bold text-on-surface-variant uppercase tracking-wider">Ajustar
                                cantidad</label>
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center bg-surface-container-low rounded-lg p-1 w-full max-w-[200px]">
                                    <button @click="decrement"
                                        class="w-12 h-12 flex items-center justify-center hover:bg-surface-container-lowest rounded-lg transition-colors text-primary active:scale-90">
                                        <span class="material-symbols-outlined">remove</span>
                                    </button>
                                    <input
                                        class="w-full bg-transparent border-none text-center font-bold text-xl focus:ring-0 text-on-surface"
                                        type="number" v-model="quantity" readonly />
                                    <button @click="increment" :disabled="isMaxReached"
                                        :class="['w-12 h-12 flex items-center justify-center rounded-lg transition-colors text-primary',
                                            isMaxReached ? 'opacity-30 cursor-not-allowed' : 'hover:bg-surface-container-lowest active:scale-90']">
                                        <span class="material-symbols-outlined">add</span>
                                    </button>
                                </div>
                                <span v-if="isMaxReached" class="text-on-surface-variant text-sm italic">Límite máximo
                                    alcanzado</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button @click="updateOrder"
                                class="flex-1 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold py-4 rounded-xl shadow-lg hover:shadow-primary/20 transition-all active:scale-[0.98]">
                                Actualizar Pedido
                            </button>
                            <button @click="returnToCart"
                                class="flex-1 bg-secondary-container text-on-secondary-container font-bold py-4 rounded-xl hover:bg-surface-container-high transition-all active:scale-[0.98]">
                                Volver al Carrito
                            </button>
                        </div>

                        <div class="flex justify-center">
                            <button @click="contactSupport"
                                class="text-primary font-semibold text-sm hover:underline flex items-center gap-1 py-2">
                                <span class="material-symbols-outlined text-sm">help</span>
                                Contactar con soporte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <nav
            class="md:hidden fixed bottom-0 left-0 w-full bg-white dark:bg-slate-950 flex justify-around items-center px-4 pb-6 pt-2 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] rounded-t-2xl z-50">
            <a v-for="item in navItems" :key="item.label" href="#"
                :class="item.active ? 'bg-blue-50 text-blue-700' : 'text-slate-500'"
                class="flex flex-col items-center justify-center rounded-xl px-3 py-1.5 transition-all">
                <span class="material-symbols-outlined">{{ item.icon }}</span>
                <span class="text-xs font-medium mt-1">{{ item.label }}</span>
            </a>
        </nav>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.material-symbols-outlined.filled {
    font-variation-settings: 'FILL' 1;
}

.shadow-custom {
    box-shadow: 0px 12px 32px rgba(13, 28, 46, 0.06);
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>