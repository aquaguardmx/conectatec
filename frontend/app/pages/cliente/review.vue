<template>
    <div class="bg-background font-body text-on-surface antialiased min-h-screen flex flex-col">
        <ClienteHeader />

        <div class="flex-1 flex flex-col">
            <!-- Main Content -->
            <main class="p-6 md:p-12 lg:p-20 flex flex-col items-center">
                <div class="w-full max-w-5xl">
                    <header class="space-y-4 mb-12">
                        <h1 class="text-4xl md:text-5xl font-black tracking-tighter">Mis Compras</h1>
                        <p class="text-lg text-on-surface-variant">Califica los productos que has comprado y ayuda a otros usuarios.</p>
                    </header>

                    <div v-if="isLoadingCompras" class="flex justify-center py-20">
                        <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    
                    <div v-else-if="compras.length === 0" class="text-center py-20 bg-surface-container-lowest rounded-xl">
                        <span class="material-symbols-outlined text-6xl text-outline-variant mb-4">shopping_bag</span>
                        <h2 class="text-2xl font-bold text-on-surface">No hay compras aún</h2>
                        <p class="text-on-surface-variant mt-2">Tus productos comprados aparecerán aquí para que los califiques.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="compra in compras" :key="compra.producto.id_producto" class="bg-surface-container-low rounded-xl p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow border border-outline-variant/10">
                            <div class="flex gap-6 mb-6">
                                <div class="w-24 h-24 rounded-lg bg-surface-container-lowest flex items-center justify-center overflow-hidden shrink-0 border border-outline-variant/20">
                                    <img :src="compra.producto.Imagenes?.startsWith('http') ? compra.producto.Imagenes : `${storageUrl}/${compra.producto.Imagenes}`" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-on-surface line-clamp-2">{{ compra.producto.Nombre }}</h3>
                                    <p class="text-on-surface-variant text-sm mt-1">{{ compra.producto.categoria || 'Producto' }}</p>
                                </div>
                            </div>
                            
                            <div v-if="compra.ya_calificado" class="bg-surface-container-lowest p-4 rounded-lg border border-outline-variant/10">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-primary flex items-center gap-1"><span class="material-symbols-outlined text-sm">check_circle</span> Ya calificado</span>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1; color: #ffb400;">star</span>
                                        <span class="font-bold text-sm">{{ compra.resena.Puntuacion }}.0</span>
                                    </div>
                                </div>
                                <p class="text-sm text-on-surface-variant italic line-clamp-2">"{{ compra.resena.Comentario }}"</p>
                            </div>
                            
                            <button v-else @click="abrirModal(compra.producto)" class="w-full py-3 bg-primary/10 text-primary hover:bg-primary/20 font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">rate_review</span>
                                Dejar Reseña
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal de Reseña -->
                <div v-if="productoSeleccionado" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
                        <div class="px-8 py-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-lowest sticky top-0 z-10">
                            <h2 class="text-2xl font-black text-on-surface">Calificar Producto</h2>
                            <button @click="cerrarModal" class="p-2 hover:bg-surface-container-highest rounded-full transition-colors text-on-surface-variant">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        
                        <div class="p-8 overflow-y-auto">
                            <div class="flex items-center gap-6 mb-8 bg-surface-container-low p-4 rounded-xl">
                                <div class="w-16 h-16 rounded-lg bg-surface-container-lowest overflow-hidden border border-outline-variant/20 shrink-0">
                                    <img :src="productoSeleccionado.Imagenes?.startsWith('http') ? productoSeleccionado.Imagenes : `${storageUrl}/${productoSeleccionado.Imagenes}`" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-on-surface line-clamp-1">{{ productoSeleccionado.Nombre }}</h3>
                                    <span class="bg-tertiary/10 text-tertiary text-[10px] font-bold px-2 py-0.5 rounded-full uppercase flex items-center w-fit gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">verified</span> Compra Verificada
                                    </span>
                                </div>
                            </div>

                            <form @submit.prevent="handleSubmit" class="space-y-8">
                                <div class="space-y-4">
                                    <label class="block text-sm font-bold uppercase tracking-widest text-on-surface-variant">Calificación General</label>
                                    <div class="flex items-center gap-4 bg-surface-container-low p-6 rounded-xl justify-center">
                                        <StarRating v-model="form.rating" />
                                        <span class="text-2xl font-black text-primary ml-4">{{ form.rating.toFixed(1) }} <span class="text-on-surface-variant font-medium text-lg">/ 5.0</span></span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between items-end mb-2">
                                        <label class="text-sm font-bold uppercase tracking-widest text-on-surface-variant" for="comments">Tu Experiencia</label>
                                        <span class="text-xs font-medium" :class="form.comment.length < 20 && form.comment.length > 0 ? 'text-error' : 'text-on-surface-variant'">
                                            {{ form.comment.length }} / Mín. 20 caracteres
                                        </span>
                                    </div>
                                    <textarea v-model="form.comment" id="comments"
                                        class="w-full bg-surface-container-low border-2 border-transparent focus:border-primary/30 rounded-xl p-5 text-base min-h-[150px] outline-none transition-colors resize-none text-on-surface"
                                        placeholder="¿Qué te pareció este producto? Cuéntanos los detalles..."></textarea>
                                </div>

                                <div class="pt-6 flex flex-col sm:flex-row items-center justify-end gap-4">
                                    <button type="button" @click="cerrarModal" class="w-full sm:w-auto px-6 py-3 font-bold text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit" :disabled="isSubmitting || form.comment.length < 20"
                                        class="w-full sm:w-auto px-8 py-3 bg-primary text-on-primary rounded-xl font-bold hover:shadow-lg hover:shadow-primary/30 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100 flex items-center justify-center gap-2">
                                        <span v-if="isSubmitting" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                        <span v-else class="material-symbols-outlined">send</span>
                                        {{ isSubmitting ? 'Enviando...' : 'Publicar Reseña' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import ClienteHeader from '~/components/cliente/Header.vue';
import StarRating from '~/components/cliente/StarRating.vue';
import { useResenas } from '~/composables/useResenas';

const { obtenerComprasPendientes, enviarResena } = useResenas();
const { storageUrl } = useApiUrl();

const compras = ref([]);
const isLoadingCompras = ref(true);

const fetchCompras = async () => {
    isLoadingCompras.value = true;
    const response = await obtenerComprasPendientes();
    if (response.success) {
        compras.value = response.data;
    }
    isLoadingCompras.value = false;
};

onMounted(() => {
    fetchCompras();
});

const productoSeleccionado = ref(null);
const isSubmitting = ref(false);

const form = reactive({
    rating: 5,
    comment: '',
});

const abrirModal = (producto) => {
    productoSeleccionado.value = producto;
    form.rating = 5;
    form.comment = '';
};

const cerrarModal = () => {
    productoSeleccionado.value = null;
};

const handleSubmit = async () => {
    if (form.comment.length < 20) {
        return;
    }
    
    isSubmitting.value = true;
    const response = await enviarResena(productoSeleccionado.value.id_producto, form.rating, form.comment);
    isSubmitting.value = false;

    if (response.success) {
        // Actualizar la lista localmente
        const compra = compras.value.find(c => c.producto.id_producto === productoSeleccionado.value.id_producto);
        if (compra) {
            compra.ya_calificado = true;
            compra.resena = response.data.data;
        }
        cerrarModal();
        alert('¡Reseña enviada con éxito!');
    } else {
        alert(response.error || 'Error al enviar la reseña');
    }
};
</script>