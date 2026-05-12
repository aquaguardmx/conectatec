<template>
    <div class="bg-surface text-on-surface min-h-screen">

        <nav
            class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm dark:shadow-none flex justify-between items-center h-16 px-6 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <button @click="goBack" type="button"
                    class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors rounded-full active:scale-95 duration-200">
                    <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                </button>
                <span class="text-xl font-bold tracking-tight text-blue-700 dark:text-blue-400">Conectatec</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-slate-500">help_outline</span>
                <span class="material-symbols-outlined text-slate-500">notifications</span>
                <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                    <img alt="User profile avatar"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-7ylJzdaugsHGHsdY-LpgBouqzhi9hBzIy8jhznw9QNqXyMQAX-8KAS4sFe_hgXjHMcobLy8FAuYbqHU4fbFRVtDzVqf8IwRbZBj_K5BsHJI3Cf5_1DxI9l-zDBzIckq_4x1ccULmzfd47-Os4tOe-Syn6rYr_HV-8u-6pPAf4RQ3zOykfMCU5U3qF3OqSmv19VJ9AaPmzwCF2PDn5QmqtYqCixtjnnpshD-3zTVBfAjSbzKSbqlBxCLd0goNLy-FSDQUmn-5mjU" />
                </div>
            </div>
        </nav>

        <main class="pt-24 pb-16 px-6 max-w-4xl mx-auto">

            <header class="mb-10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-sm font-medium text-primary uppercase tracking-wider">Gestión de Inventario</span>
                </div>
                <h1 class="text-4xl font-extrabold text-on-surface tracking-tight mb-2">Actualizar Producto</h1>
                <p class="text-on-surface-variant text-lg">Modifica los detalles del producto ID: <span
                        class="font-mono text-primary">{{ productId }}</span> para actualizar el catálogo.</p>
            </header>

            <form @submit.prevent="submitForm" class="space-y-8">

                <section class="bg-surface-container-low rounded-xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-on-surface">Galería de Imágenes</h2>
                        <span class="text-sm text-on-surface-variant italic">Máximo 5 imágenes</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div v-for="(img, index) in images" :key="index"
                            class="relative aspect-square rounded-lg overflow-hidden group border border-outline-variant/20">
                            <img class="w-full h-full object-cover" :src="img" alt="Product image" />
                            <div
                                class="absolute inset-0 bg-on-surface/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button @click.stop="removeImage(index)"
                                    class="p-2 bg-white rounded-full shadow-lg text-error hover:scale-110 transition-transform"
                                    type="button">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </div>
                        </div>

                        <button v-if="images.length < 5" @click="triggerFileInput"
                            class="aspect-square rounded-lg border-2 border-dashed border-outline-variant flex flex-col items-center justify-center gap-2 bg-surface-container-lowest hover:bg-surface-container-high transition-colors text-on-surface-variant"
                            type="button">
                            <span class="material-symbols-outlined text-3xl">add_a_photo</span>
                            <span class="text-xs font-medium">Añadir</span>
                        </button>
                        <input ref="fileInputRef" type="file" accept="image/*" class="hidden" @change="handleFileUpload" />

                        <div v-for="n in (4 - images.length)" :key="'placeholder-' + n"
                            class="aspect-square rounded-lg bg-surface-container/50 border border-outline-variant/10">
                        </div>
                    </div>
                </section>

                <section class="bg-surface-container-low rounded-xl p-8 space-y-6">
                    <h2 class="text-xl font-bold text-on-surface mb-2">Información Principal</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Nombre del
                                Producto</label>
                            <input v-model="form.Nombre" type="text"
                                class="w-full bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary p-3 text-on-surface placeholder:text-outline shadow-sm" />
                        </div>

                        <div class="col-span-1">
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Categoría</label>
                            <select v-model="form.categoria"
                                class="w-full bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary p-3 text-on-surface shadow-sm appearance-none">
                                <option value="">Seleccionar categoría</option>
                                <option value="HARDWARE & COMPONENTES">Hardware & Componentes</option>
                                <option value="SISTEMAS EMBEBIDOS">Sistemas Embebidos</option>
                                <option value="ROBÓTICA INDUSTRIAL">Robótica Industrial</option>
                                <option value="SOFTWARE LICENCIADO">Software Licenciado</option>
                                <option value="REDES Y TELECOMUNICACIONES">Redes y Telecomunicaciones</option>
                                <option value="SERVIDORES Y DATA CENTERS">Servidores y Data Centers</option>
                                <option value="SENSORES Y ACTUADORES">Sensores y Actuadores</option>
                                <option value="DISPOSITIVOS IOT">Dispositivos IoT</option>
                                <option value="HERRAMIENTAS DE DESARROLLO">Herramientas de Desarrollo</option>
                                <option value="OTRA">Otra (Especificar)</option>
                            </select>
                        </div>
                        <div v-if="form.categoria === 'OTRA'" class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Especificar Nueva Categoría</label>
                            <input v-model="categoriaPersonalizada" type="text"
                                @input="categoriaPersonalizada = $event.target.value.toUpperCase()"
                                class="w-full bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary p-3 text-on-surface placeholder:text-outline shadow-sm" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Estado</label>
                            <div class="flex gap-2">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" value="nuevo" v-model="form.estado" class="hidden peer" />
                                    <div
                                        class="w-full text-center p-3 rounded-lg border-2 border-transparent bg-surface-container-lowest peer-checked:border-primary peer-checked:bg-primary/5 text-sm font-medium transition-all">
                                        Nuevo
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" value="reacondicionado" v-model="form.estado"
                                        class="hidden peer" />
                                    <div
                                        class="w-full text-center p-3 rounded-lg border-2 border-transparent bg-surface-container-lowest peer-checked:border-primary peer-checked:bg-primary/5 text-sm font-medium transition-all">
                                        Reacondicionado
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-on-surface-variant mb-2">Descripción
                            Detallada</label>
                        <textarea v-model="form.Descripcion" rows="5"
                            class="w-full bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary p-3 text-on-surface shadow-sm resize-none"></textarea>
                    </div>
                </section>

                <section class="bg-surface-container-low rounded-xl p-8 space-y-6">
                    <h2 class="text-xl font-bold text-on-surface mb-2">Precio e Inventario</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Precio (USD)</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold">$</span>
                                <input v-model.number="form.Precio" type="number" step="0.01" min="0"
                                    class="w-full bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary py-3 pl-8 pr-4 text-on-surface shadow-sm font-bold" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Cantidad en
                                Inventario</label>
                            <div class="flex items-center gap-4">
                                <button @click="decrementStock" type="button"
                                    class="w-12 h-12 flex items-center justify-center bg-surface-container-lowest rounded-lg text-primary hover:bg-primary/10 transition-colors shadow-sm">
                                    <span class="material-symbols-outlined">remove</span>
                                </button>
                                <input v-model.number="form.Stock" type="number" min="0"
                                    class="w-24 text-center bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary p-3 text-on-surface font-bold shadow-sm" />
                                <button @click="incrementStock" type="button"
                                    class="w-12 h-12 flex items-center justify-center bg-surface-container-lowest rounded-lg text-primary hover:bg-primary/10 transition-colors shadow-sm">
                                    <span class="material-symbols-outlined">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <div
                    class="flex items-center gap-3 p-4 bg-tertiary/10 rounded-full border border-tertiary-fixed-dim/30">
                    <span class="material-symbols-outlined text-tertiary-container"
                        style="font-variation-settings: 'FILL' 1;">verified_user</span>
                    <span class="text-sm font-semibold text-tertiary-container uppercase tracking-tight">Vendedor
                        Verificado Conectatec</span>
                    <span class="ml-auto text-xs text-tertiary/70 italic">Cambios sujetos a revisión de seguridad</span>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-6">
                    <button type="submit" :disabled="isLoading"
                        class="flex-1 py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-xl font-bold text-lg shadow-lg hover:shadow-primary/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                        <span class="material-symbols-outlined">save</span>
                        Guardar Cambios
                    </button>
                    <button @click="deleteProduct" type="button" :disabled="isLoading"
                        class="px-8 py-4 bg-error text-on-error rounded-xl font-bold text-lg hover:bg-error-container hover:text-on-error-container transition-colors active:scale-[0.98] disabled:opacity-50">
                        Eliminar
                    </button>
                    <button @click="cancelUpdate" type="button" :disabled="isLoading"
                        class="px-8 py-4 bg-secondary-container text-on-secondary-container rounded-xl font-bold text-lg hover:bg-surface-container-high transition-colors active:scale-[0.98] disabled:opacity-50">
                        Cancelar
                    </button>
                </div>
            </form>

            <div
                class="mt-12 p-6 border border-outline-variant/20 rounded-xl bg-surface-container-lowest flex items-start gap-4">
                <span class="material-symbols-outlined text-amber-500">info</span>
                <div class="text-sm text-on-surface-variant space-y-1">
                    <p class="font-bold text-on-surface">Recordatorio de Seguridad</p>
                    <p>Al actualizar el precio o la cantidad, el sistema sincronizará automáticamente los datos en todos
                        los canales de venta activos. Asegúrate de que las imágenes cumplan con los estándares de
                        calidad de Conectatec.</p>
                </div>
            </div>

        </main>
        <div class="h-10 md:hidden"></div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useProductos } from '~/composables/useProductos'

const router = useRouter()
const route = useRoute()
const { obtenerProducto, actualizarProducto, eliminarProducto, isLoading, error, validationErrors } = useProductos()

// ID del producto extraído de la ruta
const productId = ref(route.query.id || '')

// Objeto reactivo que contiene los datos del formulario
const form = reactive({
    Nombre: '',
    categoria: '',
    estado: 'nuevo',
    Descripcion: '',
    Precio: 0.00,
    Stock: 0
})

const categoriaPersonalizada = ref('')

// Manejo de la galería de imágenes
const images = ref([])
const archivosFisicos = ref([])
const fileInputRef = ref(null)

onMounted(async () => {
    if (!productId.value) {
        alert('ID de producto inválido.')
        router.push('/vendedor/my-products')
        return
    }

    const { success, data } = await obtenerProducto(productId.value)
    if (success && data.data) {
        const p = data.data
        form.Nombre = p.Nombre || ''
        
        const catValue = (p.categoria || p.Categoria || '').toUpperCase()
        const categoriasConocidas = [
            'HARDWARE & COMPONENTES', 'SISTEMAS EMBEBIDOS', 'ROBÓTICA INDUSTRIAL', 
            'SOFTWARE LICENCIADO', 'REDES Y TELECOMUNICACIONES', 'SERVIDORES Y DATA CENTERS', 
            'SENSORES Y ACTUADORES', 'DISPOSITIVOS IOT', 'HERRAMIENTAS DE DESARROLLO'
        ]
        
        if (categoriasConocidas.includes(catValue)) {
            form.categoria = catValue
        } else {
            form.categoria = 'OTRA'
            categoriaPersonalizada.value = catValue
        }

        form.Descripcion = p.Descripcion || ''
        form.Precio = Number(p.Precio) || 0
        form.Stock = Number(p.Stock) || 0
        form.estado = (p.estado || p.Estado || 'nuevo').toLowerCase()
        
        if (p.Imagenes) {
            if (p.Imagenes.startsWith('http') || p.Imagenes.startsWith('data:')) {
                images.value = [p.Imagenes]
            } else {
                images.value = [`http://localhost:8000/storage/${p.Imagenes}`]
            }
        }
    }
})

const triggerFileInput = () => {
    fileInputRef.value?.click()
}

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files)
    files.forEach(file => {
        if (images.value.length < 5) {
            archivosFisicos.value.push(file)
            const reader = new FileReader()
            reader.onload = (e) => images.value.push(e.target.result)
            reader.readAsDataURL(file)
        }
    })
    event.target.value = ''
}

const removeImage = (index) => {
    images.value.splice(index, 1)
}

// Lógica de inventario
const incrementStock = () => {
    form.Stock++
}

const decrementStock = () => {
    if (form.Stock > 0) form.Stock--
}

// Lógica de los botones principales
const submitForm = async () => {
    const payload = { ...form }
    if (payload.categoria === 'OTRA') {
        payload.categoria = categoriaPersonalizada.value.trim().toUpperCase()
    }

    const formData = new FormData()
    Object.keys(payload).forEach(key => {
        if (key === 'estado') {
            formData.append('Estado', payload[key]) // Backend espera "Estado" con E mayúscula
        } else {
            formData.append(key, payload[key])
        }
    })

    if (archivosFisicos.value.length > 0) {
        formData.append('Imagenes', archivosFisicos.value[0])
    }

    const { success, data } = await actualizarProducto(productId.value, formData)
    if (success) {
        router.push({
            path: '/vendedor/update-sucessful',
            state: { 
                producto: { 
                    id_producto: productId.value, 
                    ...payload, 
                    Imagenes: data?.data?.Imagenes || images.value[0] || null 
                } 
            }
        })
    }
}

const deleteProduct = async () => {
    if (confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.')) {
        const { success } = await eliminarProducto(productId.value)
        if (success) {
            router.push('/vendedor/my-products')
        }
    }
}

const goBack = () => {
    router.back()
}

const cancelUpdate = () => {
    // Redirigir a la lista de productos
    router.push('/vendedor/my-products')
}
</script>

<style scoped>
/* Asegura que los íconos de Material Symbols se rendericen correctamente */
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>