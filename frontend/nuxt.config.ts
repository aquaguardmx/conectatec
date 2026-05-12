export default defineNuxtConfig({
  compatibilityDate: '2025-07-15', 
  devtools: { enabled: true },

  // 1. Agregamos la configuración de los iconos aquí
  app: {
    head: {
      link: [
        { 
          rel: 'stylesheet', 
          href: 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200' 
        }
      ]
    }
  },

  // Tus módulos existentes
  modules: [
    '@nuxtjs/tailwindcss'
  ],

  vite: {
    optimizeDeps: {
      include: ['qs']
    }
  }
})