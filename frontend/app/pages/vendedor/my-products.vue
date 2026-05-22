<script setup lang="ts">
import SideNavBar from '~/components/vendedor/dashboard/SideNavBar.vue';
import TopNavBar from '~/components/vendedor/dashboard/TopNavBar.vue';
import { useProductos } from '~/composables/useProductos';
import { onMounted, ref, computed } from 'vue';

const { obtenerMisProductos, eliminarProducto, isLoading, error } = useProductos();
const productos = ref<any[]>([]);
const { storageUrl } = useApiUrl();

const showingDeleteModal = ref(false);
const productoAEliminar = ref<any>(null);
const isDeleting = ref(false);

const confirmarEliminar = (producto: any) => {
    productoAEliminar.value = producto;
    showingDeleteModal.value = true;
};

const cerrarModal = () => {
    showingDeleteModal.value = false;
    productoAEliminar.value = null;
};

const ejecutarEliminacion = async () => {
    if (!productoAEliminar.value) return;
    
    isDeleting.value = true;
    const { success } = await eliminarProducto(productoAEliminar.value.id_producto || productoAEliminar.value.id);
    if (success) {
        productos.value = productos.value.filter(p => (p.id_producto || p.id) !== (productoAEliminar.value.id_producto || productoAEliminar.value.id));
        cerrarModal();
    } else {
        alert('Ocurrió un error al eliminar. ' + (error.value || ''));
    }
    isDeleting.value = false;
};

const totalProductos = computed(() => productos.value.length);
const stockBajo = computed(() => productos.value.filter((p: any) => p.Stock < 10).length);

const getImageUrl = (imagenPath: string | null) => {
    if (!imagenPath) return 'https://via.placeholder.com/400';
    if (imagenPath.startsWith('http') || imagenPath.startsWith('data:')) {
        return imagenPath;
    }
    return `${storageUrl}/${imagenPath}`;
};

onMounted(async () => {
    const response = await obtenerMisProductos();
    if (response.success && response.data?.data) {
        productos.value = response.data.data;
    }
});</script>

<template>
    <!-- SideNavBar (Authority: JSON & Design System) -->
    <SideNavBar />
    <!-- Main Canvas Area -->
    <TopNavBar />
    <main class="md:ml-64 pt-20 px-6 pb-12">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Mis Productos</h1>
                    <p class="text-on-surface-variant max-w-lg">Gestiona tu inventario, actualiza precios y supervisa el
                        rendimiento de tus productos tecnológicos en tiempo real.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative group">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
                        <input
                            class="pl-10 pr-4 py-2.5 bg-surface-container-low border-none rounded-xl w-64 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="Buscar productos..." type="text" />
                    </div>
                    <button
                        class="p-2.5 bg-surface-container-low text-on-surface-variant rounded-xl hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined">filter_list</span>
                    </button>
                </div>
            </div>
            <!-- Bento-Style Product Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                    <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest mb-1">Total
                        Productos</p>
                    <p class="text-2xl font-bold text-on-surface">{{ totalProductos }}</p>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                    <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest mb-1">Stock Bajo
                    </p>
                    <p class="text-2xl font-bold text-error">{{ stockBajo }}</p>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                    <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest mb-1">Ventas Hoy
                    </p>
                    <p class="text-2xl font-bold text-primary">+$1,420</p>
                </div>
                <div class="bg-tertiary-fixed p-6 rounded-xl shadow-sm">
                    <p class="text-xs font-bold text-on-tertiary-fixed-variant uppercase tracking-widest mb-1">Status
                        Cuenta</p>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-tertiary text-lg"
                            style="font-variation-settings: 'FILL' 1;">verified</span>
                        <p class="text-sm font-bold text-on-tertiary-fixed">Merchant Verificado</p>
                    </div>
                </div>
            </div>
            <!-- Products List Area -->
            <div v-if="isLoading" class="flex justify-center items-center py-16">
                <span class="material-symbols-outlined animate-spin text-primary text-4xl">progress_activity</span>
            </div>

            <section v-else class="space-y-6">
                <div v-for="producto in productos" :key="producto.id_producto || producto.id"
                    class="bg-surface-container-lowest p-5 rounded-xl flex flex-col md:flex-row items-center gap-6 hover:shadow-lg transition-all border border-outline-variant/5">
                    <div class="w-24 h-24 rounded-lg bg-surface-container-low overflow-hidden flex-shrink-0">
                        <img :alt="producto.Nombre" class="w-full h-full object-cover"
                            :src="getImageUrl(producto.Imagenes)" />
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-lg font-bold text-on-surface mb-1">{{ producto.Nombre }}</h3>
                        <p class="text-xs font-medium text-on-surface-variant/70 mb-2">Categoría: {{ producto.categoria || producto.Categoria }}</p>
                        <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                            <span class="px-3 py-1 bg-surface-container text-on-surface-variant text-[10px] font-bold uppercase rounded-full tracking-wider">
                                SKU: PROD-{{ producto.id_producto || producto.id }}
                            </span>
                        </div>
                    </div>
                    <div class="w-32 text-center">
                        <p class="text-xs font-bold text-on-surface-variant/60 uppercase mb-1">Precio</p>
                        <p class="text-xl font-extrabold text-on-surface">${{ Number(producto.Precio).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</p>
                    </div>
                    <div class="w-32 text-center">
                        <p class="text-xs font-bold text-on-surface-variant/60 uppercase mb-1">Stock</p>
                        <div class="flex items-center justify-center gap-2">
                            <div
                                class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden max-w-[60px]">
                                <div :class="producto.Stock < 10 ? 'bg-error' : 'bg-primary'" class="h-full" :style="`width: ${Math.min(100, (producto.Stock / 50) * 100)}%`"></div>
                            </div>
                            <span class="text-sm font-bold" :class="producto.Stock < 10 ? 'text-error' : 'text-on-surface'">{{ producto.Stock }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="$router.push(`/vendedor/update-product?id=${producto.id_producto || producto.id}`)"
                            class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-semibold text-sm hover:bg-primary-container transition-all active:scale-95">
                            Actualizar
                        </button>
                        <button @click="confirmarEliminar(producto)"
                            class="p-2.5 text-on-surface-variant hover:bg-error/10 hover:text-error rounded-lg transition-colors">
                            <span class="material-symbols-outlined transition-colors">delete</span>
                        </button>
                    </div>
                </div>

                <div v-if="productos.length === 0" class="text-center py-16 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inventory_2</span>
                    <p class="font-medium">No tienes productos registrados aún.</p>
                </div>
            </section>
            <!-- Bottom Pagination (Invisible lines rule applied) -->
            <div class="mt-12 flex items-center justify-between py-6">
                <p class="text-sm font-medium text-on-surface-variant/60">Mostrando <span
                        class="text-on-surface font-bold">1 - {{ Math.min(10, totalProductos) }}</span> de <span
                        class="text-on-surface font-bold">{{ totalProductos }}</span> productos</p>
                <div class="flex gap-2">
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary text-on-primary font-bold">1</button>
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-colors font-bold">2</button>
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-colors font-bold">3</button>
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Delete Confirmation Modal -->
        <div v-if="showingDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl w-full max-w-md p-6 shadow-2xl">
                <div class="flex items-center gap-4 text-error mb-4">
                    <span class="material-symbols-outlined text-4xl">warning</span>
                    <h3 class="text-xl font-bold text-on-surface">Confirmar Eliminación</h3>
                </div>
                <p class="text-on-surface-variant mb-6">
                    Estás a punto de eliminar permanentemente el producto <span class="font-bold text-on-surface">"{{ productoAEliminar?.Nombre }}"</span>. Esta acción no se puede deshacer.
                </p>
                <div class="flex items-center justify-end gap-3 mt-8">
                    <button @click="cerrarModal" :disabled="isDeleting" class="px-5 py-2.5 rounded-lg text-on-surface-variant font-bold hover:bg-surface-container-high transition-colors">
                        Cancelar
                    </button>
                    <button @click="ejecutarEliminacion" :disabled="isDeleting" class="px-6 py-2.5 bg-error text-on-error rounded-lg font-bold flex items-center gap-2 hover:bg-error/90 transition-colors shadow-lg shadow-error/20">
                        <span v-if="isDeleting" class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                        <span class="material-symbols-outlined text-[20px]" v-else>delete_forever</span>
                        {{ isDeleting ? 'Eliminando...' : 'Eliminar' }}
                    </button>
                </div>
            </div>
        </div>
    </main>
</template>
