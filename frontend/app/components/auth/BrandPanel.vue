<template>
  <div class="relative hidden lg:flex lg:w-1/2 overflow-hidden flex-col justify-between p-16">
    <!-- Fondo de imagen con overlay oscuro -->
    <div 
      class="absolute inset-0 z-0 bg-cover bg-center"
      style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop');"
    ></div>
    <div class="absolute inset-0 z-0 bg-gradient-to-r from-[#00081a]/90 via-[#00102a]/80 to-[#001b3d]/60"></div>
    <div class="absolute inset-0 z-0 bg-gradient-to-t from-[#00081a] via-transparent to-transparent"></div>

    <!-- Contenido Superior -->
    <div class="relative z-10 pt-4">
      <span class="text-white font-extrabold text-2xl tracking-tight">Conectatec</span>
      
      <div class="mt-24">
        <h1 class="text-white text-5xl font-bold leading-[1.1] mb-6">
          Únete a la red<br>
          universitaria<br>
          más grande
        </h1>
        <p class="text-white/80 text-lg max-w-md leading-relaxed font-light">
          Conecta con miles de estudiantes, encuentra recursos exclusivos y emprende en tu comunidad tecnológica.
        </p>
      </div>
    </div>

    <!-- Contenido Inferior (Tarjeta de miembros) -->
    <div class="relative z-10 pb-8">
      <div class="inline-flex items-center gap-4 bg-white/5 backdrop-blur-md border border-white/10 py-3 px-4 rounded-xl">
        <div class="flex -space-x-3">
          <img class="w-10 h-10 rounded-full border-2 border-[#1a2b4c]" src="https://i.pravatar.cc/100?img=11" alt="User 1">
          <img class="w-10 h-10 rounded-full border-2 border-[#1a2b4c]" src="https://i.pravatar.cc/100?img=12" alt="User 2">
          <img class="w-10 h-10 rounded-full border-2 border-[#1a2b4c]" src="https://i.pravatar.cc/100?img=13" alt="User 3">
        </div>
        <span class="text-white/90 text-sm font-medium pr-2">+5,000 miembros activos hoy</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>
