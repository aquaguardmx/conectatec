import { ref } from 'vue'

export const usePedidos = () => {
    const isLoading = ref(false)
    const error = ref<string | null>(null)

    const obtenerPedidos = async () => {
        isLoading.value = true
        error.value = null
        try {
            const tokenCookie = useCookie('auth_token')
            if (!tokenCookie.value) throw new Error('No estás autenticado')

            const response: any = await $fetch('http://127.0.0.1:8000/api/pedidos', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${tokenCookie.value}`
                }
            })
            return { success: true, data: response }
        } catch (err: any) {
            error.value = err.data?.message || err.message || 'Error al obtener pedidos'
            return { success: false, error: error.value }
        } finally {
            isLoading.value = false
        }
    }

    return {
        isLoading,
        error,
        obtenerPedidos
    }
}
