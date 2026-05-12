<template>
    <div
        class="bg-[#F8FAFC] text-slate-900 min-h-screen flex flex-col items-center justify-center p-6 relative font-sans">

        <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20 items-center z-10">

            <div class="order-2 md:order-1 flex flex-col items-start space-y-6">

                <div class="w-14 h-14 bg-[#EBF1FF] rounded-2xl flex items-center justify-center shadow-sm">
                    <div class="w-8 h-8 bg-[#0B57D0] rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl">check</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h1 class="text-4xl md:text-[2.75rem] font-extrabold leading-tight text-[#0F172A] tracking-tight">
                        ¡Bienvenido a la<br />Comunidad!
                    </h1>
                    <p class="text-[#64748B] text-lg max-w-[420px] leading-relaxed">
                        Tu cuenta ha sido creada con éxito. Ahora puedes acceder al marketplace universitario más
                        grande y seguro.
                    </p>
                </div>

                <div class="pt-2">
                    <button @click="explorar"
                        class="bg-[#0B57D0] hover:bg-blue-800 text-white font-semibold px-6 py-3.5 rounded-xl shadow-[0_8px_16px_rgba(11,87,208,0.2)] active:scale-95 transition-all flex items-center justify-center gap-2 group">
                        Empezar a Explorar
                        <span
                            class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">
                            arrow_forward
                        </span>
                    </button>
                </div>

            </div>

            <div class="order-1 md:order-2 relative w-full max-w-[420px] mx-auto">

                <div class="absolute -top-4 -right-4 md:-right-8 w-4/5 h-4/5 bg-[#EBF1FF] rounded-[2rem] -z-10"></div>

                <div
                    class="relative bg-white rounded-[2rem] p-6 shadow-[0px_20px_40px_rgba(0,0,0,0.04)] border border-slate-50">

                    <div class="relative z-10 space-y-4">

                        <img class="rounded-2xl w-full h-56 object-cover bg-slate-900"
                            src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=800"
                            alt="Equipo trabajando" />

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="bg-[#F4F7FF] p-4 rounded-2xl">
                                <span
                                    class="material-symbols-outlined text-[#006A60] mb-2 block text-xl">shield_with_heart</span>
                                <span class="font-bold block text-sm text-[#0F172A]">Seguridad Total</span>
                                <span
                                    class="text-[#64748B] text-[11px] leading-tight block mt-1">Transacciones<br />protegidas</span>
                            </div>

                            <div class="bg-[#F4F7FF] p-4 rounded-2xl">
                                <span
                                    class="material-symbols-outlined text-[#0B57D0] mb-2 block text-xl">rocket_launch</span>
                                <span class="font-bold block text-sm text-[#0F172A]">Sin Comisiones</span>
                                <span class="text-[#64748B] text-[11px] leading-tight block mt-1">Para
                                    ventas<br />estudiantiles</span>
                            </div>
                        </div>

                        <div class="bg-[#F4F7FF] p-4 rounded-2xl flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#0B57D0] fill-current">verified</span>
                            <p class="text-[13px] font-bold text-[#0F172A]">Perfil verificado por la universidad</p>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <footer class="absolute bottom-8 text-[#94A3B8] text-[10px] font-bold tracking-[0.2em] uppercase">
            Conectatec © 2024 • El Futuro es Compartido
        </footer>

    </div>
</template>

<script setup>
const explorar = () => {
    console.log("Ir al dashboard o marketplace 🚀")
}
</script>

<style>
/* Asegura que los íconos con la clase 'fill-current' se vean rellenados si usas la variable de Material Symbols */
.material-symbols-outlined.fill-current {
    font-variation-settings: 'FILL' 1;
}
</style>