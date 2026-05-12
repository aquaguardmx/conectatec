<template>
    <div class="bg-background text-on-surface font-body h-screen overflow-hidden flex flex-col items-center relative">

        <header
            class="w-full z-50 bg-background/80 backdrop-blur-xl flex justify-between items-center px-6 py-4 shrink-0">
            <div class="text-xl font-bold text-primary dark:text-ffffff tracking-tighter">
                Conectatec
            </div>
            <button
                class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors active:scale-95 duration-200">
                <span class="material-symbols-outlined">help_outline</span>
            </button>
        </header>

        <main class="flex-grow flex items-center justify-center px-6 w-full max-w-7xl mx-auto overflow-hidden">
            <div class="max-w-xl w-full text-center space-y-6 md:space-y-8">
                <div class="relative inline-flex items-center justify-center">
                    <div class="absolute inset-0 bg-error-container opacity-40 blur-3xl rounded-full"></div>
                    <div
                        class="relative w-20 h-20 md:w-24 md:h-24 bg-surface-container-lowest rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.06)] flex items-center justify-center">
                        <span class="material-symbols-outlined text-5xl md:text-6xl text-error"
                            style="font-variation-settings: 'FILL' 0;">warning</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <h1 class="text-3xl md:text-5xl font-extrabold text-on-surface tracking-tight leading-tight">
                        Algo salió mal con tu registro
                    </h1>
                    <p class="text-base md:text-lg text-on-surface-variant leading-relaxed max-w-lg mx-auto">
                        Los datos ingresados no son válidos o el correo institucional ya se encuentra registrado. Por
                        favor, revisa la información e inténtalo de nuevo.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <NuxtLink to="/auth/register"
                        class="group relative overflow-hidden bg-gradient-to-br from-primary to-primary-container p-4 rounded-xl flex flex-col items-center justify-center transition-all duration-300 hover:shadow-lg active:scale-95 h-28 md:h-32 w-full">
                        <span class="material-symbols-outlined text-on-primary text-2xl md:text-3xl mb-2">refresh</span>
                        <span class="text-on-primary font-bold tracking-wide text-sm md:text-base">Volver a
                            Intentar</span>
                    </NuxtLink>

                    <button
                        class="bg-secondary-container p-4 rounded-xl flex flex-col items-center justify-center transition-all duration-300 hover:bg-surface-container-highest active:scale-95 h-28 md:h-32 w-full">
                        <span
                            class="material-symbols-outlined text-on-secondary-container text-2xl md:text-3xl mb-2">support_agent</span>
                        <span class="text-on-secondary-container font-bold tracking-wide text-sm md:text-base">Contactar
                            a Soporte</span>
                    </button>
                </div>

                <div class="mt-8 p-5 bg-surface-container-low rounded-xl text-left border-l-4 border-primary w-full">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-primary mt-1">info</span>
                        <div class="flex-1">
                            <h3 class="font-bold text-on-surface text-sm md:text-base">¿Necesitas ayuda inmediata?</h3>
                            <p class="text-[12px] md:text-sm text-on-surface-variant">Puedes escribirnos a
                                soporte@conectatec.edu o revisar nuestro centro de ayuda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer
            class="w-full py-6 text-center shrink-0 text-[10px] text-on-surface-variant/70 tracking-widest uppercase bg-background">
            CONECTATEC © 2024 • EL FUTURO ES COMPARTIDO
        </footer>

        <div class="absolute inset-0 -z-10 overflow-hidden w-full h-full">
            <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-primary opacity-5 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-20 right-1/4 w-80 h-80 bg-tertiary opacity-5 blur-[100px] rounded-full"></div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>