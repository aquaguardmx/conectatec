<template>
    <div class="min-h-screen flex flex-col md:flex-row font-sans text-slate-900 bg-white overflow-hidden">

        <section class="hidden md:flex md:w-1/2 relative bg-[#020B1A] overflow-hidden">
            <div class="absolute inset-0 opacity-70">
                <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop"
                    alt="Tech background" class="w-full h-full object-cover mix-blend-overlay" />
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#020617]/90 to-[#0A192F]/50"></div>
            <div
                class="absolute top-1/2 right-0 -translate-y-1/2 w-[30rem] h-[30rem] bg-[#00A3FF]/10 rounded-full blur-[100px]">
            </div>
        </section>

        <section
            class="flex-1 flex flex-col justify-center items-center p-6 md:p-12 relative bg-gradient-to-b from-[#EBF2FF] via-[#F8FAFF] to-white">

            <div class="w-full max-w-[420px] space-y-7 z-10 mb-8">

                <div class="space-y-1.5">
                    <h1 class="text-[2rem] md:text-[2.25rem] font-extrabold text-[#0F172A] tracking-tight">Nueva
                        Contraseña</h1>
                    <p class="text-[#64748B] text-[14px]">Crea una contraseña segura para tu cuenta</p>
                </div>

                <form class="space-y-5" @submit.prevent="submitForm">

                    <div class="space-y-1.5">
                        <label for="new_password" class="block text-[12px] font-bold text-[#0F172A] tracking-wide">
                            Nueva Contraseña
                        </label>
                        <div class="relative">
                            <input id="new_password" v-model="form.newPassword"
                                :type="showPassword ? 'text' : 'password'" placeholder="••••••••"
                                class="w-full px-4 py-3.5 bg-[#F4F7FF] border border-transparent rounded-[12px] focus:ring-2 focus:ring-[#0052FF] focus:bg-white transition-all duration-200 outline-none text-[#0F172A] placeholder:text-[#94A3B8] font-bold" />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#94A3B8] hover:text-[#0052FF] transition-colors">
                                <span class="material-symbols-outlined text-[20px]">
                                    {{ showPassword ? 'visibility_off' : 'visibility' }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="confirm_password" class="block text-[12px] font-bold text-[#0F172A] tracking-wide">
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <input id="confirm_password" v-model="form.confirmPassword"
                                :type="showConfirmPassword ? 'text' : 'password'" placeholder="••••••••"
                                class="w-full px-4 py-3.5 bg-[#F4F7FF] border border-transparent rounded-[12px] focus:ring-2 focus:ring-[#0052FF] focus:bg-white transition-all duration-200 outline-none text-[#0F172A] placeholder:text-[#94A3B8] font-bold" />
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#94A3B8] hover:text-[#0052FF] transition-colors">
                                <span class="material-symbols-outlined text-[20px]">
                                    {{ showConfirmPassword ? 'visibility_off' : 'visibility' }}
                                </span>
                            </button>
                        </div>
                        <p v-if="form.confirmPassword && !passwordsMatch" class="text-xs text-red-500 font-medium mt-1">
                            Las contraseñas no coinciden.
                        </p>
                    </div>

                    <div class="bg-[#EEF4FF] p-5 rounded-2xl space-y-4 mt-2 transition-all duration-300">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#64748B]">Seguridad de la
                                contraseña</span>
                            <span class="text-[11px] font-bold transition-colors"
                                :class="strengthScore === 3 ? 'text-green-600' : 'text-[#0052FF]'">
                                {{ strengthText }}
                            </span>
                        </div>

                        <div class="flex gap-1 h-[4px] w-full rounded-full overflow-hidden">
                            <div class="h-full flex-1 transition-colors duration-300 rounded-full"
                                :class="strengthScore >= 1 ? (strengthScore === 3 ? 'bg-green-500' : 'bg-[#0052FF]') : 'bg-[#D8E2F0]'">
                            </div>
                            <div class="h-full flex-1 transition-colors duration-300 rounded-full"
                                :class="strengthScore >= 2 ? (strengthScore === 3 ? 'bg-green-500' : 'bg-[#0052FF]') : 'bg-[#D8E2F0]'">
                            </div>
                            <div class="h-full flex-1 transition-colors duration-300 rounded-full"
                                :class="strengthScore >= 3 ? 'bg-green-500' : 'bg-[#D8E2F0]'"></div>
                        </div>

                        <ul class="space-y-2.5 pt-1">
                            <li class="flex items-center gap-2.5 text-[13px] transition-colors"
                                :class="hasMinLength ? 'text-[#475569]' : 'text-[#64748B]'">
                                <svg v-if="hasMinLength" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10"
                                        :fill="strengthScore === 3 ? '#22C55E' : '#0052FF'" />
                                    <path d="M8 12.5L11 15.5L16 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <svg v-else class="w-4 h-4 text-[#94A3B8] flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                                </svg>
                                <span>Al menos 8 caracteres</span>
                            </li>

                            <li class="flex items-center gap-2.5 text-[13px] transition-colors"
                                :class="hasNumber ? 'text-[#475569]' : 'text-[#64748B]'">
                                <svg v-if="hasNumber" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10"
                                        :fill="strengthScore === 3 ? '#22C55E' : '#0052FF'" />
                                    <path d="M8 12.5L11 15.5L16 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <svg v-else class="w-4 h-4 text-[#94A3B8] flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                                </svg>
                                <span>Contiene un número</span>
                            </li>

                            <li class="flex items-center gap-2.5 text-[13px] transition-colors"
                                :class="hasSpecialChar ? 'text-[#475569]' : 'text-[#64748B]'">
                                <svg v-if="hasSpecialChar" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10"
                                        :fill="strengthScore === 3 ? '#22C55E' : '#0052FF'" />
                                    <path d="M8 12.5L11 15.5L16 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <svg v-else class="w-4 h-4 text-[#94A3B8] flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                                </svg>
                                <span>Un carácter especial (!@#$%)</span>
                            </li>
                        </ul>
                    </div>

                    <button type="submit" :disabled="!isFormValid"
                        class="w-full mt-2 py-3.5 bg-[#0052FF] hover:bg-blue-700 text-white font-bold rounded-[10px] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100 disabled:hover:bg-[#0052FF]">
                        <span class="text-[14px]">Cambiar Contraseña</span>
                        <span class="material-symbols-outlined text-[18px]">published_with_changes</span>
                    </button>
                </form>

                <div class="pt-8 flex flex-col items-center gap-5">
                    <p class="text-[13px] text-[#475569]">
                        ¿Necesitas ayuda?
                        <a href="#" class="text-[#0052FF] font-bold hover:underline">Contactar soporte</a>
                    </p>
                    <a href="/auth/login"
                        class="flex items-center gap-1.5 text-[#64748B] text-[13px] hover:text-[#0F172A] transition-colors">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>

            <div class="absolute bottom-12 right-12 z-0 hidden lg:block">
                <div class="relative w-[70px] h-[85px]">
                    <svg viewBox="0 0 24 24" fill="#C6D4F0" class="w-full h-full drop-shadow-sm">
                        <path d="M12 2L4 5V11.09C4 16.14 7.41 20.85 12 22C16.59 20.85 20 16.14 20 11.09V5L12 2Z" />
                    </svg>
                    <svg class="absolute inset-0 m-auto w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.89 2-2s-.9-2-2-2-2 .89-2 2 .89 2 2 2z" />
                    </svg>
                </div>
            </div>

            <div class="absolute bottom-6 right-6 z-20">
                <button
                    class="w-12 h-12 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.08)] flex items-center justify-center hover:scale-105 active:scale-95 transition-all">
                    <div class="w-5 h-5 bg-[#1E293B] rounded-full flex items-center justify-center">
                        <span class="text-white text-[11px] font-bold leading-none">?</span>
                    </div>
                </button>
            </div>

        </section>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'

// 1. Importamos las herramientas de navegación y el composable
const route = useRoute()
const { updatePassword } = useAuth()

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const loading = ref(false)

// 2. Rescatamos los datos que vienen en la URL (?email=...&code=...)
const email = route.query.email
const code = route.query.code

const form = reactive({
    newPassword: '',
    confirmPassword: ''
})

// --- LÓGICA DE VALIDACIÓN (Tu lógica original se mantiene intacta) ---

const hasMinLength = computed(() => form.newPassword.length >= 8)
const hasNumber = computed(() => /\d/.test(form.newPassword))
const hasSpecialChar = computed(() => /[!@#$%^&*(),.?":{}|<>]/.test(form.newPassword))

const strengthScore = computed(() => {
    let score = 0
    if (hasMinLength.value) score++
    if (hasNumber.value) score++
    if (hasSpecialChar.value) score++
    return score
})

const strengthText = computed(() => {
    if (form.newPassword.length === 0) return 'Ninguna'
    if (strengthScore.value === 1) return 'Baja'
    if (strengthScore.value === 2) return 'Media'
    if (strengthScore.value === 3) return 'Alta'
    return 'Baja'
})

const passwordsMatch = computed(() => {
    return form.newPassword === form.confirmPassword
})

const isFormValid = computed(() => {
    return strengthScore.value === 3 && passwordsMatch.value && form.newPassword !== ''
})

// --- ACCIÓN DEL SUBMIT CORREGIDA ---
const submitForm = async () => {
    if (!isFormValid.value || loading.value) return;

    loading.value = true
    try {
        // Enviamos los 3 datos necesarios a Laravel
        await updatePassword(email, code, form.newPassword)

        console.log("Contraseña actualizada correctamente en la tabla Usuario")

        // Redirigimos a la página final de éxito
        await navigateTo('/auth/updated-password')

    } catch (error) {
        console.error("Error al actualizar:", error)
        alert("Ocurrió un error. Es posible que el código haya expirado o la sesión sea inválida.")
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