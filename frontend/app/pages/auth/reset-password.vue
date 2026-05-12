<template>
    <div class="min-h-screen flex flex-col md:flex-row font-sans text-slate-900 bg-white overflow-hidden">

        <section
            class="hidden md:flex md:w-1/2 relative bg-[#003EC7] overflow-hidden flex-col justify-between p-12 lg:p-16">

            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop"
                    alt="Circuitos" class="w-full h-full object-cover mix-blend-overlay opacity-[0.15]" />
                <div class="absolute inset-0 bg-gradient-to-b from-[#003EC7]/40 to-[#001452]/90"></div>
                <div
                    class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#0052FF] rounded-full blur-[120px] opacity-60">
                </div>
            </div>

            <div class="relative z-10 space-y-12 max-w-lg">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-[10px] flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-[#003EC7]" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v4h-2zm0 6h2v2h-2z" />
                            <circle cx="8" cy="12" r="1.5" />
                            <circle cx="16" cy="12" r="1.5" />
                            <circle cx="12" cy="8" r="1.5" />
                            <circle cx="12" cy="16" r="1.5" />
                            <circle cx="12" cy="12" r="1.5" />
                        </svg>
                    </div>
                    <span class="text-white text-[22px] font-bold tracking-tight">Conectatec</span>
                </div>

                <div class="space-y-6">
                    <h1 class="text-white text-[2.75rem] md:text-[3.25rem] font-extrabold tracking-tight leading-[1.1]">
                        Tu seguridad<br>es nuestra<br>prioridad técnica.
                    </h1>
                    <p class="text-[#B7C4FF] text-[16px] leading-relaxed max-w-[400px]">
                        Entendemos que el acceso a tus herramientas de trabajo debe ser fluido y seguro. Sigue los pasos
                        para restablecer tu identidad digital en nuestra plataforma.
                    </p>
                </div>
            </div>

            <div class="relative z-10 w-full max-w-[420px]">
                <div
                    class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.1)] flex items-start gap-4">
                    <div class="bg-[#A7F1FF] w-10 h-10 rounded-lg flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[#001F24] text-[20px]"
                            style="font-variation-settings: 'FILL' 1;">security</span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-[15px] mb-1">Protección de Datos</h3>
                        <p class="text-[#B7C4FF] text-[13px] leading-relaxed">
                            Utilizamos encriptación de grado institucional para asegurar que cada solicitud de
                            recuperación sea legítima.
                        </p>
                    </div>
                </div>
            </div>

        </section>

        <section class="flex-1 flex flex-col justify-center items-center p-6 md:p-12 relative bg-[#FAFAFC]">

            <div class="w-full max-w-[400px] space-y-10 z-10">

                <div class="md:hidden flex items-center justify-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-[#003EC7] rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[18px]">hub</span>
                    </div>
                    <span class="text-[#0F172A] text-xl font-bold tracking-tight">Conectatec</span>
                </div>

                <div class="space-y-3">
                    <h2 class="text-[1.75rem] font-extrabold text-[#0F172A] tracking-tight">Restablecer contraseña</h2>
                    <p class="text-[#64748B] text-[14px] leading-relaxed">
                        Ingresa tu correo institucional para recibir un código de recuperación.
                    </p>
                </div>

                <form class="space-y-6" @submit.prevent="handleReset">

                    <div class="space-y-2">
                        <label for="email" class="block text-[12px] font-bold text-[#0F172A] tracking-wide">
                            Correo Institucional
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-[#94A3B8] text-[20px]">mail</span>
                            </div>
                            <input id="email" v-model="email" type="email" placeholder="usuario@institucion.edu"
                                required
                                class="w-full pl-11 pr-4 py-4 bg-[#F4F7FF] border border-transparent rounded-xl focus:ring-2 focus:ring-[#0052FF] focus:bg-white transition-all duration-200 outline-none text-[#0F172A] placeholder:text-[#94A3B8] font-medium" />
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-[#0052FF] hover:bg-blue-700 text-white font-bold rounded-xl shadow-[0_8px_16px_rgba(0,82,255,0.2)] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2">
                        <span class="text-[15px]">Enviar Código</span>
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                </form>

                <div class="pt-2 flex flex-col items-center gap-8">

                    <NuxtLink to="/auth/login"
                        class="flex items-center gap-1.5 text-[#0052FF] font-bold text-[14px] hover:underline transition-colors group">
                        <span
                            class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">chevron_left</span>
                        Volver a Iniciar Sesión
                    </NuxtLink>

                    <div class="w-full h-px bg-slate-200/80"></div>

                    <div class="text-center space-y-4 w-full">
                        <p class="text-[#64748B] text-[13px] font-medium">¿Tienes problemas para acceder?</p>
                        <div class="flex gap-3 justify-center">
                            <button type="button"
                                class="text-[12px] font-bold text-[#475569] bg-[#E2E8F0] hover:bg-[#CBD5E1] px-5 py-2.5 rounded-full transition-colors">
                                Soporte IT
                            </button>
                            <button type="button"
                                class="text-[12px] font-bold text-[#475569] bg-[#E2E8F0] hover:bg-[#CBD5E1] px-5 py-2.5 rounded-full transition-colors">
                                FAQ
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div
                class="absolute bottom-8 right-8 hidden lg:flex items-center gap-3 bg-white p-2.5 pr-5 rounded-full shadow-[0_12px_32px_rgba(13,28,46,0.08)] border border-slate-100 z-20">
                <div class="bg-[#005761] w-8 h-8 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[16px]"
                        style="font-variation-settings: 'FILL' 1;">shield</span>
                </div>
                <span class="text-[12px] font-extrabold text-[#0F172A] tracking-wide">Conexión Encriptada</span>
            </div>

        </section>
    </div>
</template>

<script setup>
import { ref } from 'vue'

// 1. Instanciamos las herramientas necesarias
const { requestPasswordReset } = useAuth()
const loading = ref(false)
const email = ref('')

const handleReset = async () => {
    if (!email.value) return alert("Por favor, ingresa tu correo.")

    loading.value = true
    try {
        // 2. Llamamos al composable. 
        // Internamente, este mandará el dato como 'email' al controlador de Laravel,
        // el cual buscará en la columna 'Correo_Institucional'.
        await requestPasswordReset(email.value)

        // 3. Navegamos a la siguiente página pasando el email en la URL
        await navigateTo({
            path: '/auth/confirm-code',
            query: { email: email.value }
        })
    } catch (error) {
        // Laravel devolverá un error 422 o 404 si el Correo_Institucional no existe
        console.error("Error en reset:", error)
        alert("El correo institucional ingresado no está registrado.")
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
