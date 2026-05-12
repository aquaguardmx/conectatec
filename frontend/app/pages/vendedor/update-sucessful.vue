<template>
    <div
        class="bg-surface font-body text-on-surface antialiased min-h-screen flex flex-col items-center justify-center p-6">

        <main class="w-full max-w-2xl flex flex-col items-center space-y-12">

            <div class="text-center space-y-6">
                <div
                    class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-primary-container/10 relative">
                    <div class="absolute inset-0 rounded-full border-4 border-primary/20 animate-pulse"></div>
                    <div
                        class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary to-primary-container rounded-full text-white shadow-lg">
                        <span class="material-symbols-outlined text-4xl"
                            style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl md:text-4xl font-headline font-extrabold tracking-tight text-on-surface">
                        ¡Producto Actualizado Correctamente!
                    </h1>
                    <p class="text-on-surface-variant max-w-md mx-auto">
                        Los cambios se han guardado de forma segura en tu inventario y ya están visibles para tus
                        clientes.
                    </p>
                </div>
            </div>

            <div
                class="w-full bg-surface-container-lowest rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.06)] overflow-hidden flex flex-col md:flex-row border border-outline-variant/20">
                <div class="w-full md:w-48 h-48 md:h-auto relative overflow-hidden bg-surface-container-low">
                    <img alt="Resumen del producto" class="w-full h-full object-cover" :src="getImageUrl" />
                    <div class="absolute top-3 left-3">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full bg-tertiary text-on-tertiary text-xs font-bold tracking-wider uppercase">
                            <span class="material-symbols-outlined text-sm mr-1"
                                style="font-variation-settings: 'FILL' 1;">verified_user</span>
                            Verificado
                        </span>
                    </div>
                </div>

                <div class="flex-1 p-8 space-y-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-primary tracking-widest uppercase mb-1 block">{{
                                producto.Categoria || producto.categoria }}</span>
                            <h2 class="text-xl font-bold text-on-surface">{{ producto.Nombre }}</h2>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-extrabold text-on-surface">${{
                                Number(producto.Precio).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</span>
                            <span class="text-xs text-on-surface-variant block">Stock: {{ producto.Stock }}
                                unidades</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <div
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container text-on-secondary-fixed-variant text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span class="font-medium">Precio Ajustado</span>
                        </div>
                        <div
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container text-on-secondary-fixed-variant text-sm">
                            <span class="material-symbols-outlined text-sm">description</span>
                            <span class="font-medium">SEO Optimizado</span>
                        </div>
                        <div
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container text-on-secondary-fixed-variant text-sm">
                            <span class="material-symbols-outlined text-sm">image</span>
                            <span class="font-medium">Galería Actualizada</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-outline-variant/20 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-base">history</span>
                            <span>Última modificación: Hace un momento</span>
                        </div>
                        <button class="text-primary font-bold hover:underline inline-flex items-center gap-1">
                            Ver ficha pública
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button @click="$router.push('/vendedor/my-products')"
                    class="w-full sm:w-auto px-8 py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-xl shadow-lg hover:shadow-primary/20 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">inventory_2</span>
                    Ver Mis Productos
                </button>
                <button @click="$router.push('/vendedor/dashboard')"
                    class="w-full sm:w-auto px-8 py-4 bg-secondary-container text-on-secondary-container font-bold rounded-xl hover:bg-surface-container-high active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">dashboard</span>
                    Ir al Dashboard
                </button>
            </div>

            <div class="pt-4" v-if="producto.id_producto || producto.id">
                <button @click="$router.push('/vendedor/update-product?id=' + (producto.id_producto || producto.id))"
                    class="text-on-surface-variant hover:text-primary font-medium text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Seguir editando este producto
                </button>
            </div>
        </main>

        <div class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-5%] left-[-5%] w-[30%] h-[30%] bg-tertiary/5 rounded-full blur-[100px]"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const producto = ref({
    id: '',
    id_producto: '',
    Nombre: 'Producto Modificado',
    Categoria: 'General',
    Descripcion: '',
    Precio: 0.00,
    Stock: 0,
    Imagenes: null
});

// Rescatar propiedad pasada desde update-product
if (typeof window !== 'undefined' && window.history.state?.producto) {
    producto.value = { ...producto.value, ...window.history.state.producto };
}

const getImageUrl = computed(() => {
    const imagenPath = producto.value.Imagenes;
    if (!imagenPath) return 'https://lh3.googleusercontent.com/aida-public/AB6AXuAdKCdxeTDMjm1UjJ8i5gtoATwxNPl8CREOXpTrHPS7TnTquvFSPwooXByQ3VcoLzvcWfK1Xs2573rWTDRmA-2MqSZGF71WhqxiZQQqZd3nayZdvQ4N78Xz2wdeWLDU-vPh7St2uSA5DwG6YUDAp2M9reEcN3vO1JVdVcdUbu_d7FKKbCzGuGp_48X0_zQjoLUUWdrcOVdZrv0PADKCPkl-zVBdOFWTA6iv85WQe9C9vEeiytDDbrKNzOc_0xqoHqsvR4Pnup4mNUQ';
    if (imagenPath.startsWith('http') || imagenPath.startsWith('data:')) {
        return imagenPath;
    }
    return `http://localhost:8000/storage/${imagenPath}`;
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.glass-panel {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
</style>