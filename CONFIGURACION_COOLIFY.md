# Configuración en Coolify - Solución Error "Failed to fetch"

## Problema
El frontend no puede conectarse al backend en producción porque `NUXT_PUBLIC_API_BASE` no está configurada correctamente.

## Solución en Coolify

### 1. Configurar Variables de Entorno del Proyecto

En tu proyecto de Coolify, ve a **Environment Variables** y agrega las siguientes variables:

```
BACKEND_URL=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
NUXT_PUBLIC_API_BASE=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
DB_DATABASE=tienda_sistema
DB_USERNAME=postgres
DB_PASSWORD=1307
```

### 2. Importante: Nombres de las Variables

- **Backend usa:** `BACKEND_URL` y `APP_URL`
- **Frontend usa:** `NUXT_PUBLIC_API_BASE` (¡debe ser exactamente así!)
- **Base de datos:** `DB_*`

### 3. Verificar la Configuración

Después de establecer las variables:

1. **Reconstruir el proyecto:**
   - Ve a "Deployments"
   - Haz clic en "Deploy"
   - Espera a que se reconstruyan ambos servicios

2. **Verificar los logs:**
   - Abre los logs del frontend
   - Busca: `Building with NUXT_PUBLIC_API_BASE=...`
   - Verifica que sea la URL correcta

3. **Probar en el navegador:**
   - Abre la página de registro
   - Abre la consola del navegador (F12 → Network)
   - Intenta registrarte
   - Deberías ver una petición POST a `/api/register` exitosa

## Valores Correctos para tu Dominio

| Variable | Valor |
|----------|-------|
| `BACKEND_URL` | `http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io` |
| `NUXT_PUBLIC_API_BASE` | `http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io` |
| `APP_URL` | `http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io` |

## Solucionar si Persiste el Error

### Opción 1: Verificar que el valor se pasó al build
```bash
# En los logs del build del frontend, deberías ver algo como:
# "Building with NUXT_PUBLIC_API_BASE=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io"
```

### Opción 2: Limpiar caché de construcción
- En Coolify: **Projects → Settings → Clear Build Cache**
- Luego redeploy

### Opción 3: Verificar CORS en el backend
```bash
# El backend debe responder con estos headers:
# Access-Control-Allow-Origin: *
# Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
# Access-Control-Allow-Headers: Content-Type, Authorization
```

## Test Manual

Desde tu navegador, abre la consola y prueba:

```javascript
fetch('http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io/api/register', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    nombre: 'Test',
    Matricula: 'TEST001',
    Correo_Institucional: 'test@orizaba.tecnm.mx',
    contrasena: 'Password123!',
    Telefono: '5551234567',
    Rol: 'estudiante'
  })
})
.then(r => r.json())
.then(d => console.log(d))
.catch(e => console.error(e))
```

Si responde con un usuario creado, ¡el problema está resuelto!
