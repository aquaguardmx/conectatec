<template>
    <div class="min-h-screen flex flex-col md:flex-row font-sans text-slate-900 bg-white overflow-hidden">

        <section
            class="hidden lg:flex w-1/2 relative bg-gradient-to-b from-[#003EC7] to-[#4A85FF] overflow-hidden flex-col items-center justify-center p-12">

            <div class="absolute inset-0 opacity-[0.15] pointer-events-none"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M19 19V0h2v19h19v2H21v19h-2V21H0v-2h19z\' fill=\'%23ffffff\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); background-size: 32px 32px;">
            </div>

            <div class="relative z-10 text-center max-w-[420px] flex flex-col items-center">

                <div
                    class="w-16 h-16 rounded-full border border-white/20 bg-white/10 backdrop-blur-sm flex items-center justify-center mb-8 shadow-sm">
                    <span class="material-symbols-outlined text-white text-3xl">restore</span>
                </div>

                <h1
                    class="text-[2.5rem] md:text-[2.75rem] leading-[1.15] font-extrabold text-white mb-6 tracking-tight">
                    Seguridad que<br>impulsa tu talento.
                </h1>

                <p class="text-[#B7C4FF] text-[15px] leading-relaxed font-medium">
                    En Conectatec protegemos tu identidad digital para que solo te preocupes por lo que importa: crear
                    conexiones significativas.
                </p>
            </div>
        </section>

        <section class="flex-1 relative flex flex-col justify-center items-center p-6 lg:p-12 bg-[#FAFAFC]">

            <div
                class="absolute top-6 left-6 lg:top-8 lg:left-8 bg-slate-800 text-white text-[11px] font-mono font-bold px-3 py-1.5 rounded-md shadow-md z-50 tracking-wider">
                código 123456
            </div>

            <div
                class="absolute top-6 right-6 lg:top-10 lg:right-12 text-[1.25rem] font-extrabold tracking-tight text-[#0F172A]">
                Conectatec
            </div>

            <div class="w-full max-w-[400px] mt-10 lg:mt-0">

                <div class="mb-8 space-y-3">
                    <h2 class="text-[1.75rem] font-extrabold tracking-tight text-[#0F172A]">Verificar Identidad</h2>
                    <p class="text-[14px] text-[#64748B] leading-relaxed font-medium">
                        Hemos enviado un código de 6 dígitos a tu <span class="text-[#003EC7] font-bold">correo
                            institucional</span> para confirmar tu solicitud.
                    </p>
                </div>

                <form @submit.prevent="verifyCode">

                    <div class="flex justify-between gap-2 sm:gap-3 mb-8">
                        <input v-for="(digit, index) in 6" :key="index" v-model="digits[index]" type="text"
                            maxlength="1" @input="handleInput($event, index)"
                            @keydown.backspace="handleBackspace($event, index)"
                            class="w-12 h-14 sm:w-14 sm:h-16 bg-[#F4F7FF] text-center text-xl font-extrabold rounded-xl border border-transparent focus:border-[#0052FF] focus:ring-2 focus:ring-[#0052FF]/20 focus:bg-white text-[#0F172A] transition-all outline-none" />
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-[#0052FF] hover:bg-blue-700 text-white font-bold rounded-xl shadow-[0_8px_20px_rgba(0,82,255,0.2)] active:scale-95 transition-all duration-200">
                        Verificar Código
                    </button>

                    <div class="mt-6 flex flex-col items-center gap-5">
                        <p class="text-[13px] text-[#475569] font-medium">
                            ¿No recibiste el código?
                            <button type="button"
                                class="text-[#003EC7] font-bold hover:underline ml-1">Reenviar</button>
                        </p>

                        <NuxtLink to="/auth/login"
                            class="flex items-center gap-1.5 text-[13px] text-[#64748B] hover:text-[#0F172A] font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                            Volver al inicio de sesión
                        </NuxtLink>
                    </div>
                </form>

                <div class="mt-12 bg-[#F4F7FF] rounded-2xl p-5 flex items-start gap-4">
                    <div class="bg-white p-1.5 rounded-lg shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-[#005761] text-[18px]"
                            style="font-variation-settings: 'FILL' 1;">verified_user</span>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-[#0F172A] mb-1">Entorno Seguro</h4>
                        <p class="text-[11px] text-[#64748B] leading-relaxed font-medium">
                            Esta es una conexión cifrada de extremo a extremo administrada por el sistema de seguridad
                            de Conectatec.
                        </p>
                    </div>
                </div>

            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const route = useRoute()
// El email viene de la URL, lo aseguramos como String
const email = route.query.email
const { verifyResetCode } = useAuth()

const digits = reactive(['', '', '', '', '', ''])

const handleInput = (e, index) => {
    const val = e.target.value
    if (val && index < 5) {
        const nextInput = e.target.parentElement.querySelectorAll('input')[index + 1]
        if (nextInput) nextInput.focus()
    }
}

const handleBackspace = (e, index) => {
    if (e.key === 'Backspace' && !digits[index] && index > 0) {
        const prevInput = e.target.parentElement.querySelectorAll('input')[index - 1]
        if (prevInput) prevInput.focus()
    }
}

const verifyCode = async () => {
    const fullCode = digits.join('')

    if (fullCode.length < 6) {
        alert("Por favor completa el código de 6 dígitos")
        return
    }

    try {
        // CORRECCIÓN: Para ver el objeto en el log usamos coma, no suma
        console.log("Enviando a Laravel:", { email: email, code: fullCode });

        // Llamada al composable
        await verifyResetCode(email, fullCode)

        await navigateTo({
            path: '/auth/new-password',
            query: { email: email, code: fullCode }
        })
    } catch (error) {
        // Si entra aquí es porque Laravel respondió con error (ej. 401)
        console.error("Error detallado:", error);
        alert("Código inválido. El servidor dice que " + fullCode + " no es correcto.");
    }
}
</script>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>