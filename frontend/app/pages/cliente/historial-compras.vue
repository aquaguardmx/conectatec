<template>
    <div class="bg-background text-on-background min-h-screen font-body flex flex-col">
        <ClienteHeader />

        <div class="flex-1 flex flex-col pt-6 md:pt-10">
            <!-- Main Content -->
            <main class="flex-1 p-6 lg:p-10 w-full max-w-6xl mx-auto">
                <header class="mb-10 w-full">
                    <div class="flex items-end gap-4 mb-2">
                        <h1 class="text-4xl font-extrabold tracking-tight text-on-surface">Historial de Compras</h1>
                        <span
                            class="mb-1.5 px-3 py-1 bg-surface-container-high text-primary font-bold text-xs rounded-full">
                            {{ orders.length }} Pedidos
                        </span>
                    </div>
                    <p class="text-on-surface-variant font-medium">Revisa el estado de tus pedidos.</p>
                </header>

                <section class="w-full">
                    <div v-if="isLoading" class="flex justify-center py-20">
                        <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin">
                        </div>
                    </div>
                    <div v-else-if="orders.length === 0"
                        class="text-center py-20 bg-surface-container-lowest rounded-xl">
                        <span class="material-symbols-outlined text-6xl text-outline-variant mb-4">shopping_bag</span>
                        <h2 class="text-2xl font-bold text-on-surface">No hay pedidos aún</h2>
                        <p class="text-on-surface-variant mt-2">Los productos que compres aparecerán aquí.</p>
                    </div>
                    <div v-else
                        class="bg-surface-container-lowest rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.04)] overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-widest font-bold">
                                        <th class="px-6 py-4">No. Pedido</th>
                                        <th class="px-6 py-4">Producto</th>
                                        <th class="px-6 py-4">Fecha</th>
                                        <th class="px-6 py-4">Precio</th>
                                        <th class="px-6 py-4">Estado</th>
                                        <th class="px-6 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-surface-container">
                                    <tr v-for="order in orders" :key="order.id"
                                        class="hover:bg-surface-container-low/30 transition-colors group">
                                        <td class="px-6 py-6 text-sm font-mono text-primary font-bold">#CT-{{ order.id
                                            }}
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-xl bg-surface-container overflow-hidden shrink-0">
                                                    <img :src="order.image" :alt="order.name"
                                                        class="w-full h-full object-cover" />
                                                </div>
                                                <div>
                                                    <p class="font-bold text-on-surface">{{ order.name }}</p>
                                                    <p class="text-xs text-on-surface-variant">{{ order.category }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 text-sm text-on-surface-variant">{{ order.date }}</td>
                                        <td class="px-6 py-6 text-sm font-bold text-on-surface">{{
                                            formatPrice(order.price) }}</td>
                                        <td class="px-6 py-6">
                                            <span :class="statusClasses(order.status)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full"
                                                    :class="statusDotClasses(order.status)"></span>
                                                {{ order.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 text-right">
                                            <button @click="handleAction(order)"
                                                :class="actionButtonClasses(order.status)">
                                                {{ actionButtonText(order.status) }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <!-- Mobile Bottom NavBar -->
        <nav
            class="md:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-xl border-t shadow-[0_-8px_24px_rgba(0,0,0,0.05)] flex items-center justify-around py-3 px-6 z-50">
            <a v-for="item in mobileMenu" :key="item.text" href="#"
                :class="item.active ? 'text-primary' : 'text-slate-400'" class="flex flex-col items-center gap-1">
                <span class="material-symbols-outlined"
                    :style="item.active ? 'font-variation-settings: \'FILL\' 1;' : ''">{{ item.icon }}</span>
                <span class="text-[10px] font-bold">{{ item.text }}</span>
            </a>
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import ClienteHeader from '~/components/cliente/Header.vue';
import { usePedidos } from '~/composables/usePedidos';

const router = useRouter();
const { obtenerPedidos, isLoading } = usePedidos();
const { storageUrl } = useApiUrl();
const orders = ref([]);

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(price);
};

onMounted(async () => {
    const response = await obtenerPedidos();
    if (response.success && response.data?.data) {
        orders.value = response.data.data.map(p => {
            let status = 'Entregado';
            if (p.Recibido == 0 && p.Entregado == 0) status = 'En Camino';
            else if (p.Recibido == 1 && p.Entregado == 0) status = 'En Camino';
            else if (p.Entregado == 1) status = 'Entregado';

            const d = new Date(p.created_at);
            const dateStr = d.toLocaleDateString('es-ES', { month: 'short', day: 'numeric', year: 'numeric' });

            return {
                id: p.NoPedidos,
                name: p.producto?.Nombre || 'Producto Desconocido',
                category: p.producto?.categoria || 'Categoría',
                date: dateStr,
                price: p.producto ? parseFloat(p.producto.Precio) : 0,
                status: status,
                image: p.producto?.Imagenes
                    ? (p.producto.Imagenes.startsWith('http') ? p.producto.Imagenes : `${storageUrl}/${p.producto.Imagenes}`)
                    : 'https://placehold.co/100x100?text=Sin+Imagen',
                producto_id: p.id_producto
            };
        });
    }
});

// Helper functions for dynamic styling
const statusClasses = (status) => {
    switch (status) {
        case 'Entregado': return 'bg-green-100 text-green-700';
        case 'En Camino': return 'bg-blue-100 text-blue-700';
        case 'Cancelado': return 'bg-error-container text-error';
        default: return 'bg-slate-100 text-slate-700';
    }
};

const statusDotClasses = (status) => {
    switch (status) {
        case 'Entregado': return 'bg-green-600';
        case 'En Camino': return 'bg-blue-600';
        case 'Cancelado': return 'bg-error';
        default: return 'bg-slate-600';
    }
};

const actionButtonText = (status) => {
    if (status === 'Entregado') return 'Calificar Producto';
    if (status === 'En Camino') return 'Ver Detalles';
    return 'Ver Detalles';
};

const actionButtonClasses = (status) => {
    if (status === 'En Camino') {
        return 'bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:shadow-lg active:scale-95 transition-all';
    }
    return 'text-primary hover:bg-primary-container/10 px-4 py-2 rounded-lg text-sm font-bold transition-all';
};

const handleAction = (order) => {
    if (order.status === 'Entregado') {
        router.push('/cliente/review');
    } else {
        router.push({ path: '/cliente/detalle', query: { id: order.producto_id } });
    }
};
</script>