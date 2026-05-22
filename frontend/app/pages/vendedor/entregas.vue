<template>
    <div class="bg-background text-on-surface antialiased min-h-screen">
        <SideNavBar />
        <TopNavBar />

        <main class="md:ml-64 p-6 lg:p-12 min-h-screen">
            <div class="max-w-6xl mx-auto">

                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                    <div class="space-y-2">
                        <nav class="flex items-center gap-2 text-[10px] uppercase tracking-widest font-bold text-on-secondary-container">
                            <span>Portal</span>
                            <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                            <span class="text-primary">Entregas</span>
                        </nav>
                        <h2 class="text-4xl font-extrabold tracking-tighter text-on-surface">Mis Entregas</h2>
                        <p class="text-on-surface-variant max-w-lg text-sm leading-relaxed">
                            Consulta los pedidos de tus productos con el punto, fecha y hora de entrega acordados por el comprador.
                        </p>
                    </div>

                    <!-- Filtros -->
                    <div class="flex gap-2 bg-surface-container-low p-1.5 rounded-full border border-outline-variant/10 shadow-inner overflow-x-auto">
                        <button v-for="f in filtros" :key="f.val" @click="filtroActivo = f.val"
                            class="px-6 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap"
                            :class="filtroActivo === f.val ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-on-surface-variant hover:bg-surface-container-high'">
                            {{ f.label }}
                        </button>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                    <div v-for="stat in stats" :key="stat.label"
                        class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/10 shadow-sm">
                        <span class="material-symbols-outlined text-2xl mb-2 block" :class="stat.color"
                            style="font-variation-settings: 'FILL' 1;">{{ stat.icono }}</span>
                        <p class="text-2xl font-black text-on-surface">{{ stat.valor }}</p>
                        <p class="text-xs text-on-surface-variant font-medium mt-1">{{ stat.label }}</p>
                    </div>
                </div>

                <!-- Loading -->
                <div v-if="isLoading" class="flex flex-col items-center justify-center py-20 gap-4">
                    <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                    <p class="text-on-surface-variant font-medium">Cargando entregas...</p>
                </div>

                <!-- Sin resultados -->
                <div v-else-if="pedidosFiltrados.length === 0"
                    class="flex flex-col items-center justify-center py-20 text-center bg-surface-container-lowest rounded-2xl border border-outline-variant/10">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4"
                        style="font-variation-settings: 'FILL' 1;">local_shipping</span>
                    <h3 class="text-2xl font-bold text-on-surface mb-2">Sin entregas {{ filtroActivo !== 'todos' ? 'en esta categoría' : 'aún' }}</h3>
                    <p class="text-on-surface-variant max-w-sm text-sm">Cuando tus productos sean comprados, verás aquí los detalles de entrega.</p>
                </div>

                <!-- Lista de entregas -->
                <div v-else class="space-y-4">
                    <div v-for="pedido in pedidosFiltrados" :key="pedido.idPedidos"
                        class="bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-sm overflow-hidden transition-all hover:shadow-md group">

                        <div class="flex flex-col md:flex-row gap-0">

                            <!-- Banda de color lateral (estado) -->
                            <div class="md:w-1.5 w-full h-1.5 md:h-auto rounded-l-2xl flex-shrink-0"
                                :class="pedido.Entregado ? 'bg-green-500' : esPróximo(pedido.fecha_entrega) ? 'bg-amber-500 animate-pulse' : 'bg-primary'">
                            </div>

                            <div class="flex flex-col sm:flex-row gap-5 p-6 flex-1 items-start sm:items-center">
                                <!-- Imagen producto -->
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-surface-container flex-shrink-0 border border-outline-variant/10">
                                    <img v-if="pedido.producto?.Imagenes"
                                        :src="getImageUrl(pedido.producto.Imagenes)"
                                        :alt="pedido.producto.Nombre"
                                        class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-surface-variant text-3xl">inventory_2</span>
                                    </div>
                                </div>

                                <!-- Info producto y comprador -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                            :class="pedido.Entregado
                                                ? 'bg-green-100 text-green-700'
                                                : esPróximo(pedido.fecha_entrega)
                                                    ? 'bg-amber-100 text-amber-700'
                                                    : 'bg-primary/10 text-primary'">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                :class="pedido.Entregado ? 'bg-green-500' : esPróximo(pedido.fecha_entrega) ? 'bg-amber-500' : 'bg-primary'"></span>
                                            {{ pedido.Entregado ? 'Entregado' : esPróximo(pedido.fecha_entrega) ? 'Hoy / Mañana' : 'Pendiente' }}
                                        </span>
                                        <span class="text-[10px] font-bold text-on-surface-variant">#{{ pedido.NoPedidos }}</span>
                                    </div>
                                    <h3 class="font-bold text-on-surface text-base leading-tight mb-1 truncate">
                                        {{ pedido.producto?.Nombre || 'Producto' }}
                                    </h3>
                                    <p class="text-xs text-on-surface-variant">
                                        <span class="material-symbols-outlined text-xs align-middle mr-0.5">person</span>
                                        {{ pedido.comprador?.nombre || 'Comprador' }}
                                        <span class="mx-1 opacity-30">·</span>
                                        ${{ parseFloat(pedido.producto?.Precio || 0).toLocaleString() }}
                                    </p>
                                </div>

                                <!-- Punto de entrega -->
                                <div class="flex-shrink-0 text-center sm:text-left min-w-[160px]">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Punto de entrega</p>
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings:'FILL' 1;">location_on</span>
                                        <p class="font-bold text-on-surface text-sm">{{ pedido.punto_entrega || 'Por definir' }}</p>
                                    </div>
                                </div>

                                <!-- Fecha y hora -->
                                <div class="flex-shrink-0 text-center sm:text-left min-w-[140px]">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Fecha y hora</p>
                                    <p class="font-bold text-on-surface text-sm capitalize">{{ formatearFecha(pedido.fecha_entrega) }}</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        <span class="material-symbols-outlined text-xs align-middle" style="font-variation-settings:'FILL' 1;">schedule</span>
                                        {{ formatearHora(pedido.hora_entrega) }}
                                    </p>
                                </div>

                                <!-- Acción -->
                                <div class="flex-shrink-0">
                                    <button v-if="!pedido.Entregado"
                                        @click="marcarEntregado(pedido)"
                                        :disabled="pedido._marcando"
                                        class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-xl text-sm shadow-sm hover:shadow-green-500/30 transition-all active:scale-95 disabled:opacity-50">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        {{ pedido._marcando ? 'Guardando...' : 'Entregado' }}
                                    </button>
                                    <div v-else class="flex items-center gap-2 px-5 py-2.5 bg-green-100 text-green-700 font-bold rounded-xl text-sm">
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">check_circle</span>
                                        Completado
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Mobile nav -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-slate-100 px-6 py-3 flex justify-between items-center z-50">
            <a v-for="(link, index) in mobileNav" :key="index" @click.prevent="$router.push(link.path)"
                class="flex flex-col items-center gap-1 cursor-pointer"
                :class="link.active ? 'text-primary' : 'text-slate-400'">
                <span class="material-symbols-outlined" :style="link.active ? `font-variation-settings: 'FILL' 1;` : ''">{{ link.icon }}</span>
                <span class="text-[10px] font-bold">{{ link.text }}</span>
            </a>
            <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                <button @click="$router.push('/vendedor/add-product')"
                    class="w-14 h-14 bg-primary text-white rounded-full shadow-lg shadow-primary/40 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">add</span>
                </button>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useCookie } from '#app'
import SideNavBar from '~/components/vendedor/dashboard/SideNavBar.vue'
import TopNavBar from '~/components/vendedor/dashboard/TopNavBar.vue'

const { storageUrl, apiUrl } = useApiUrl()

const isLoading = ref(false)
const pedidos = ref([])

const filtros = [
    { val: 'todos', label: 'Todos' },
    { val: 'pendientes', label: 'Pendientes' },
    { val: 'proximos', label: 'Hoy / Mañana' },
    { val: 'entregados', label: 'Entregados' },
]
const filtroActivo = ref('todos')

const mobileNav = ref([
    { icon: 'dashboard', text: 'Inicio', active: false, path: '/vendedor/dashboard' },
    { icon: 'local_shipping', text: 'Entregas', active: true, path: '/vendedor/entregas' },
    { icon: 'history_edu', text: 'Historial', active: false, path: '/vendedor/historial' },
    { icon: 'settings', text: 'Perfil', active: false, path: '/vendedor/configuracion' },
])

// Helpers de fecha
const esPróximo = (fecha) => {
    if (!fecha) return false
    const hoy = new Date()
    const d = new Date(fecha + 'T12:00:00')
    const diff = (d - hoy) / (1000 * 60 * 60 * 24)
    return diff >= 0 && diff < 2
}

const formatearFecha = (fecha) => {
    if (!fecha) return 'Por definir'
    const d = new Date(fecha + 'T12:00:00')
    return d.toLocaleDateString('es-MX', { weekday: 'short', day: 'numeric', month: 'short' })
}

const formatearHora = (hora) => {
    const mapa = { '10:00': '10:00 AM', '12:00': '12:00 PM', '14:00': '2:00 PM', '16:00': '4:00 PM' }
    return mapa[hora] || hora || '--'
}

const getImageUrl = (img) => {
    if (!img) return ''
    if (img.startsWith('http') || img.startsWith('data:')) return img
    return `${storageUrl}/${img}`
}

// Estadísticas
const stats = computed(() => [
    { label: 'Total pedidos', valor: pedidos.value.length, icono: 'receipt_long', color: 'text-primary' },
    { label: 'Pendientes', valor: pedidos.value.filter(p => !p.Entregado).length, icono: 'hourglass_empty', color: 'text-amber-500' },
    { label: 'Hoy / Mañana', valor: pedidos.value.filter(p => !p.Entregado && esPróximo(p.fecha_entrega)).length, icono: 'alarm', color: 'text-orange-500' },
    { label: 'Entregados', valor: pedidos.value.filter(p => p.Entregado).length, icono: 'check_circle', color: 'text-green-500' },
])

const pedidosFiltrados = computed(() => {
    if (filtroActivo.value === 'pendientes') return pedidos.value.filter(p => !p.Entregado)
    if (filtroActivo.value === 'proximos') return pedidos.value.filter(p => !p.Entregado && esPróximo(p.fecha_entrega))
    if (filtroActivo.value === 'entregados') return pedidos.value.filter(p => p.Entregado)
    return pedidos.value
})

// Cargar pedidos del vendedor
const cargarEntregas = async () => {
    isLoading.value = true
    try {
        const token = useCookie('auth_token')
        const response = await $fetch(`${apiUrl}/vendedor/entregas`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token.value}`
            }
        })
        pedidos.value = (response.data || []).map(p => ({ ...p, _marcando: false }))
    } catch (e) {
        console.error('Error al cargar entregas:', e)
    } finally {
        isLoading.value = false
    }
}

// Marcar como entregado
const marcarEntregado = async (pedido) => {
    pedido._marcando = true
    try {
        const token = useCookie('auth_token')
        await $fetch(`${apiUrl}/vendedor/entregas/${pedido.idPedidos}/entregar`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token.value}`
            }
        })
        pedido.Entregado = 1
    } catch (e) {
        console.error('Error al marcar entregado:', e)
    } finally {
        pedido._marcando = false
    }
}

onMounted(() => {
    cargarEntregas()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

body { font-family: 'Inter', sans-serif; }

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
