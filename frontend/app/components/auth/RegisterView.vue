<template>
  <div class="min-h-screen flex bg-[#fcfdff]">
    <!-- Panel Izquierdo - Branding -->
    <AuthBrandPanel />

    <!-- Panel Derecho - Formulario -->
    <div class="flex-1 flex flex-col items-center justify-center px-8 lg:px-16 py-6 relative overflow-y-auto">
      <div class="w-full max-w-[460px] mx-auto pb-10">

        <!-- Header del formulario -->
        <div class="mb-5">
          <h1 class="text-3xl font-extrabold text-[#1a1f36] tracking-tight mb-1">Crear nueva cuenta</h1>
          <p class="text-[14px] font-medium text-[#6b7280]">Ingresa tus datos institucionales</p>
        </div>

        <div v-if="errorMessage" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
          <p class="font-semibold">Error al registrar</p>
          <p class="mt-1">{{ errorMessage }}</p>
        </div>

        <!-- Formulario -->
        <form class="space-y-4" @submit.prevent="handleRegister">

          <!-- Selector de Rol -->
          <div class="space-y-2">
            <label class="block text-[13px] font-semibold text-[#3b415a]">Selecciona tu rol</label>
            <div class="grid grid-cols-2 gap-3">
              <!-- Estudiante -->
              <button type="button" @click="setRole('estudiante')"
                class="relative w-full cursor-pointer rounded-[14px] p-3.5 flex flex-col items-center justify-center transition-all duration-200 border-2"
                :class="form.role === 'estudiate' ? 'bg-[#f4f7fa] border-[#f4f7fa]' : 'bg-transparent border-[#e5e7eb] opacity-70 hover:opacity-100 hover:bg-[#f4f7fa]/40'">
                <!-- Checkmark si está activo -->
                <div v-if="form.role === 'estudiante'" class="absolute top-2.5 right-2.5 text-[#0052ff]">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd" />
                  </svg>
                </div>

                <div class="w-8 h-8 bg-white rounded-full shadow-sm flex items-center justify-center mb-2"
                  :class="form.role === 'estudiante' ? 'text-[#0052ff]' : 'text-[#6b7280]'">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M12 3L1 9L4 10.63V17C4 18.66 7.58 20 12 20C16.42 20 20 18.66 20 17V10.63L23 9L12 3ZM12 18C8.54 18 6 17.06 6 17V11.72L12 15L18 11.72V17C18 17.06 15.46 18 12 18ZM12 5L19.53 9L12 13L4.47 9L12 5Z" />
                  </svg>
                </div>
                <span class="text-[13px] font-semibold"
                  :class="form.role === 'estudiante' ? 'text-[#1a1f36]' : 'text-[#6b7280]'">Estudiante</span>
              </button>

              <!-- Vendedor -->
              <button type="button" @click="setRole('vendedor')"
                class="relative w-full cursor-pointer rounded-[14px] p-3.5 flex flex-col items-center justify-center transition-all duration-200 border-2"
                :class="form.role === 'vendedor' ? 'bg-[#f4f7fa] border-[#f4f7fa]' : 'bg-transparent border-[#e5e7eb] opacity-70 hover:opacity-100 hover:bg-[#f4f7fa]/40'">
                <!-- Checkmark si está activo -->
                <div v-if="form.role === 'vendedor'" class="absolute top-2.5 right-2.5 text-[#0052ff]">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd" />
                  </svg>
                </div>

                <div class="w-8 h-8 bg-white rounded-full shadow-sm flex items-center justify-center mb-2"
                  :class="form.role === 'vendedor' ? 'text-[#0052ff]' : 'text-[#6b7280]'">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M20 6H4V8H20V6ZM20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V10H20V18ZM15 12H9V14H15V12Z" />
                  </svg>
                </div>
                <span class="text-[13px] font-semibold"
                  :class="form.role === 'vendedor' ? 'text-[#1a1f36]' : 'text-[#6b7280]'">Vendedor</span>
              </button>
            </div>
          </div>

          <!-- Nombre completo -->
          <AuthFormField id="full-name" v-model="form.fullName" label="Nombre Completo" type="text"
            placeholder="Ej. Juan Pérez García" autocomplete="name" />

          <!-- Matrícula y Teléfono -->
          <div class="grid grid-cols-2 gap-3">
            <AuthFormField id="student-id" v-model="form.studentId" label="Matrícula / ID" type="text"
              placeholder="A01234567" autocomplete="off" />
            <AuthFormField id="phone" v-model="form.phone" label="Teléfono" type="tel" placeholder="55 1234 5678"
              autocomplete="tel" />
          </div>

          <!-- Correo institucional -->
          <AuthFormField id="email" v-model="form.email" label="Correo Institucional" type="email"
            placeholder="usuario@universidad.edu" autocomplete="email">
            <template #prefix>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V8L12 13L4 8V18H20ZM12 11L4 6H20L12 11Z" />
              </svg>
            </template>
          </AuthFormField>

          <!-- Contraseña -->
          <AuthFormField id="password" v-model="form.password" label="Contraseña"
            :type="showPassword ? 'text' : 'password'" placeholder="••••••••••••••" autocomplete="new-password">
            <template #prefix>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M18 8H17V6C17 3.24 14.76 1 12 1C9.24 1 7 3.24 7 6V8H6C4.9 8 4 8.9 4 10V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V10C20 8.9 19.1 8 18 8ZM9 6C9 4.34 10.34 3 12 3C13.66 3 15 4.34 15 6V8H9V6ZM18 20H6V10H18V20ZM12 17C13.1 17 14 16.1 14 15C14 13.9 13.1 13 12 13C10.9 13 10 13.9 10 15C10 16.1 10.9 17 12 17Z" />
              </svg>
            </template>
            <template #suffix>
              <button type="button" class="hover:text-[#0052ff] transition-colors flex items-center justify-center p-1"
                @click="showPassword = !showPassword">
                <svg v-if="!showPassword" class="w-[22px] h-[22px]" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" />
                </svg>
                <svg v-else class="w-[22px] h-[22px]" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M12 7C14.76 7 17 9.24 17 12C17 12.34 16.96 12.67 16.89 12.98L19.5 15.58C20.46 14.59 21.21 13.37 21.6 12C20.31 7.61 16.48 4.29 12 4.29C10.73 4.29 9.53 4.52 8.41 4.96L10.3 6.85C10.84 6.74 11.4 6.68 12 6.68V7ZM2.01 3.87L4.69 6.55C3.06 7.83 1.77 9.53 1.15 11.53C2.44 15.92 6.27 19.24 10.74 19.24C11.66 19.24 12.56 19.06 13.41 18.73L16.38 21.7L17.79 20.29L3.42 5.92L2.01 3.87ZM10.74 17.24C8.2 17.24 6.04 15.65 4.9 13.5C5.45 12.46 6.19 11.55 7.07 10.81L9.12 12.86C9.04 13.07 9 13.28 9 13.5C9 14.88 10.12 16 11.5 16C11.72 16 11.93 15.96 12.14 15.88L13.84 17.58C12.88 18.02 11.83 18.24 10.74 18.24Z" />
                </svg>
              </button>
            </template>
          </AuthFormField>

          <!-- Botón de submit -->
          <div class="pt-1">
            <button type="submit" :disabled="loading"
              class="w-full py-3.5 px-6 bg-[#0052ff] hover:bg-[#0042cd] text-white font-bold rounded-xl transition-all duration-200 disabled:opacity-60 flex items-center justify-center gap-2">
              <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              <span>{{ loading ? 'Enviando...' : 'Crear Cuenta' }}</span>
              <svg v-if="!loading" class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
              </svg>
            </button>
          </div>

          <!-- Link inicio sesión -->
          <div class="text-center pt-2">
            <p class="text-[15px] font-medium text-[#6b7280]">
              ¿Ya tienes cuenta?
              <NuxtLink to="/auth/login" class="text-[#0052ff] font-bold hover:underline">
                Inicia sesión
              </NuxtLink>
            </p>
          </div>
        </form>
      </div>

      <!-- Footer legal -->
      <div class="absolute bottom-4 left-0 right-0 text-center px-8">
        <p class="text-[11px] text-[#9ca3af] max-w-sm mx-auto leading-relaxed">
          Al unirte a Conectatec, aceptas nuestros <a href="#" class="underline">Términos de Servicio</a> y nuestra <a
            href="#" class="underline">Política de Privacidad</a>.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'

const form = reactive({
  role: 'estudiante',
  fullName: '',
  studentId: '',
  phone: '',
  email: '',
  password: '',
})

const loading = ref(false)
const showPassword = ref(false)
const errorMessage = ref('')
const { registerStudent } = useAuth()

function setRole(newRole: string) {
  form.role = newRole
}

async function handleRegister() {
  errorMessage.value = ''
  // --- 1. VALIDACIÓN DE CORREO ---
  const emailRegex = /^[a-zA-Z0-9._%+-]+@orizaba\.tecnm\.mx$/;
  if (!emailRegex.test(form.email)) {
    alert("Error: El correo debe tener la extensión @orizaba.tecnm.mx");
    return;
  }

  // --- 2. VALIDACIÓN DE MATRÍCULA (Solo números, 8 dígitos) ---
  const studentIdRegex = /^\d{8}$/; // \d significa dígito, {8} significa exactamente 8
  if (!studentIdRegex.test(form.studentId)) {
    alert("Error: La matrícula debe consistir en exactamente 8 números (ej. 23011178)");
    return;
  }

  // --- 3. VALIDACIÓN DE TELÉFONO (Solo números, 10 dígitos) ---
  // Nota: Eliminamos espacios antes de validar por si el usuario puso "55 123..."
  const cleanPhone = form.phone.replace(/\s+/g, ''); 
  const phoneRegex = /^\d{10}$/; // Valida que sean 10 números seguidos
  if (!phoneRegex.test(cleanPhone)) {
    alert("Error: El teléfono debe contener 10 dígitos numéricos");
    return;
  }

  // --- 4. VALIDACIÓN DE CONTRASEÑA ---
  const specialCharRegex = /[\W_]/; 
  if (form.password.length < 8 || !specialCharRegex.test(form.password)) {
    alert("Error: La contraseña debe tener al menos 8 caracteres y un carácter especial");
    return;
  }

  // --- PROCESO DE REGISTRO ---
  loading.value = true
  try {
    const dataToSend = {
      nombreCompleto: form.fullName,
      matricula: form.studentId,
      telefono: cleanPhone, // Enviamos el teléfono limpio (solo números)
      email: form.email,
      password: form.password,
      rol: form.role
    }

    const response = await registerStudent(dataToSend)
    await navigateTo('/auth/welcome')

  } catch (err: any) {
    console.error('Error:', err)

    const responseData = err?.data || err?.response?.data || null
    if (responseData) {
      if (responseData.errors) {
        errorMessage.value = Object.values(responseData.errors)
          .flat()
          .join(' ')
      } else if (responseData.message) {
        errorMessage.value = responseData.message
      } else {
        errorMessage.value = JSON.stringify(responseData)
      }
    } else if (err?.message) {
      errorMessage.value = err.message
    } else {
      errorMessage.value = 'Ocurrió un error inesperado al registrar la cuenta.'
    }
  } finally {
    loading.value = false
  }
}
</script>