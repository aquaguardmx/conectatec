<template>
    <div class="bg-background text-on-surface antialiased min-h-screen">
        <SideNavBar />
        <TopNavBar />

        <main class="md:ml-64 p-6 lg:p-12 min-h-screen">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                    <div class="space-y-2">
                        <nav
                            class="flex items-center gap-2 text-[10px] uppercase tracking-widest font-bold text-on-secondary-container">
                            <span>Portal</span>
                            <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                            <span class="text-primary">Historial</span>
                        </nav>
                        <h2 class="text-4xl font-extrabold tracking-tighter text-on-surface">Historial de Publicaciones
                        </h2>
                        <p class="text-on-surface-variant max-w-lg text-sm leading-relaxed">
                            Gestiona y supervisa el rendimiento de tus publicaciones activas, revisa los cambios
                            recientes y recupera productos eliminados.
                        </p>
                    </div>

                    <div
                        class="flex gap-2 bg-surface-container-low p-1.5 rounded-full border border-outline-variant/10 shadow-inner overflow-x-auto">
                        <button v-for="filter in filters" :key="filter" @click="activeFilter = filter"
                            class="px-6 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap"
                            :class="activeFilter === filter ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-on-surface-variant hover:bg-surface-container-high'">
                            {{ filter }}
                        </button>
                    </div>
                </div>

                <section
                    class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant/10">
                    <div
                        class="hidden md:grid grid-cols-12 px-8 py-5 bg-surface-container/30 border-b border-outline-variant/10">
                        <div
                            class="col-span-5 text-[11px] font-black uppercase tracking-widest text-on-surface-variant">
                            Producto & Detalles</div>
                        <div
                            class="col-span-3 text-[11px] font-black uppercase tracking-widest text-on-surface-variant">
                            Fecha de Publicación</div>
                        <div
                            class="col-span-2 text-[11px] font-black uppercase tracking-widest text-on-surface-variant">
                            Estado</div>
                        <div
                            class="col-span-2 text-right text-[11px] font-black uppercase tracking-widest text-on-surface-variant">
                            Acciones</div>
                    </div>

                    <div class="divide-y divide-outline-variant/5">
                        <div v-for="pub in filteredPublications" :key="pub.id"
                            class="grid grid-cols-1 md:grid-cols-12 gap-4 px-8 py-6 items-center hover:bg-surface-container-low/50 transition-colors group"
                            :class="{ 'opacity-70 hover:opacity-100': pub.status === 'Eliminado' }">
                            <div class="col-span-1 md:col-span-5 flex items-center gap-5">
                                <div class="relative w-16 h-16 rounded-xl overflow-hidden shadow-sm flex-shrink-0 transition-all duration-500"
                                    :class="{ 'grayscale group-hover:grayscale-0': pub.status === 'Eliminado' }">
                                    <img :src="pub.image" :alt="pub.title"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                    <div v-if="pub.status === 'Actualizado'"
                                        class="absolute inset-0 bg-on-surface/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="material-symbols-outlined text-white text-xl">visibility</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-on-surface group-hover:text-primary transition-colors"
                                        :class="{ 'line-through decoration-error/50': pub.status === 'Eliminado' }">
                                        {{ pub.title }}
                                    </h4>
                                    <p class="text-xs text-on-surface-variant mt-1">Ref: {{ pub.refCode }} • {{
                                        pub.category }}</p>
                                </div>
                            </div>

                            <div class="col-span-1 md:col-span-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-on-surface">{{ pub.date }}</span>
                                    <span class="text-[10px] text-on-surface-variant">{{ pub.timeInfo }}</span>
                                </div>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider"
                                    :class="statusStyles[pub.status].badge">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="statusStyles[pub.status].dot"></span>
                                    {{ pub.status }}
                                </span>
                            </div>

                            <div class="col-span-1 md:col-span-2 text-left md:text-right">
                                <button @click="$router.push(`/vendedor/update-product?id=${pub.id}`)"
                                    class="bg-secondary-container text-on-secondary-container px-5 py-2 rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition-all active:scale-95">
                                    Review
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-surface-container-low/30 border-t border-outline-variant/10">
                        <p class="text-xs font-medium text-on-surface-variant">Mostrando <span
                                class="text-on-surface font-bold">{{ filteredPublications.length }}</span> de <span
                                class="text-on-surface font-bold">{{ publications.length }}</span> publicaciones</p>
                        <div class="flex items-center gap-2">
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/20 hover:bg-white transition-all disabled:opacity-30"
                                disabled>
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-white font-bold text-xs shadow-sm">1</button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-white border border-transparent hover:border-outline-variant/20 transition-all font-bold text-xs">2</button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-white border border-transparent hover:border-outline-variant/20 transition-all font-bold text-xs">3</button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/20 hover:bg-white transition-all">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 gap-6 mt-12 md:grid-cols-2">
                    <div
                        class="p-8 rounded-xl bg-primary text-white flex flex-col justify-between relative overflow-hidden group">
                        <div
                            class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-125 transition-transform duration-700">
                            <span class="material-symbols-outlined text-[120px]"
                                style="font-variation-settings: 'FILL' 1;">insights</span>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-black opacity-80">Rendimiento Mensual
                            </p>
                            <h3 class="text-3xl font-black mt-2">+24% Alcance</h3>
                        </div>
                        <button
                            class="mt-8 w-fit text-xs font-bold underline underline-offset-4 decoration-2 hover:opacity-80 transition-opacity flex items-center gap-2 z-10">
                            Ver Estadísticas
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </div>

                    <div
                        class="p-8 rounded-xl bg-surface-container-low border border-outline-variant/10 flex flex-col justify-between group">
                        <div>
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm text-primary mb-4 group-hover:rotate-12 transition-transform">
                                <span class="material-symbols-outlined"
                                    style="font-variation-settings: 'FILL' 1;">verified</span>
                            </div>
                            <h3 class="font-bold text-on-surface">Merchant Badge</h3>
                            <p class="text-xs text-on-surface-variant mt-2 leading-relaxed">Tu perfil ha cumplido con el
                                98% de entregas a tiempo este mes.</p>
                        </div>
                        <div class="mt-6 flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-white rounded-full overflow-hidden">
                                <div class="w-[98%] h-full bg-primary"></div>
                            </div>
                            <span class="text-[10px] font-black text-primary">98%</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <nav
            class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-slate-100 px-6 py-3 flex justify-between items-center z-50">
            <a v-for="(link, index) in mobileNav" :key="index" @click.prevent="$router.push(link.path)"
                class="flex flex-col items-center gap-1 cursor-pointer"
                :class="link.active ? 'text-primary' : 'text-slate-400'">
                <span class="material-symbols-outlined"
                    :style="link.active ? `font-variation-settings: 'FILL' 1;` : ''">{{ link.icon }}</span>
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
import { useCookie, useRouter } from '#app'
import { useProductos } from '~/composables/useProductos'
import SideNavBar from '~/components/vendedor/dashboard/SideNavBar.vue'
import TopNavBar from '~/components/vendedor/dashboard/TopNavBar.vue'

const router = useRouter()
const { obtenerMisProductos } = useProductos()
const { storageUrl } = useApiUrl()

const userNameCookie = useCookie('user_name')
const userRoleCookie = useCookie('user_role')

// -- User Data --
const user = ref({
    name: userNameCookie.value || 'Vendedor',
    role: userRoleCookie.value === 'vendedor' ? 'Verified Seller' : 'Miembro',
    avatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFgSkzbzro6Jsju_bD8Dv_wKj4MGuTcAToDhUGz_HEfM7XFj8TzQR0yBVXVO4yoVh2KxS4u4XtymxbQ19MCTHiFBuZyE9rQhOti6Ovw93vTs5dNc3_HEP4r9hH0PpYePGlFz8ovWxhDAiuYnPTs4FO9CYjegY3oZ84V3Sp2sUq9GHDH_Ah5uJgTyPUlqtnzwukdslips2MEbWxVkesZtxlkvpiWX395Na3MZ1J9eYhwmQVw9JpWET_gAg8BB7aY9WS6uC65pPL_is'
})

// -- Navigation Data --
const sidebarLinks = ref([
    { icon: 'dashboard', text: 'Dashboard', active: false, path: '/vendedor/dashboard' },
    { icon: 'inventory_2', text: 'My Products', active: false, path: '/vendedor/my-products' },
    { icon: 'shopping_cart', text: 'Orders', active: false, path: '/vendedor/ordenes' },
    { icon: 'history_edu', text: 'Publication History', active: true, path: '/vendedor/historial' },
    { icon: 'analytics', text: 'Analytics', active: false, path: '/vendedor/analiticas' },
    { icon: 'settings', text: 'Settings', active: false, path: '/vendedor/configuracion' },
])

const mobileNav = ref([
    { icon: 'dashboard', text: 'Inicio', active: false, path: '/vendedor/dashboard' },
    { icon: 'history_edu', text: 'Historial', active: true, path: '/vendedor/historial' },
    { icon: 'analytics', text: 'Métricas', active: false, path: '/vendedor/analiticas' },
    { icon: 'settings', text: 'Perfil', active: false, path: '/vendedor/configuracion' },
])

// -- Filters --
const filters = ref(['Todos'])
const activeFilter = ref('Todos')

// -- Status Dictionary for Dynamic Classes --
const statusStyles = {
    Activo: { badge: 'bg-tertiary-fixed text-on-tertiary-fixed-variant', dot: 'bg-tertiary-container' },
    Actualizado: { badge: 'bg-surface-container text-primary', dot: 'bg-primary animate-pulse' },
    Eliminado: { badge: 'bg-error-container text-on-error-container', dot: 'bg-error' },
}

const getImageUrl = (imagenPath) => {
    if (!imagenPath) return 'https://via.placeholder.com/400';
    if (imagenPath.startsWith('http') || imagenPath.startsWith('data:')) {
        return imagenPath;
    }
    return `${storageUrl}/${imagenPath}`;
};

// -- dynamic Publications Data --
const publications = ref([])

onMounted(async () => {
    const response = await obtenerMisProductos()
    if (response.success && response.data?.data) {
        publications.value = response.data.data.map(p => ({
            id: p.id_producto || p.id,
            title: p.Nombre,
            refCode: `CT-${String(p.id_producto || p.id).padStart(5, '0')}`,
            category: p.categoria || p.Categoria,
            date: 'Recientemente',
            timeInfo: `Stock: ${p.Stock} unidades`,
            status: p.Aceptado ? 'Activo' : 'Actualizado',
            image: getImageUrl(p.Imagenes),
        }))
    }
})

// -- Filter Logic --
const filteredPublications = computed(() => {
    if (activeFilter.value === 'Todos') return publications.value
    return publications.value.filter(pub => pub.status === activeFilter.value)
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

body {
    font-family: 'Inter', sans-serif;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>