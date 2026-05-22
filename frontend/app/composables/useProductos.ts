import { ref } from 'vue'

export const useProductos = () => {
  const { apiUrl } = useApiUrl()

  // Estados para manejar la interfaz de usuario
  const isLoading = ref(false)
  const error = ref<string | null>(null)
  const validationErrors = ref<Record<string, string[]>>({})

  const publicarProducto = async (productoData: any) => {
    // 1. Reiniciamos los estados antes de enviar la petición
    isLoading.value = true
    error.value = null
    validationErrors.value = {}

    try {
      // 2. Hacemos la petición a tu API de Laravel
      const tokenCookie = useCookie('auth_token')
      
      const response = await $fetch(`${apiUrl}/productos`, {
        method: 'POST',
        body: productoData,
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })

      // 3. Si todo sale bien, retornamos éxito
      return { success: true, data: response }

    } catch (err: any) {
      // 4. Manejo de errores específico para Laravel
      
      // Si Laravel devuelve un error de validación (Código 422)
      if (err.statusCode === 422) {
        error.value = 'Por favor, revisa los campos del formulario.'
        validationErrors.value = err.data?.errors || {}
      } else {
        // Otros errores (500 Server Error, etc.)
        error.value = err.data?.message || 'Ocurrió un error inesperado al conectar con el servidor.'
      }

      return { success: false, error: error.value, validationErrors: validationErrors.value }
      
    } finally {
      // 5. Apagamos el estado de carga sin importar si hubo éxito o error
      isLoading.value = false
    }
  }

  const obtenerMisProductos = async () => {
    isLoading.value = true
    error.value = null
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/productos`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al obtener productos.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }
  const obtenerProducto = async (id: number | string) => {
    isLoading.value = true
    error.value = null
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/productos/${id}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al obtener el producto.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const actualizarProducto = async (id: number | string, productoData: any) => {
    isLoading.value = true
    error.value = null
    validationErrors.value = {}
    try {
      const tokenCookie = useCookie('auth_token')
      
      let fetchMethod: 'PUT' | 'POST' = 'PUT'
      // Laravel no procesa multipart/form-data en PUT, usamos el truco de POST + _method
      if (productoData instanceof FormData) {
        fetchMethod = 'POST'
        productoData.append('_method', 'PUT')
      }

      const response: any = await $fetch(`${apiUrl}/productos/${id}`, {
        method: fetchMethod,
        body: productoData,
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      if (err.statusCode === 422) {
        error.value = 'Por favor, revisa los campos del formulario.'
        validationErrors.value = err.data?.errors || {}
      } else {
        error.value = err.data?.message || 'Ocurrió un error inesperado al conectar con el servidor.'
      }
      return { success: false, error: error.value, validationErrors: validationErrors.value }
    } finally {
      isLoading.value = false
    }
  }

  const eliminarProducto = async (id: number | string) => {
    isLoading.value = true
    error.value = null
    try {
      const tokenCookie = useCookie('auth_token')
      const response: any = await $fetch(`${apiUrl}/productos/${id}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'Authorization': tokenCookie.value ? `Bearer ${tokenCookie.value}` : ''
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al eliminar el producto.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const obtenerTodosProductos = async () => {
    isLoading.value = true
    error.value = null
    try {
      const response: any = await $fetch(`${apiUrl}/public/productos`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json'
        }
      })
      
      // Si la respuesta es directa el array (dependiendo de la versión de Laravel/fetch)
      if (Array.isArray(response)) {
        return { success: true, data: { success: true, data: response } }
      }
      
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al obtener productos.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const obtenerProductoPublico = async (id: number | string) => {
    isLoading.value = true
    error.value = null
    try {
      const response: any = await $fetch(`${apiUrl}/public/productos/${id}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json'
        }
      })
      return { success: true, data: response }
    } catch (err: any) {
      error.value = err.data?.message || 'Error al obtener el producto.'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  return {
    publicarProducto,
    obtenerTodosProductos,
    obtenerProductoPublico,
    obtenerMisProductos,
    obtenerProducto,
    actualizarProducto,
    eliminarProducto,
    isLoading,
    error,
    validationErrors
  }
}