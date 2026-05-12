<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useCarrito } from '~/composables/useCarrito';

const router = useRouter();
const { obtenerCarrito, realizarCompra } = useCarrito();

const cartItems = ref([]);
const isProcessing = ref(false);
// 'tarjeta' | 'efectivo' — se carga desde sessionStorage
const selectedPaymentMethod = ref('tarjeta');

// Datos de entrega guardados en seleccionar-entrega
const entregaDetails = ref({ punto_entrega: '', fecha_entrega: '', hora_entrega: '' });

const fetchCart = async () => {
    const response = await obtenerCarrito();
    if (response.success && response.data) {
        cartItems.value = response.data;
    }
};

onMounted(() => {
    fetchCart();

    // Cargar detalles de entrega
    const str = sessionStorage.getItem('entregaDetails');
    if (str) {
        try { entregaDetails.value = JSON.parse(str); } catch (e) {}
    }

    // Cargar método de pago guardado en metodo-pago.vue
    const metodo = sessionStorage.getItem('metodoPago');
    if (metodo) {
        // El frontend usa 'card' pero el backend espera 'tarjeta'
        selectedPaymentMethod.value = metodo === 'card' ? 'tarjeta' : 'efectivo';
    }
});

const subtotal = computed(() => {
    return cartItems.value.reduce((acc, item) => {
        const price = parseFloat(item.producto?.Precio || 0);
        return acc + (price * item.Cantidad);
    }, 0);
});

const total = computed(() => subtotal.value);

const orderData = computed(() => {
    if (cartItems.value.length === 0) return null;

    const firstItem = cartItems.value[0];
    const image = firstItem.producto?.Imagenes
        ? (firstItem.producto.Imagenes.startsWith('http') ? firstItem.producto.Imagenes : `http://127.0.0.1:8000/storage/${firstItem.producto.Imagenes}`)
        : 'https://placehold.co/400?text=Sin+Imagen';

    const extraItems = cartItems.value.length > 1 ? `y ${cartItems.value.length - 1} productos más` : '';

    return {
        productName: firstItem.producto?.Nombre || firstItem.Seleccion,
        specs: extraItems || `Cantidad: ${firstItem.Cantidad}`,
        price: total.value,
        subtotal: subtotal.value,
        image: image,
    };
});

const horaEtiqueta = computed(() => {
    const mapa = { '10:00': '10:00 AM', '12:00': '12:00 PM', '14:00': '2:00 PM', '16:00': '4:00 PM' };
    return mapa[entregaDetails.value.hora_entrega] || entregaDetails.value.hora_entrega;
});

const fechaFormateada = computed(() => {
    if (!entregaDetails.value.fecha_entrega) return '';
    const d = new Date(entregaDetails.value.fecha_entrega + 'T12:00:00');
    return d.toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long' });
});

const handleConfirm = async () => {
    if (isProcessing.value || cartItems.value.length === 0) return;
    isProcessing.value = true;

    const paymentDetailsStr = sessionStorage.getItem('paymentDetails');
    let nombreTarjeta = undefined;
    let numeroTarjeta = undefined;
    
    if (paymentDetailsStr) {
        try {
            const details = JSON.parse(paymentDetailsStr);
            nombreTarjeta = details.nombre_tarjeta;
            numeroTarjeta = details.numero_tarjeta;
        } catch (e) {
            console.error('Error parsing payment details', e);
        }
    }

    const response = await realizarCompra(
        selectedPaymentMethod.value,
        nombreTarjeta,
        numeroTarjeta,
        entregaDetails.value.punto_entrega,
        entregaDetails.value.fecha_entrega,
        entregaDetails.value.hora_entrega,
    );

    isProcessing.value = false;

    if (response.success) {
        sessionStorage.removeItem('paymentDetails');
        sessionStorage.removeItem('entregaDetails');
        sessionStorage.removeItem('metodoPago');
        alert('¡Compra realizada con éxito!');
        router.push('/cliente/main');
    } else {
        alert(response.error || 'Hubo un error al procesar tu compra.');
    }
};

const goBack = () => {
    // Si pagó con tarjeta, regresar a datos de tarjeta; si es efectivo, a método de pago
    if (selectedPaymentMethod.value === 'tarjeta') {
        router.push('/cliente/datos-tarjeta');
    } else {
        router.push('/cliente/metodo-pago');
    }
};
</script>

<template>
    <div class="bg-background font-body text-on-background antialiased min-h-screen">

        <header
            class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-md shadow-custom flex items-center justify-between px-8 h-20">
            <div class="text-2xl font-black tracking-tighter text-primary">Conectatec</div>
            <div class="flex items-center gap-6">
                <span
                    class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary transition-colors">
                    help_outline
                </span>
                <div
                    class="h-10 w-10 rounded-full bg-surface-container-high flex items-center justify-center overflow-hidden border border-outline-variant/10">
                    <img alt="User profile" class="w-full h-full object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDaKkujVrhnVoiYNqKAMnYjOPOHYLtyVAXL35IcDqbUmVx-8cdcCCFaSUHV1yD2Nu9KBLoIMYJFl_m0U2zV8NbnDGE_DMYyUDcmhmbr3IQbF-R8qqT4xErUZFfcvDVBwUu_CA93ASXzBpI0qQDLzg35hABUzFZgUbVUAUY25OrjGkYRJVvxiSByCvJpHAmd5XYRmK_N-vv1wpKbfE3yrl7UTE9knEmaYX-hOtmu5gJMQLesQGM4-ds9VRdiiExTT9apS2d6u4cEinQ">
                </div>
            </div>
        </header>

        <main class="min-h-screen pt-32 pb-20 px-6 flex flex-col items-center justify-center relative overflow-hidden">

            <div class="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-center z-10">

                <div class="lg:col-span-7 flex flex-col space-y-8">
                    <div class="relative">
                        <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>

                        <div
                            class="relative inline-flex items-center justify-center p-4 bg-surface-container-lowest rounded-xl shadow-custom mb-8">
                            <div
                                class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-on-primary">
                                <span class="material-symbols-outlined text-4xl filled">check_circle</span>
                            </div>
                            <div class="ml-4 pr-4">
                                <p class="text-primary font-bold text-sm uppercase tracking-widest">Paso Completado</p>
                                <h2 class="text-xl font-bold text-on-surface">Método de pago seleccionado</h2>
                            </div>
                        </div>

                        <h1
                            class="text-5xl lg:text-6xl font-black text-on-background tracking-tighter leading-tight mb-6">
                            Todo listo
                        </h1>
                        <p class="text-on-surface-variant text-lg max-w-md leading-relaxed">
                            Tu selección ha sido validada. Haz clic en el botón para procesar el pago y asegurar tu
                            pedido en el próximo lote de envío.
                        </p>
                    </div>



                    <div v-if="cartItems.length > 0" class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button @click="handleConfirm" :disabled="isProcessing"
                            class="px-8 py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-xl shadow-lg hover:shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50">
                            {{ isProcessing ? 'Procesando...' : 'Confirmar Compra' }}
                            <span class="material-symbols-outlined" v-if="!isProcessing">arrow_forward</span>
                            <div v-else
                                class="w-5 h-5 border-2 border-on-primary border-t-transparent rounded-full animate-spin">
                            </div>
                        </button>
                        <button @click="goBack" :disabled="isProcessing"
                            class="px-8 py-4 bg-secondary-container text-on-secondary-container font-semibold rounded-xl hover:bg-surface-container-high transition-colors duration-200 disabled:opacity-50">
                            Volver
                        </button>
                    </div>
                    <div v-else
                        class="p-6 bg-error/10 text-error rounded-xl font-bold border border-error/20 flex flex-col items-center justify-center">
                        <span class="material-symbols-outlined text-4xl mb-2">remove_shopping_cart</span>
                        Tu carrito está vacío. No hay nada que comprar.
                        <button @click="router.push('/cliente/main')"
                            class="mt-4 px-6 py-2 bg-error text-white rounded-lg">Ir a la tienda</button>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-surface-container-lowest rounded-xl shadow-custom p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <span class="material-symbols-outlined text-8xl">shopping_cart_checkout</span>
                        </div>

                        <h3 class="text-on-surface font-bold text-lg mb-6 flex items-center gap-2">
                            Resumen del Pedido
                        </h3>

                        <div v-if="orderData" class="space-y-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-16 h-16 bg-surface-container-low rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/20">
                                    <img :src="orderData.image" :alt="orderData.productName"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-on-surface text-sm line-clamp-1">{{ orderData.productName
                                    }}</p>
                                    <p class="text-on-surface-variant text-xs">{{ orderData.specs }}</p>
                                </div>
                                <p class="font-bold text-on-surface">${{ orderData.price.toLocaleString() }}</p>
                            </div>

                            <div class="p-4 bg-surface-container-low rounded-xl border border-primary/10">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Punto de Entrega</p>
                                    <span class="bg-tertiary/10 text-tertiary text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-tighter">Confirmado</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings:'FILL' 1;">location_on</span>
                                    <div>
                                        <p class="text-on-surface font-bold text-sm">{{ entregaDetails.punto_entrega || 'Por definir' }}</p>
                                        <p class="text-on-surface-variant text-xs capitalize mt-0.5">{{ fechaFormateada }} · {{ horaEtiqueta }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-outline-variant/20 space-y-3">
                                <div class="flex justify-between text-on-surface-variant text-sm">
                                    <span>Subtotal</span>
                                    <span>${{ orderData.subtotal.toLocaleString() }}</span>
                                </div>

                                <div class="flex justify-between text-on-surface text-xl font-black pt-2">
                                    <span>Total</span>
                                    <span class="text-primary">${{ orderData.price.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-center gap-2 text-tertiary">
                        <span class="material-symbols-outlined text-lg filled">verified_user</span>
                        <span class="text-xs font-bold uppercase tracking-widest">Compra Protegida por Conectatec
                            Secure</span>
                    </div>
                </div>
            </div>

            <div
                class="fixed bottom-0 left-0 w-full h-64 bg-gradient-to-t from-surface-container-low to-transparent -z-10 pointer-events-none">
            </div>
            <div class="fixed top-0 right-0 w-1/3 h-full bg-surface-tint/[0.03] -z-10 pointer-events-none skew-x-12">
            </div>
        </main>
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
</style>