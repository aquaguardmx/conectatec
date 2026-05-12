<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import Header from '../../components/cliente/Header.vue';

const router = useRouter();

// Puntos de entrega disponibles en la universidad
const puntosEntrega = [
    { id: 'biblioteca', nombre: 'Biblioteca Central', descripcion: 'Planta baja, mostrador principal', icono: 'local_library', color: 'from-blue-500 to-blue-600' },
    { id: 'cafeteria', nombre: 'Cafetería Principal', descripcion: 'Edificio central, junto a la entrada', icono: 'local_cafe', color: 'from-amber-500 to-orange-500' },
    { id: 'edificio_a', nombre: 'Edificio A — Aulas', descripcion: 'Recepción, planta baja', icono: 'school', color: 'from-purple-500 to-purple-600' },
    { id: 'edificio_b', nombre: 'Edificio B — Laboratorios', descripcion: 'Pasillo principal, área de espera', icono: 'science', color: 'from-teal-500 to-teal-600' },
    { id: 'cancha', nombre: 'Cancha Deportiva', descripcion: 'Entrada norte, banca de espera', icono: 'sports_soccer', color: 'from-green-500 to-green-600' },
    { id: 'rectoria', nombre: 'Rectoría', descripcion: 'Recepción, primer piso', icono: 'account_balance', color: 'from-indigo-500 to-indigo-600' },
    { id: 'entrada', nombre: 'Entrada Principal', descripcion: 'Caseta de vigilancia, lado derecho', icono: 'door_front', color: 'from-rose-500 to-rose-600' },
];

// Franjas horarias disponibles
const horasDisponibles = [
    { valor: '10:00', etiqueta: '10:00 AM' },
    { valor: '12:00', etiqueta: '12:00 PM' },
    { valor: '14:00', etiqueta: '2:00 PM' },
    { valor: '16:00', etiqueta: '4:00 PM' },
];

const puntoSeleccionado = ref('');
const fechaSeleccionada = ref('');
const horaSeleccionada = ref('');
const errorMsg = ref('');

// Fecha mínima: mañana; máxima: 7 días
const fechaMin = computed(() => {
    const d = new Date();
    d.setDate(d.getDate() + 1);
    return d.toISOString().split('T')[0];
});
const fechaMax = computed(() => {
    const d = new Date();
    d.setDate(d.getDate() + 7);
    return d.toISOString().split('T')[0];
});

const puntoActual = computed(() =>
    puntosEntrega.find(p => p.id === puntoSeleccionado.value)
);

const fechaFormateada = computed(() => {
    if (!fechaSeleccionada.value) return '';
    const d = new Date(fechaSeleccionada.value + 'T12:00:00');
    return d.toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long' });
});

const confirmar = () => {
    errorMsg.value = '';
    if (!puntoSeleccionado.value) {
        errorMsg.value = 'Por favor selecciona un punto de entrega.';
        return;
    }
    if (!fechaSeleccionada.value) {
        errorMsg.value = 'Por favor selecciona una fecha de entrega.';
        return;
    }
    if (!horaSeleccionada.value) {
        errorMsg.value = 'Por favor selecciona una hora de entrega.';
        return;
    }

    // Guardamos en sessionStorage para que confirmacion-pago lo use
    sessionStorage.setItem('entregaDetails', JSON.stringify({
        punto_entrega: puntoActual.value?.nombre,
        fecha_entrega: fechaSeleccionada.value,
        hora_entrega: horaSeleccionada.value,
    }));

    router.push('/cliente/metodo-pago');
};
</script>

<template>
    <div class="bg-background text-on-surface font-body min-h-screen">
        <Header />

        <main class="pt-32 pb-24 px-4 md:px-8 max-w-5xl mx-auto">

            <!-- Breadcrumb paso -->
            <div class="flex items-center gap-2 mb-8">
                <span class="text-xs font-bold uppercase tracking-widest text-primary/60">Paso 01</span>
                <div class="h-[1px] w-8 bg-outline-variant/30"></div>
                <span class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Punto de Entrega</span>
            </div>

            <div class="mb-10">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter text-on-surface mb-3 leading-tight">
                    ¿Dónde recibes tu pedido?
                </h1>
                <p class="text-on-surface-variant text-lg max-w-xl">
                    Elige un punto de entrega dentro del campus universitario, la fecha y la hora que mejor te funcionen.
                </p>
            </div>

            <!-- Puntos de entrega -->
            <section class="mb-10">
                <h2 class="text-sm font-black uppercase tracking-widest text-on-surface-variant mb-4">
                    <span class="material-symbols-outlined align-middle text-base mr-1" style="font-variation-settings: 'FILL' 1;">location_on</span>
                    Puntos disponibles
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <button
                        v-for="punto in puntosEntrega"
                        :key="punto.id"
                        @click="puntoSeleccionado = punto.id"
                        class="relative text-left p-5 rounded-2xl border-2 transition-all duration-200 group hover:shadow-lg active:scale-[0.97]"
                        :class="puntoSeleccionado === punto.id
                            ? 'border-primary bg-primary/5 shadow-md shadow-primary/10'
                            : 'border-outline-variant/20 bg-surface-container-lowest hover:border-primary/40'"
                    >
                        <!-- Indicador seleccionado -->
                        <div v-if="puntoSeleccionado === punto.id"
                            class="absolute top-3 right-3 w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-xs" style="font-size:12px; font-variation-settings:'FILL' 1;">check</span>
                        </div>

                        <!-- Icono con gradiente -->
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br mb-4 flex items-center justify-center text-white shadow-sm"
                            :class="punto.color">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">{{ punto.icono }}</span>
                        </div>

                        <h3 class="font-bold text-on-surface text-sm mb-1 leading-tight">{{ punto.nombre }}</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">{{ punto.descripcion }}</p>
                    </button>
                </div>
            </section>

            <!-- Fecha y hora -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <!-- Fecha -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/20 shadow-sm">
                    <h2 class="text-sm font-black uppercase tracking-widest text-on-surface-variant mb-4">
                        <span class="material-symbols-outlined align-middle text-base mr-1" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
                        Fecha de entrega
                    </h2>
                    <input
                        v-model="fechaSeleccionada"
                        type="date"
                        :min="fechaMin"
                        :max="fechaMax"
                        class="w-full bg-surface-container-low border-2 border-outline-variant/20 rounded-xl py-4 px-4 text-on-surface font-semibold focus:outline-none focus:border-primary transition-colors"
                    />
                    <p v-if="fechaFormateada" class="text-sm text-primary font-semibold mt-2 capitalize">
                        📅 {{ fechaFormateada }}
                    </p>
                    <p v-else class="text-xs text-on-surface-variant mt-2">Disponible de mañana a 7 días.</p>
                </div>

                <!-- Hora -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/20 shadow-sm">
                    <h2 class="text-sm font-black uppercase tracking-widest text-on-surface-variant mb-4">
                        <span class="material-symbols-outlined align-middle text-base mr-1" style="font-variation-settings: 'FILL' 1;">schedule</span>
                        Franja horaria
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            v-for="hora in horasDisponibles"
                            :key="hora.valor"
                            @click="horaSeleccionada = hora.valor"
                            class="py-4 px-3 rounded-xl border-2 font-bold text-sm transition-all duration-200 active:scale-[0.97]"
                            :class="horaSeleccionada === hora.valor
                                ? 'border-primary bg-primary text-on-primary shadow-md'
                                : 'border-outline-variant/20 bg-surface-container-low text-on-surface hover:border-primary/40'"
                        >
                            <span class="material-symbols-outlined text-sm align-middle mr-1" style="font-variation-settings: 'FILL' 1;">schedule</span>
                            {{ hora.etiqueta }}
                        </button>
                    </div>
                </div>
            </section>

            <!-- Resumen seleccionado -->
            <div v-if="puntoSeleccionado && fechaSeleccionada && horaSeleccionada"
                class="mb-8 p-5 rounded-2xl bg-primary/5 border border-primary/20 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br flex items-center justify-center text-white shadow-sm flex-shrink-0"
                    :class="puntoActual?.color">
                    <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">{{ puntoActual?.icono }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-on-surface">{{ puntoActual?.nombre }}</p>
                    <p class="text-sm text-on-surface-variant capitalize">{{ fechaFormateada }} · {{ horasDisponibles.find(h => h.valor === horaSeleccionada)?.etiqueta }}</p>
                </div>
                <span class="material-symbols-outlined text-primary text-2xl flex-shrink-0" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>

            <!-- Error -->
            <p v-if="errorMsg" class="mb-4 text-error text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">error</span>
                {{ errorMsg }}
            </p>

            <!-- Botones de acción -->
            <div class="flex items-center gap-4">
                <button @click="router.push('/cliente/carrito')"
                    class="flex items-center gap-2 px-6 py-4 text-primary font-bold hover:bg-primary/5 rounded-xl transition-colors active:scale-95">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Volver al carrito
                </button>
                <button @click="confirmar"
                    class="flex-1 max-w-xs bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-primary/30 transition-all active:scale-95 flex items-center justify-center gap-3">
                    Continuar al pago
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </main>
    </div>
</template>

<style scoped>
.font-headline, .font-body {
    font-family: 'Inter', sans-serif;
}
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
