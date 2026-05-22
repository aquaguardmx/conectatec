import { ref } from 'vue'

export const useCarrito = () => {
  const { apiUrl } = useApiUrl()
  const isLoading = ref(false)
  const error = ref<string | null>(null)
  const validationErrors = ref<Record<string, string[]>>({})

  // 1. Obtener carrito del usuario actual
  const obtenerCarrito = async () => {
    isLoading.value = true
    error.value = null
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/carrito`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al obtener el carrito.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  // 2. Agregar al carrito
  const agregarAlCarrito = async (id_producto: number, cantidad: number = 1, seleccion: string = '') => {
    isLoading.value = true
    error.value = null
    validationErrors.value = {}
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/carrito`, {
        method: 'POST',
        body: {
          id_producto,
          Cantidad: cantidad,
          Seleccion: seleccion
        },
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      if (err.statusCode === 422) {
        error.value = 'Revisa los datos enviados.'
        validationErrors.value = err.data?.errors || {}
      } else {
        error.value = err.data?.message || 'Error al agregar al carrito.'
      }
      return { success: false, error: error.value, validationErrors: validationErrors.value }
    } finally {
      isLoading.value = false
    }
  }

  // 3. Actualizar cantidad
  const actualizarCantidad = async (idCarrito: number, cantidad: number) => {
    isLoading.value = true
    error.value = null
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/carrito/${idCarrito}`, {
        method: 'PUT',
        body: { Cantidad: cantidad },
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al actualizar cantidad.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  // 4. Eliminar del carrito
  const eliminarDelCarrito = async (idCarrito: number) => {
    isLoading.value = true
    error.value = null
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/carrito/${idCarrito}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al eliminar producto del carrito.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  // 5. Realizar compra (Checkout)
  const realizarCompra = async (
    metodoPago: string,
    nombreTarjeta?: string,
    numeroTarjeta?: string,
    puntoEntrega?: string,
    fechaEntrega?: string,
    horaEntrega?: string
  ) => {
    isLoading.value = true
    error.value = null
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/carrito/checkout`, {
        method: 'POST',
        body: { 
          metodo_pago:    metodoPago,
          nombre_tarjeta: nombreTarjeta,
          numero_tarjeta: numeroTarjeta,
          punto_entrega:  puntoEntrega,
          fecha_entrega:  fechaEntrega,
          hora_entrega:   horaEntrega,
        },
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al procesar la compra.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  return {
    obtenerCarrito,
    agregarAlCarrito,
    actualizarCantidad,
    eliminarDelCarrito,
    realizarCompra,
    isLoading,
    error,
    validationErrors
  }
}
