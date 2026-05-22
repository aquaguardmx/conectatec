import { ref } from 'vue'

export const useResenas = () => {
    const { apiUrl } = useApiUrl()
    const isLoading = ref(false)
    const error = ref<string | null>(null)

    const obtenerComprasPendientes = async () => {
        isLoading.value = true
        error.value = null
        try {
            const tokenCookie = useCookie('auth_token')
            if (!tokenCookie.value) throw new Error('No estás autenticado')

            const response: any = await $fetch(`${apiUrl}/resenas/compras`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${tokenCookie.value}`
                }
            })
            return { success: true, data: response }
        } catch (err: any) {
            error.value = err.data?.message || err.message || 'Error al obtener compras'
            return { success: false, error: error.value }
        } finally {
            isLoading.value = false
        }
    }

    const enviarResena = async (idProducto: number, puntuacion: number, comentario: string) => {
        isLoading.value = true
        error.value = null
        try {
            const tokenCookie = useCookie('auth_token')
            if (!tokenCookie.value) throw new Error('No estás autenticado')

            const response: any = await $fetch(`${apiUrl}/resenas`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${tokenCookie.value}`
                },
                body: {
                    id_producto: idProducto,
                    puntuacion,
                    comentario
                }
            })
            return { success: true, data: response }
        } catch (err: any) {
            error.value = err.data?.message || err.message || 'Error al enviar reseña'
            return { success: false, error: error.value }
        } finally {
            isLoading.value = false
        }
    }

    return {
        isLoading,
        error,
        obtenerComprasPendientes,
        enviarResena
    }
}
