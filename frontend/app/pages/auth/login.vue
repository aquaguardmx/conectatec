<template>
    <div class="min-h-screen flex bg-[#fcfdff]">
        <AuthBrandPanel />

        <div class="flex-1 flex flex-col items-center justify-center px-8 lg:px-16 py-6 relative overflow-y-auto">
            <div class="w-full max-w-[460px] mx-auto pb-10">

                <div class="mb-5">
                    <h1 class="text-3xl font-extrabold text-[#1a1f36] tracking-tight mb-1">Iniciar sesión</h1>
                    <p class="text-[14px] font-medium text-[#6b7280]">Ingresa con tus credenciales institucionales</p>
                </div>

                <form class="space-y-4" @submit.prevent="handleLogin">

                    <AuthFormField id="email" v-model="form.email" label="Correo Institucional" type="email"
                        placeholder="usuario@universidad.edu" autocomplete="email">
                        <template #prefix>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V8L12 13L4 8V18H20ZM12 11L4 6H20L12 11Z" />
                            </svg>
                        </template>
                    </AuthFormField>

                    <div class="space-y-1">
                        <AuthFormField id="password" v-model="form.password" label="Contraseña"
                            :type="showPassword ? 'text' : 'password'" placeholder="••••••••••••••"
                            autocomplete="current-password">
                            <template #prefix>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18 8H17V6C17 3.24 14.76 1 12 1C9.24 1 7 3.24 7 6V8H6C4.9 8 4 8.9 4 10V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V10C20 8.9 19.1 8 18 8ZM9 6C9 4.34 10.34 3 12 3C13.66 3 15 4.34 15 6V8H9V6ZM18 20H6V10H18V20ZM12 17C13.1 17 14 16.1 14 15C14 13.9 13.1 13 12 13C10.9 13 10 13.9 10 15C10 16.1 10.9 17 12 17Z" />
                                </svg>
                            </template>
                            <template #suffix>
                                <button type="button"
                                    class="hover:text-[#0052ff] transition-colors flex items-center justify-center p-1"
                                    @click="showPassword = !showPassword">
                                    <svg v-if="!showPassword" class="w-[22px] h-[22px]" fill="currentColor"
                                        viewBox="0 0 24 24">
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

                        <NuxtLink to="/auth/reset-password" class="flex justify-end pt-1">
                            <span class="text-[13px] font-bold text-[#0052ff] hover:underline">
                                ¿Olvidaste tu contraseña?
                            </span>
                        </NuxtLink>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="loading"
                            class="w-full py-3.5 px-6 bg-[#0052ff] hover:bg-[#0042cd] text-white font-bold rounded-xl transition-all duration-200 disabled:opacity-60 flex items-center justify-center gap-2">
                            <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span>{{ loading ? 'Iniciando sesión...' : 'Ingresar' }}</span>
                            <svg v-if="!loading" class="w-5 h-5 ml-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-[15px] font-medium text-[#6b7280]">
                            ¿No tienes cuenta?
                            <NuxtLink to="/auth/register" class="text-[#0052ff] font-bold hover:underline">
                                Regístrate
                            </NuxtLink>
                        </p>
                    </div>
                </form>
            </div>

            <div class="absolute bottom-4 left-0 right-0 text-center px-8">
                <p class="text-[11px] text-[#9ca3af] max-w-sm mx-auto leading-relaxed">
                    Al iniciar sesión en Conectatec, aceptas nuestros <a href="#" class="underline">Términos de
                        Servicio</a> y
                    nuestra <a href="#" class="underline">Política de Privacidad</a>.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'

const form = reactive({
    email: '',
    password: '',
})

const loading = ref(false)
const showPassword = ref(false)

// 1. Instanciamos el composable de autenticación (Asegúrate de que tengas un método `login`)
const { login } = useAuth()

// login.vue

async function handleLogin() {
    loading.value = true
    try {
        const credentials = {
            email: form.email,
            password: form.password,
        }

        const response = await login(credentials)

        // 1. Imprime la respuesta en consola para ver qué está mandando Laravel realmente
        console.log('Respuesta del servidor:', response)

        // 2. Acceso seguro al Rol (con R mayúscula y usando ?. para evitar errores)
        // Usamos el nombre 'Rol' porque mencionaste que así se llama en la BD
        const userRole = response.user?.Rol

        if (!userRole) {
            console.warn('El servidor no devolvió el campo Rol dentro del objeto user')
            // Si el login fue exitoso pero no hay rol, quizá quieras mandarlo a un sitio genérico
            await navigateTo('/auth/welcome')
            return
        }

        // 3. Redirección basada en el rol
        const roleLower = userRole.toLowerCase()

        console.log(roleLower)

        if (roleLower === 'vendedor') {
            await navigateTo('/vendedor/dashboard')
        } else if (roleLower === 'estudiante') {
            await navigateTo('/cliente/main')
        }

    } catch (err: any) {
        console.error('Error al iniciar sesión:', err)
        alert('Credenciales incorrectas o problema de conexión.')
    } finally {
        loading.value = false
    }
}
</script>