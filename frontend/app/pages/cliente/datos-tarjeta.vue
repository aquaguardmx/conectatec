<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useCarrito } from '~/composables/useCarrito';

const router = useRouter();
const { obtenerCarrito } = useCarrito();
const { storageUrl } = useApiUrl();

// Estado del formulario
const form = reactive({
    cardholder: '',
    cardNumber: '',
    expiry: '',
    cvv: '',
    saveCard: false
});

// Estado de validación
const errors = reactive({
    cardNumber: false,
    expiry: false
});

// Datos del pedido dinámicos
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

// Bloquea teclas no numéricas en el campo de tarjeta (permite: dígitos, Backspace, Delete, flechas, Tab)
const onlyDigitsKeydown = (e) => {
    const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', 'Home', 'End'];
    if (allowed.includes(e.key)) return;
    if (!/^\d$/.test(e.key)) e.preventDefault();
};

// Formateador simple para el número de tarjeta (añade espacios cada 4 dígitos)
const formatCardNumber = (e) => {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formatted = value.match(/.{1,4}/g)?.join(' ') || '';
    form.cardNumber = formatted.substring(0, 19); // Máximo 16 dígitos + 3 espacios
};

// Formateador para fecha de expiración MM/YY
const formatExpiry = (e) => {
    let value = e.target.value.replace(/\//g, '').replace(/[^0-9]/gi, '');
    if (value.length >= 2) {
        form.expiry = value.substring(0, 2) + '/' + value.substring(2, 4);
    } else {
        form.expiry = value;
    }
    // Limpiar error mientras el usuario escribe
    errors.expiry = false;
};

const processPayment = () => {
    // Validar número de tarjeta (16 dígitos)
    const cleanNumber = form.cardNumber.replace(/\s/g, '');
    errors.cardNumber = !/^\d{16}$/.test(cleanNumber);

    // Validar fecha de expiración: debe ser MM/YY válido y mayor al mes actual
    errors.expiry = false;
    const expiryMatch = form.expiry.match(/^(\d{2})\/(\d{2})$/);
    if (!expiryMatch) {
        errors.expiry = true;
    } else {
        const month = parseInt(expiryMatch[1], 10);
        const year  = parseInt('20' + expiryMatch[2], 10);
        const now   = new Date();
        const currentYear  = now.getFullYear();
        const currentMonth = now.getMonth() + 1; // 1-12

        if (month < 1 || month > 12) {
            errors.expiry = true;
        } else if (year < currentYear || (year === currentYear && month <= currentMonth)) {
            errors.expiry = true;
        }
    }

    if (!errors.cardNumber && !errors.expiry) {
        // Guardar información temporal para la confirmación del pago
        sessionStorage.setItem('paymentDetails', JSON.stringify({
            nombre_tarjeta: form.cardholder,
            numero_tarjeta: cleanNumber
        }));
        // Al tener éxito, navegamos a confirmación de pago
        router.push('/cliente/confirmacion-pago');
    }
};
</script>

<template>
    <div class="text-on-background bg-background min-h-screen font-body">
        <header
            class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-md shadow-custom flex items-center justify-between px-8 h-20">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black tracking-tighter text-primary">Conectatec</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <nav class="flex items-center space-x-6">
                    <a class="text-primary font-bold border-b-2 border-primary transition-colors duration-200"
                        href="#">Checkout</a>
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-colors duration-200"
                        href="#">Productos</a>
                    <a class="text-on-surface-variant font-medium hover:text-primary transition-colors duration-200"
                        href="#">Soporte</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <button class="text-on-surface-variant hover:text-primary active:scale-95 transition-all">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div
                        class="w-10 h-10 rounded-full bg-surface-container overflow-hidden border border-outline-variant/20">
                        <img alt="User Profile" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuClqLe9Vm5VSOPo7l8bG17F1cN17QzHreWwIDfsQBKmxZr-I-0Ao4NTXh4GAR4rXWabPMmetEohiRrMZzNmVuU4tDju1KX1AysIaCAsnpLO8BsT76Rf8laz2Okk9mTD-hm9yYZ_MAPmZNo1GzhilP1sM1Um-91obRkUSt6vyO-c1skptp380bIWk4mgntzslf4Q17gHgoctKRctl_aKOwuPJSWyTCp8cntkxl0VZxHDjsEXlHBrorXLKEW46JDrDsPOdnq7QatwsyM" />
                    </div>
                </div>
            </div>
        </header>

        <main class="pt-32 pb-20 px-4 md:px-8 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                <section class="lg:col-span-7 space-y-8">
                    <div class="space-y-2">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full bg-tertiary/10 text-tertiary text-xs font-bold tracking-wider uppercase">
                            <span class="material-symbols-outlined text-sm mr-1 filled">verified_user</span>
                            Pago Encriptado SSL
                        </span>
                        <h1 class="text-4xl font-extrabold text-on-surface tracking-tight">Finalizar Compra</h1>
                        <p class="text-on-surface-variant text-lg">Introduce tus datos financieros para procesar el
                            pedido de forma segura.</p>
                    </div>

                    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-custom">
                        <form @submit.prevent="processPayment" class="space-y-6">

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-on-surface-variant ml-1">Titular de la
                                    Tarjeta</label>
                                <div class="relative group">
                                    <input v-model="form.cardholder"
                                        class="w-full bg-surface-container-low border-none rounded-lg py-4 px-12 focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all text-on-surface"
                                        placeholder="Nombre como aparece en la tarjeta" type="text" />
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary">person</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-on-surface-variant ml-1">Número de
                                    Tarjeta</label>
                                <div class="relative group">
                                    <input :value="form.cardNumber" @input="formatCardNumber" @keydown="onlyDigitsKeydown"
                                        class="w-full bg-surface-container-low border-none rounded-lg py-4 px-12 focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all text-on-surface"
                                        placeholder="0000 0000 0000 0000" type="text" inputmode="numeric" maxlength="19" />
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary">credit_card</span>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 flex gap-1 opacity-60">
                                        <div class="w-8 h-5 bg-on-surface-variant/20 rounded-sm"></div>
                                        <div class="w-8 h-5 bg-on-surface-variant/20 rounded-sm"></div>
                                    </div>
                                </div>
                                <p v-if="errors.cardNumber"
                                    class="text-error text-xs font-medium mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">error</span>
                                    El número de tarjeta no es válido
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-on-surface-variant ml-1">Fecha de
                                        Expiración</label>
                                    <div class="relative group">
                                        <input :value="form.expiry" @input="formatExpiry"
                                            class="w-full bg-surface-container-low border-none rounded-lg py-4 px-12 focus:ring-2 transition-all text-on-surface"
                                            :class="errors.expiry ? 'ring-2 ring-error bg-error/5' : 'focus:ring-primary focus:bg-surface-container-lowest'"
                                            placeholder="MM/YY" maxlength="5" type="text" inputmode="numeric" />
                                        <span
                                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary">calendar_month</span>
                                    </div>
                                    <p v-if="errors.expiry" class="text-error text-xs font-medium mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">error</span>
                                        La fecha de expiración debe ser mayor al mes actual
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-on-surface-variant ml-1">CVV</label>
                                    <div class="relative group">
                                        <input v-model="form.cvv"
                                            class="w-full bg-surface-container-low border-none rounded-lg py-4 px-12 focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all text-on-surface"
                                            maxlength="4" placeholder="***" type="password" />
                                        <span
                                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary">lock</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 bg-surface-container-low rounded-lg">
                                <input v-model="form.saveCard" id="save-card" type="checkbox"
                                    class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary" />
                                <label class="text-sm text-on-surface-variant font-medium cursor-pointer"
                                    for="save-card">
                                    Guardar esta tarjeta para futuras compras
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold text-lg rounded-lg shadow-lg hover:shadow-primary/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                Pagar Ahora ${{ total.toLocaleString() }}
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </form>
                    </div>
                </section>

                <aside class="lg:col-span-5 space-y-8">
                    <div class="bg-surface-container-low rounded-xl p-8 space-y-6 sticky top-28">
                        <h3 class="text-xl font-bold text-on-surface">Resumen del Pedido</h3>

                        <div class="space-y-4">
                            <div v-for="item in cartItems" :key="item.idCarrito"
                                class="flex items-center gap-4 bg-surface-container-lowest p-3 rounded-lg">
                                <div class="w-16 h-16 bg-surface-variant rounded-lg flex items-center justify-center overflow-hidden">
                                    <img v-if="item.producto?.Imagenes" :src="item.producto.Imagenes.startsWith('http') ? item.producto.Imagenes : `${storageUrl}/${item.producto.Imagenes}`" class="w-full h-full object-cover" />
                                    <span v-else class="material-symbols-outlined text-primary text-3xl">shopping_bag</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-on-surface line-clamp-1">{{ item.producto?.Nombre || item.Seleccion }}</p>
                                    <p class="text-xs text-on-surface-variant">Cant: {{ item.Cantidad }}</p>
                                </div>
                                <p class="font-bold text-on-surface">${{ parseFloat(item.producto?.Precio || 0).toLocaleString() }}</p>
                            </div>
                        </div>

                        <div class="border-t border-outline-variant/30 pt-6 space-y-3">
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Subtotal</span>
                                <span>${{ total.toLocaleString() }}</span>
                            </div>
                            <div class="flex justify-between text-2xl font-black text-on-surface pt-2">
                                <span>Total</span>
                                <span class="text-primary">${{ total.toLocaleString() }}</span>
                            </div>
                        </div>

                        <div class="bg-tertiary/5 border border-tertiary/10 p-4 rounded-lg flex gap-3">
                            <span class="material-symbols-outlined text-tertiary filled">workspace_premium</span>
                            <div class="text-sm">
                                <p class="font-bold text-tertiary">Garantía Conectatec</p>
                                <p class="text-on-surface-variant text-xs">Protección de seguridad de grado bancario.
                                </p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        <footer class="bg-surface-container-low py-12 px-8 border-t border-outline-variant/10">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-6">
                    <span class="text-xl font-bold tracking-tighter text-on-surface/50">Conectatec</span>
                    <p class="text-sm text-on-surface-variant">© 2026 Portal de Pagos Seguro.</p>
                </div>
                <div class="flex gap-6">
                    <a class="text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors"
                        href="#">Privacidad</a>
                    <a class="text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors"
                        href="#">Términos</a>
                </div>
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