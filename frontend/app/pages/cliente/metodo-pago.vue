<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useCarrito } from '~/composables/useCarrito';

const router = useRouter();
const { obtenerCarrito } = useCarrito();

// Estado para el método de pago seleccionado
const selectedMethod = ref('card');

const cartItems = ref([]);

const fetchCart = async () => {
    const response = await obtenerCarrito();
    if (response.success && response.data) {
        cartItems.value = response.data;
    }
};

onMounted(() => {
    fetchCart();
});

const subtotal = computed(() => {
    return cartItems.value.reduce((acc, item) => {
        const price = parseFloat(item.producto?.Precio || 0);
        return acc + (price * item.Cantidad);
    }, 0);
});

const total = computed(() => subtotal.value);

// Datos del resumen reactivos
const orderSummary = computed(() => ({
    id: '#ORD-' + Math.floor(Math.random() * 90000 + 10000), // ID aleatorio temporal
    subtotal: subtotal.value,
    total: total.value
}));

const handleContinue = () => {
    // Guardar método de pago en sessionStorage
    sessionStorage.setItem('metodoPago', selectedMethod.value);

    if (selectedMethod.value === 'card') {
        router.push('/cliente/datos-tarjeta');
    } else {
        // Efectivo: saltar datos de tarjeta e ir directo a confirmación
        router.push('/cliente/confirmacion-pago');
    }
};

const goBack = () => {
    router.push('/cliente/carrito');
};
</script>

<template>
    <div class="bg-background font-body text-on-background min-h-screen">
        <nav
            class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-md shadow-custom flex items-center justify-between px-8 h-20">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-black tracking-tighter text-primary">Conectatec</span>
                <div class="hidden md:flex items-center space-x-6">
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-colors duration-200"
                        href="#">Dashboard</a>
                    <a class="text-primary font-bold border-b-2 border-primary hover:text-primary transition-colors duration-200"
                        href="#">Orders</a>
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-colors duration-200"
                        href="#">Payments</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="p-2 text-on-surface-variant hover:text-primary transition-colors active:scale-95">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <button class="p-2 text-on-surface-variant hover:text-primary transition-colors active:scale-95">
                    <span class="material-symbols-outlined">help_outline</span>
                </button>
                <div
                    class="h-10 w-10 rounded-full bg-surface-container-high flex items-center justify-center overflow-hidden border border-outline-variant/20">
                    <img alt="User profile" class="w-full h-full object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDlWPDgcwSQS54T9hMNXSwHxnFXY-KGWv5FvaYgcp8sB87CsQTFCCbQmiuz2Zy3OwuClDJjRyx6I3yb3i492BLfuo55LLaMKO8tEZ5EXBIdYF3AvtJfd6nRPJlBnjF6cNmExuyYhX6228MVxiplckXJzrLNCL2TrhMpBAocDnOpCZaOzRmn41rFVjc6Abhg3N52iNV4OZFUYAazxOuUUCIhDFQySDs5vNAfTH712i6IVFI9xuoZMI4h_CPRDwLPLLGJMwd7MHr09Hg" />
                </div>
            </div>
        </nav>

        <main class="pt-32 pb-20 px-4 md:px-0 flex justify-center min-h-screen bg-surface-tint/[0.05]">
            <div class="w-full max-w-2xl">
                <div class="flex items-center gap-2 mb-8 px-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-primary/60">Paso 02</span>
                    <div class="h-[1px] w-8 bg-outline-variant/30"></div>
                    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Pago</h2>
                </div>

                <div class="mb-10 px-2">
                    <h1 class="text-4xl font-extrabold tracking-tight text-on-surface mb-3">Seleccionar método de pago
                    </h1>
                    <p class="text-on-surface-variant text-lg">Elige cómo prefieres completar tu transacción de forma
                        segura.</p>
                </div>

                <div class="space-y-6">
                    <label class="block cursor-pointer group">
                        <input type="radio" v-model="selectedMethod" value="card" class="hidden" />
                        <div class="bg-surface-container-lowest p-6 rounded-xl transition-all duration-300 shadow-sm border-2 flex items-center justify-between hover:translate-x-1"
                            :class="selectedMethod === 'card' ? 'border-primary shadow-md' : 'border-transparent'">
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-3xl">credit_card</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-on-surface">Tarjeta de Crédito o Débito</h3>
                                    <p class="text-sm text-on-surface-variant">Visa, Mastercard, American Express</p>
                                </div>
                            </div>
                            <div class="h-6 w-6 rounded-full border-2 transition-colors flex items-center justify-center"
                                :class="selectedMethod === 'card' ? 'bg-primary border-primary' : 'border-outline-variant'">
                                <div class="h-2 w-2 rounded-full bg-white transition-opacity"
                                    :class="selectedMethod === 'card' ? 'opacity-100' : 'opacity-0'"></div>
                            </div>
                        </div>
                    </label>

                    <label class="block cursor-pointer group">
                        <input type="radio" v-model="selectedMethod" value="cash" class="hidden" />
                        <div class="bg-surface-container-lowest p-6 rounded-xl transition-all duration-300 shadow-sm border-2 flex items-center justify-between hover:translate-x-1"
                            :class="selectedMethod === 'cash' ? 'border-primary shadow-md' : 'border-transparent'">
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-14 h-14 rounded-full bg-tertiary/10 flex items-center justify-center text-tertiary">
                                    <span class="material-symbols-outlined text-3xl">payments</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-on-surface">Efectivo</h3>
                                    <p class="text-sm text-on-surface-variant">Pago en puntos autorizados o contra
                                        entrega</p>
                                </div>
                            </div>
                            <div class="h-6 w-6 rounded-full border-2 transition-colors flex items-center justify-center"
                                :class="selectedMethod === 'cash' ? 'bg-primary border-primary' : 'border-outline-variant'">
                                <div class="h-2 w-2 rounded-full bg-white transition-opacity"
                                    :class="selectedMethod === 'cash' ? 'opacity-100' : 'opacity-0'"></div>
                            </div>
                        </div>
                    </label>

                    <div class="mt-12 p-8 rounded-xl bg-surface-container-low border border-white/40 backdrop-blur-sm">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-on-surface-variant font-medium">Resumen del pedido</span>
                            <span class="text-on-surface-variant text-sm">{{ orderSummary.id }}</span>
                        </div>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-on-surface-variant">Subtotal</span>
                                <span class="text-on-surface font-semibold">${{ orderSummary.subtotal.toLocaleString()
                                    }}</span>
                            </div>

                        </div>
                        <div class="pt-6 border-t border-outline-variant/20 flex justify-between items-end">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-on-surface-variant mb-1">Total a pagar
                                </p>
                                <p class="text-3xl font-black text-primary">${{ orderSummary.total.toLocaleString() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1 bg-tertiary/10 rounded-full">
                                <span class="material-symbols-outlined text-tertiary text-sm filled">verified</span>
                                <span class="text-[10px] font-bold text-tertiary uppercase tracking-tighter">Merchant
                                    Verificado</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 mt-12 pt-8">
                        <button @click="goBack"
                            class="flex items-center gap-2 px-6 py-4 text-primary font-bold hover:bg-primary/5 rounded-xl transition-colors active:scale-95">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Volver
                        </button>
                        <button @click="handleContinue"
                            class="flex-1 max-w-xs bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-primary/30 transition-all active:scale-95 flex items-center justify-center gap-3">
                            Continuar
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-10 text-center">
            <div class="flex items-center justify-center gap-4 opacity-40">
                <span class="material-symbols-outlined">lock</span>
                <span class="text-xs font-medium tracking-widest uppercase">Pago seguro encriptado SSL</span>
            </div>
        </footer>
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