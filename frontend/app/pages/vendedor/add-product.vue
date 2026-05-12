<template>
  <main class="ml-64 pt-24 pb-20 px-12 max-w-7xl mx-auto">
    <SideNavBar />
    <TopNavBar />
    <div class="flex flex-col gap-10">

      <section class="flex flex-col gap-2">
        <nav class="flex items-center gap-2 text-xs font-medium text-on-surface-variant mb-4">
          <NuxtLink class="hover:text-primary transition-colors" to="/vendedor/dashboard">Dashboard</NuxtLink>
          <span class="material-symbols-outlined text-[14px]">chevron_right</span>
          <NuxtLink class="hover:text-primary transition-colors" to="/vendedor/mis-productos">Mis Productos</NuxtLink>
          <span class="material-symbols-outlined text-[14px]">chevron_right</span>
          <span class="text-on-surface">Nuevo Producto</span>
        </nav>
        <h2 class="text-4xl font-extrabold tracking-tight text-on-surface">Publicar Nuevo Producto</h2>
        <p class="text-on-surface-variant text-lg max-w-2xl">
          Añade los detalles técnicos y comerciales para listar tu producto en el ecosistema Conectatec.
        </p>
      </section>

      <div class="grid grid-cols-12 gap-8 items-start">

        <div class="col-span-12 lg:col-span-8 flex flex-col gap-8">

          <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm border border-outline-variant/10">
            <h3 class="text-xl font-bold mb-6 text-on-surface flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">info</span>
              Información General
            </h3>
            <div class="grid grid-cols-2 gap-6">
              <div class="col-span-2">
                <label class="block text-sm font-semibold text-on-surface mb-2">Nombre del Producto</label>
                <input v-model="form.Nombre"
                  class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary transition-all text-on-surface"
                  placeholder="Ej. Procesador Cuántico V2 - Edición Limitada" type="text" />
                <p v-if="validationErrors.Nombre" class="text-xs text-error mt-1">{{ validationErrors.Nombre[0] }}</p>
              </div>

              <div class="col-span-1">
                <label class="block text-sm font-semibold text-on-surface mb-2">Categoría</label>
                <select v-model="form.Categoria"
                  class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary transition-all text-on-surface appearance-none">
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
                <p v-if="validationErrors.Categoria" class="text-xs text-error mt-1">{{ validationErrors.Categoria[0] }}
                </p>
              </div>

              <div class="col-span-1">
                <label class="block text-sm font-semibold text-on-surface mb-2">Estado del Equipo</label>
                <div class="flex gap-2">
                  <button @click="form.Estado = 'nuevo'" :class="form.Estado === 'nuevo'
                    ? 'bg-primary text-on-primary'
                    : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high'"
                    class="flex-1 py-3 px-4 rounded-lg text-xs font-bold transition-all">
                    Nuevo
                  </button>
                  <button @click="form.Estado = 'reacondicionado'" :class="form.Estado === 'reacondicionado'
                    ? 'bg-primary text-on-primary'
                    : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high'"
                    class="flex-1 py-3 px-4 rounded-lg text-xs font-bold transition-all">
                    Reacondicionado
                  </button>
                </div>
              </div>

              <div v-if="form.Categoria === 'OTRA'" class="col-span-2 transition-all duration-300 ease-in-out">
                <label class="block text-sm font-semibold text-on-surface mb-2">Especificar Nueva Categoría</label>
                <input v-model="categoriaPersonalizada"
                  @input="categoriaPersonalizada = $event.target.value.toUpperCase()"
                  class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary transition-all text-on-surface placeholder:normal-case"
                  placeholder="Ej. INTELIGENCIA ARTIFICIAL" type="text" />
                <p v-if="form.Categoria === 'OTRA' && categoriaPersonalizada.trim() === ''"
                  class="text-xs text-on-surface-variant mt-1">
                  * Este campo es obligatorio si seleccionaste "Otra"
                </p>
              </div>

              <div class="col-span-2">
                <label class="block text-sm font-semibold text-on-surface mb-2">Descripción Detallada</label>
                <div
                  class="bg-surface-container-low rounded-lg overflow-hidden border-none focus-within:ring-2 focus-within:ring-primary transition-all">
                  <div
                    class="flex items-center gap-2 px-4 py-2 border-b border-outline-variant/10 bg-surface-container">
                    <button class="p-1 hover:bg-surface-container-highest rounded transition-colors">
                      <span class="material-symbols-outlined text-sm">format_bold</span>
                    </button>
                    <button class="p-1 hover:bg-surface-container-highest rounded transition-colors">
                      <span class="material-symbols-outlined text-sm">format_italic</span>
                    </button>
                    <button class="p-1 hover:bg-surface-container-highest rounded transition-colors">
                      <span class="material-symbols-outlined text-sm">format_list_bulleted</span>
                    </button>
                    <div class="w-px h-4 bg-outline-variant mx-1"></div>
                    <button class="p-1 hover:bg-surface-container-highest rounded transition-colors">
                      <span class="material-symbols-outlined text-sm">link</span>
                    </button>
                  </div>
                  <textarea v-model="form.Descripcion"
                    class="w-full bg-transparent border-none focus:ring-0 px-4 py-3 text-on-surface text-sm"
                    placeholder="Describe las especificaciones técnicas, compatibilidad y beneficios..."
                    rows="6"></textarea>
                </div>
                <p v-if="validationErrors.Descripcion" class="text-xs text-error mt-1">{{
                  validationErrors.Descripcion[0] }}</p>
              </div>
            </div>
          </div>

          <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm border border-outline-variant/10">
            <h3 class="text-xl font-bold mb-6 text-on-surface flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">image</span>
              Galería de Imágenes
            </h3>
            <div
              class="border-2 border-dashed border-outline-variant rounded-xl p-12 flex flex-col items-center justify-center gap-4 bg-surface-container-low/30 hover:bg-surface-container-low transition-colors cursor-pointer group"
              @click="triggerFileInput">
              <div
                class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary text-3xl">cloud_upload</span>
              </div>
              <div class="text-center">
                <p class="text-on-surface font-bold">Arrastra y suelta tus archivos aquí</p>
                <p class="text-on-surface-variant text-sm mt-1">Soporta JPG, PNG hasta 10MB por imagen (Máx. 5)</p>
              </div>
              <button
                class="mt-2 px-6 py-2 bg-surface-container-lowest border border-outline-variant text-primary text-sm font-bold rounded-lg hover:bg-white transition-colors">
                Seleccionar Archivos
              </button>
              <input ref="fileInput" type="file" accept="image/*" multiple class="hidden" @change="handleFileUpload" />
            </div>

            <div class="grid grid-cols-4 gap-4 mt-8">
              <div v-for="(img, index) in imagenes" :key="index"
                class="aspect-square rounded-lg bg-surface-container-low relative group overflow-hidden border border-outline-variant/10">
                <img :src="img" alt="Imagen producto"
                  class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all" />
                <button @click.stop="removeImage(index)"
                  class="absolute top-2 right-2 w-6 h-6 bg-error text-on-error rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                  <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
              </div>

              <template v-for="n in emptySlots" :key="'empty-' + n">
                <div
                  class="aspect-square rounded-lg bg-surface-container-low border-2 border-dashed border-outline-variant/30 flex items-center justify-center">
                  <span class="material-symbols-outlined text-outline-variant">add</span>
                </div>
              </template>
            </div>
            <p v-if="validationErrors.Imagenes" class="text-xs text-error mt-2">{{ validationErrors.Imagenes[0] }}</p>
          </div>
        </div>

        <div class="col-span-12 lg:col-span-4 flex flex-col gap-8">
          <div
            class="bg-surface-container-lowest rounded-xl p-8 shadow-sm border border-outline-variant/10 sticky top-24">
            <h3 class="text-xl font-bold mb-6 text-on-surface flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">payments</span>
              Precio y Stock
            </h3>
            <div class="space-y-6">

              <div>
                <label class="block text-sm font-semibold text-on-surface mb-2">Precio de Venta</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-on-surface-variant">$</span>
                  <input v-model.number="form.Precio"
                    class="w-full bg-surface-container-low border-none rounded-lg pl-8 pr-16 py-3 focus:ring-2 focus:ring-primary transition-all text-on-surface font-bold text-lg"
                    placeholder="0.00" type="number" min="0" step="0.01" />
                  <span
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-on-surface-variant bg-surface-container px-2 py-1 rounded">USD</span>
                </div>
                <p v-if="validationErrors.Precio" class="text-xs text-error mt-1">{{ validationErrors.Precio[0] }}</p>
                <p class="text-[11px] text-tertiary font-medium mt-2 flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">verified_user</span>
                  Comisión de plataforma: 2.5% aplicada
                </p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-on-surface mb-2">Cantidad en Inventario</label>
                <div class="flex items-center gap-3">
                  <button @click="decrementarStock"
                    class="w-12 h-12 rounded-lg bg-surface-container-low flex items-center justify-center hover:bg-surface-container-high transition-colors active:scale-90">
                    <span class="material-symbols-outlined">remove</span>
                  </button>
                  <input v-model.number="form.Stock"
                    class="flex-1 bg-surface-container-low border-none rounded-lg text-center font-bold text-on-surface py-3 focus:ring-2 focus:ring-primary"
                    type="number" min="0" />
                  <button @click="form.Stock++"
                    class="w-12 h-12 rounded-lg bg-surface-container-low flex items-center justify-center hover:bg-surface-container-high transition-colors active:scale-90">
                    <span class="material-symbols-outlined">add</span>
                  </button>
                </div>
                <p v-if="validationErrors.Stock" class="text-xs text-error mt-1">{{ validationErrors.Stock[0] }}</p>
              </div>

              <div v-if="!formularioValido && !error" class="pt-4 space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-error-container/20 text-error">
                  <span class="material-symbols-outlined">warning</span>
                  <p class="text-xs font-semibold">Faltan campos obligatorios por completar.</p>
                </div>
              </div>

              <div v-if="error" class="pt-4 space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-error-container/20 text-error">
                  <span class="material-symbols-outlined">error</span>
                  <p class="text-xs font-semibold">{{ error }}</p>
                </div>
              </div>

              <div class="pt-6 flex flex-col gap-3">
                <button @click="publicar" :disabled="!formularioValido || isLoading"
                  class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-lg font-bold shadow-xl shadow-primary/20 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                  {{ isLoading ? 'Publicando...' : 'Publicar Producto' }}
                  <span v-if="!isLoading" class="material-symbols-outlined text-[20px]">rocket_launch</span>
                  <span v-else class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
                </button>
                <button @click="cancelar" :disabled="isLoading"
                  class="w-full py-4 bg-secondary-container text-on-secondary-container rounded-lg font-bold hover:bg-secondary-fixed transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                  Cancelar
                </button>
              </div>
            </div>
          </div>

          <div class="bg-tertiary/5 rounded-xl p-6 border border-tertiary/10">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-lg bg-tertiary flex items-center justify-center text-on-tertiary shrink-0">
                <span class="material-symbols-outlined">lightbulb</span>
              </div>
              <div>
                <h4 class="text-sm font-bold text-tertiary mb-1">Tip de Arquitecto</h4>
                <p class="text-xs text-on-tertiary-fixed-variant leading-relaxed">
                  Productos con al menos 3 imágenes de alta calidad y descripciones técnicas detalladas tienen un 45%
                  más de probabilidad de ser adquiridos en las primeras 48 horas.
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>
</template>

<script setup>
import SideNavBar from '~/components/vendedor/dashboard/SideNavBar.vue';
import TopNavBar from '~/components/vendedor/dashboard/TopNavBar.vue';
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useProductos } from '~/composables/useProductos'

const router = useRouter()
const { publicarProducto, isLoading, error, validationErrors } = useProductos()

const MAX_IMAGENES = 5

const form = ref({
  Nombre: '',
  Categoria: '',
  Estado: 'nuevo',
  Descripcion: '',
  Precio: null,
  Stock: 1,
})

const categoriaPersonalizada = ref('')
const imagenes = ref([]) // Se usa SOLO para la vista previa (Base64)
const archivosFisicos = ref([]) // NUEVO: Se usa para guardar el archivo físico real a enviar
const fileInput = ref(null)

const emptySlots = computed(() => Math.max(0, MAX_IMAGENES - imagenes.value.length))

const formularioValido = computed(() => {
  const categoriaValida = form.value.Categoria !== '' &&
    (form.value.Categoria !== 'OTRA' || (form.value.Categoria === 'OTRA' && categoriaPersonalizada.value.trim() !== ''));

  return form.value.Nombre.trim() !== '' &&
    categoriaValida &&
    form.value.Descripcion.trim() !== '' &&
    form.value.Precio > 0;
})

function triggerFileInput() {
  fileInput.value?.click()
}

function handleFileUpload(event) {
  const files = Array.from(event.target.files)
  files.forEach(file => {
    if (imagenes.value.length < MAX_IMAGENES) {
      // Guardamos el archivo real para enviarlo al backend
      archivosFisicos.value.push(file)

      // Convertimos a Base64 solo para mostrar la miniatura en la pantalla
      const reader = new FileReader()
      reader.onload = (e) => imagenes.value.push(e.target.result)
      reader.readAsDataURL(file)
    }
  })
  event.target.value = ''
}

function removeImage(index) {
  imagenes.value.splice(index, 1)
  archivosFisicos.value.splice(index, 1) // Eliminamos también el archivo físico
}

function decrementarStock() {
  if (form.value.Stock > 0) form.value.Stock--
}

async function publicar() {
  if (!formularioValido.value) return

  const categoriaFinal = form.value.Categoria === 'OTRA'
    ? categoriaPersonalizada.value.trim().toUpperCase()
    : form.value.Categoria.toUpperCase();

  // Usamos FormData en lugar de un objeto literal JSON para poder enviar archivos
  const formData = new FormData();
  formData.append('Nombre', form.value.Nombre);
  formData.append('categoria', categoriaFinal);
  formData.append('Estado', form.value.Estado);
  formData.append('Descripcion', form.value.Descripcion);
  formData.append('Precio', form.value.Precio);
  formData.append('Stock', form.value.Stock);

  // Solo adjuntamos la imagen si hay al menos una seleccionada
  if (archivosFisicos.value.length > 0) {
    formData.append('Imagenes', archivosFisicos.value[0]);
  }

  const resultado = await publicarProducto(formData);

  if (resultado.success) {
    router.push({
      path: '/vendedor/product-succesful',
      // Pasamos el producto retornado por el API para tener la ruta de la base de datos
      state: { producto: resultado.data?.data || Object.fromEntries(formData.entries()) }
    })
  } else {
    console.error('Error al guardar:', resultado.error)
  }
}

function cancelar() {
  router.push('/vendedor/mis-productos')
}
</script>