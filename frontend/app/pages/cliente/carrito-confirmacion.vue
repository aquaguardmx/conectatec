<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useProductos } from '~/composables/useProductos';

const router = useRouter();
const route = useRoute();
const { obtenerProductoPublico } = useProductos();
const { storageUrl } = useApiUrl();

const product = ref({
    name: 'Cargando producto...',
    quantity: 1,
    price: 0.00,
    image: 'https://placehold.co/400?text=Cargando...',
    category: 'MARKETPLACE'
});

onMounted(async () => {
    const productId = route.query.id;
    if (productId) {
        const response = await obtenerProductoPublico(productId);
        if (response.success && response.data) {
            const data = response.data.data || response.data;
            product.value = {
                name: data.Nombre,
                quantity: 1,
                price: parseFloat(data.Precio),
                image: data.Imagenes
                    ? (data.Imagenes.startsWith('http') ? data.Imagenes : `${storageUrl}/${data.Imagenes}`)
                    : 'https://placehold.co/400?text=Sin+Imagen',
                category: data.categoria || 'MARKETPLACE'
            };
        }
    }
});

const goToCart = () => {
    // Si tienes una página de carrito, navega ahí. Por ahora lo mandamos a inicio o donde gustes.
    router.push('/cliente/main'); // Cambia esto por la ruta real de tu carrito cuando la tengas
};

const continueShopping = () => {
    router.push('/cliente/main');
};
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center p-6 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-surface-container-low via-background to-background">

        <div
            class="max-w-xl w-full bg-surface-container-lowest rounded-xl custom-shadow overflow-hidden border-none relative">

            <div class="pt-12 pb-8 px-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-tertiary-container/10 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-tertiary text-4xl filled">check_circle</span>
                </div>
                <h1 class="text-3xl font-headline font-black tracking-tighter text-on-surface mb-2">
                    Producto agregado correctamente
                </h1>
                <p class="text-on-surface-variant body-md max-w-sm">
                    Tu selección ha sido procesada y ya se encuentra lista en tu carrito de compras.
                </p>
            </div>

            <div class="px-8 mb-10">
                <div class="bg-surface-container-low rounded-xl p-6 flex items-center gap-6">
                    <div class="w-24 h-24 rounded-lg overflow-hidden bg-white shrink-0">
                        <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-tertiary text-sm filled">verified</span>
                            <span class="text-tertiary text-xs font-bold tracking-wider">{{ product.category }}</span>
                        </div>
                        <h2 class="text-xl font-headline font-bold text-on-surface tracking-tight">
                            {{ product.name }}
                        </h2>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-on-surface-variant font-medium">
                                Cantidad: <span class="text-on-surface">{{ product.quantity }}</span>
                            </p>
                            <p class="text-primary font-bold text-lg">${{ product.price.toFixed(2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 pb-12 flex flex-col gap-3">
                <button @click="goToCart"
                    class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-lg active:scale-[0.98] transition-all flex items-center justify-center gap-2 custom-shadow">
                    Ir al carrito
                    <span class="material-symbols-outlined">shopping_cart</span>
                </button>

                <button @click="continueShopping"
                    class="w-full py-4 bg-secondary-container text-on-secondary-container font-bold rounded-lg hover:bg-secondary-fixed transition-colors active:scale-[0.98]">
                    Seguir comprando
                </button>
            </div>

            <div class="bg-surface-container py-4 px-8 flex justify-center items-center gap-4 border-none">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant text-lg">shield</span>
                    <span class="text-xs text-on-surface-variant font-medium">Compra Protegida</span>
                </div>
                <div class="w-1 h-1 bg-outline-variant rounded-full"></div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant text-lg">local_shipping</span>
                    <span class="text-xs text-on-surface-variant font-medium">Envío TechExpress</span>
                </div>
            </div>
        </div>

        <div class="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden pointer-events-none">
            <div
                class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] bg-surface-tint opacity-5 blur-[120px] rounded-full">
            </div>
            <div
                class="absolute bottom-[-5%] left-[-10%] w-[30%] h-[30%] bg-tertiary opacity-5 blur-[100px] rounded-full">
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Importación de fuentes e iconos si no están en tu index.html */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

.font-headline,
.font-body {
    font-family: 'Inter', sans-serif;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.material-symbols-outlined.filled {
    font-variation-settings: 'FILL' 1;
}

.custom-shadow {
    box-shadow: 0px 12px 32px rgba(13, 28, 46, 0.06);
}
</style>