<template>
    <div class="min-h-screen flex flex-col font-sans text-slate-900 bg-[#F8F9FF]">

        <header class="w-full flex justify-between items-center px-8 py-6 shrink-0">
            <div class="text-[1.25rem] font-extrabold text-[#0D1C2E] tracking-tight">
                Conectatec
            </div>
            <button
                class="w-7 h-7 bg-[#434656] text-white rounded-full flex items-center justify-center transition-transform hover:scale-105 active:scale-95 shadow-sm">
                <span class="text-xs font-bold leading-none">?</span>
            </button>
        </header>

        <main class="flex-grow flex items-center justify-center px-4 md:px-8 py-6">

            <div
                class="w-full max-w-[1040px] bg-white rounded-[16px] shadow-[0_12px_32px_rgba(13,28,46,0.06)] flex flex-col md:flex-row overflow-hidden border border-transparent">

                <div class="hidden md:block md:w-1/2 relative bg-slate-100 min-h-[500px]">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop"
                        alt="Campus Corporativo" class="absolute inset-0 w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-[#003EC7]/5 mix-blend-overlay"></div>
                </div>

                <div
                    class="w-full md:w-1/2 px-10 py-14 lg:px-16 lg:py-16 flex flex-col items-center text-center relative">

                    <div class="w-20 h-20 bg-[#0052FF] rounded-[16px] flex items-center justify-center mb-8 shadow-sm">
                        <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#0052FF] text-[22px]"
                                style="font-variation-settings: 'FILL' 1, 'wght' 700;">check</span>
                        </div>
                    </div>

                    <h1 class="text-[2.1rem] font-extrabold text-[#0D1C2E] tracking-tight leading-tight mb-4">
                        Contraseña Actualizada
                    </h1>
                    <p class="text-[15px] text-[#54647A] leading-relaxed max-w-[310px] font-medium mb-10">
                        Tu contraseña ha sido restablecida exitosamente. Ya puedes acceder a tu cuenta.
                    </p>

                    <button @click="goToLogin"
                        class="w-full py-4 bg-[#0052FF] hover:bg-blue-700 text-white font-bold text-[15px] rounded-[10px] shadow-[0_8px_16px_rgba(0,82,255,0.15)] active:scale-95 transition-all duration-200 mb-8">
                        Ir al Inicio de Sesión
                    </button>

                    <div class="w-full bg-[#EFF4FF] rounded-xl p-5 flex items-start gap-4 mb-14 text-left">
                        <div
                            class="w-[22px] h-[22px] bg-[#003EC7] rounded-full flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-white text-[11px] font-serif italic font-bold leading-none">i</span>
                        </div>
                        <div>
                            <h3 class="text-[13px] font-bold text-[#0D1C2E] mb-1">Consejo de Seguridad</h3>
                            <p class="text-[12px] text-[#54647A] leading-relaxed">
                                Asegúrate de no compartir tus credenciales y activar la verificación en dos pasos desde
                                tu perfil.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-5 text-[#54647A] mt-auto w-full">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[#005761] text-[15px]"
                                style="font-variation-settings: 'FILL' 1;">verified_user</span>
                            <span class="text-[10px] font-bold tracking-widest uppercase text-[#434656]">Conexión
                                Segura</span>
                        </div>
                        <div class="w-px h-3.5 bg-[#C3C5D9]"></div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[#005761] text-[15px]"
                                style="font-variation-settings: 'FILL' 1;">shield</span>
                            <span class="text-[10px] font-bold tracking-widest uppercase text-[#434656]">Protección
                                SSL</span>
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <footer
            class="w-full flex justify-between items-center px-8 py-6 shrink-0 text-[#737688] text-[10px] font-semibold tracking-wider">
            <p>© 2024 Conectatec. Todos los derechos reservados.</p>
            <div class="flex gap-6 uppercase">
                <a href="#" class="hover:text-[#0D1C2E] transition-colors">Privacidad</a>
                <a href="#" class="hover:text-[#0D1C2E] transition-colors">Términos</a>
                <a href="#" class="hover:text-[#0D1C2E] transition-colors">Soporte</a>
            </div>
        </footer>

    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goToLogin = () => {
    router.push('/auth/login')
}
</script>

<style scoped>
/* Iconos de Material Design: configuración exacta para renderizar versión rellenada (FILL 1) */
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>