// frontend/composables/useAuth.ts

export const useAuth = () => {
  const { apiUrl } = useApiUrl()

  // --- REGISTRO ---
  const registerStudent = async (formData: any) => {
    try {
      const response = await $fetch(`${apiUrl}/register`, {
        method: 'POST',
        body: {
          nombre: formData.nombreCompleto,
          Matricula: formData.matricula,
          Correo_Institucional: formData.email, // Mapeo a tu BD
          contrasena: formData.password,        // Mapeo a tu BD
          Telefono: formData.telefono,
          Rol: formData.rol
        }
      })
      return response
    } catch (error: any) {
      console.error('Error al registrar:', error.data || error)
      throw error
    }
  }

  // --- LOGIN ---
  const login = async (credentials: any) => {
    try {
      const response: any = await $fetch(`${apiUrl}/login`, {
        method: 'POST',
        body: {
          // Asegúrate de enviar los nombres que tu controlador espera
          email: credentials.email,
          password: credentials.password
        }
      })

      // Guardamos el token
      const tokenCookie = useCookie('auth_token', { maxAge: 60 * 60 * 24 * 7 })
      tokenCookie.value = response.token

      // --- CORRECCIÓN AQUÍ ---
      // Verificamos si existe 'user' y usamos 'Rol' con mayúscula
      if (response.user && response.user.Rol) {
        const roleCookie = useCookie('user_role', { maxAge: 60 * 60 * 24 * 7 })
        roleCookie.value = response.user.Rol

        const nameCookie = useCookie('user_name', { maxAge: 60 * 60 * 24 * 7 })
        nameCookie.value = response.user.nombre
      }

      return response
    } catch (error: any) {
      console.error('Error al iniciar sesión:', error.data || error)
      throw error
    }
  }

  // --- PASO 1: SOLICITAR RECUPERACIÓN (Verificar Email) ---
  const requestPasswordReset = async (email: string) => {
    try {
      const response = await $fetch(`${apiUrl}/forgot-password`, {
        method: 'POST',
        body: { email } // Laravel buscará este email en 'Correo_Institucional'
      })
      return response
    } catch (error: any) {
      console.error('Error al solicitar código:', error.data || error)
      throw error
    }
  }

  // --- PASO 2: VERIFICAR CÓDIGO (Opcional si lo haces en el paso final) ---
  const verifyResetCode = async (email: string, code: string) => {
    try {
      // Esta función es útil para validar el código antes de mostrar la pantalla de nueva password
      const response = await $fetch(`${apiUrl}/verify-code`, {
        method: 'POST',
        body: { email, code }
      })
      return response
    } catch (error: any) {
      throw error
    }
  }

  // --- PASO 3: ACTUALIZAR CONTRASEÑA FINAL ---
  const updatePassword = async (email: string, code: string, password: string) => {
    try {
      const response = await $fetch(`${apiUrl}/reset-password`, {
        method: 'POST',
        body: { 
          email: email, 
          code: code, 
          password: password // Laravel lo recibirá y lo guardará como 'contrasena'
        }
      })
      return response
    } catch (error: any) {
      console.error('Error al actualizar contraseña:', error.data || error)
      throw error
    }
  }

  // Exportamos todas las funciones necesarias para tu flujo
  return { 
    registerStudent, 
    login, 
    requestPasswordReset, 
    verifyResetCode, 
    updatePassword 
  }
}