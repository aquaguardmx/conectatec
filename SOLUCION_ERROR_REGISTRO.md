# Solución: Error "Failed to fetch" en Registro (Producción)

## 🔴 Problemas Identificados y Corregidos

### 1. **Tabla `personal_access_tokens` no existe** (Backend)
El error en los logs: `SQLSTATE[42P01]: Undefined table: relation "personal_access_tokens" does not exist`

**Causa:** Las migraciones de Laravel Sanctum no se ejecutaron correctamente.

**Solución:** 
```bash
docker-compose exec backend php artisan migrate --force
```

### 2. **Frontend no encuentra el Backend API** (Frontend)
Error: "Failed to fetch" en el navegador aunque el servidor esté levantado en Coolify.

**Causa:** `NUXT_PUBLIC_API_BASE` no está disponible durante el build de Nuxt, por lo que el frontend no sabe a dónde hacer las peticiones.

**Cambios realizados:**
- ✅ Actualizado `frontend/Dockerfile` para aceptar `NUXT_PUBLIC_API_BASE` como argumento de build
- ✅ Configurado `docker-compose.yml` para pasar el argumento al build
- ✅ Creado `.env.example` con variables necesarias

### 3. **Configuración de CORS/Sanctum** (Backend)
**Cambios realizados:**
- ✅ Actualizado `config/sanctum.php` para usar variable de entorno
- ✅ Configurado `.env` con `SANCTUM_STATEFUL_DOMAINS`
- ✅ Habilitado middleware CORS en `bootstrap/app.php`

### 4. **Configuración de Entorno (.env)** (Backend)
**Cambios realizados:**
- ✅ `APP_ENV=production`
- ✅ `APP_DEBUG=false`
- ✅ `APP_URL=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io`
- ✅ `DB_HOST=db` (para Docker)
- ✅ `SANCTUM_STATEFUL_DOMAINS` configurado

### 5. **Script de Entrada Docker Mejorado** (Backend)
El `docker-entrypoint.sh` ahora:
- Intenta conexión a la BD hasta 30 veces (antes 5)
- Ejecuta migraciones en bucle hasta que tenga éxito
- Proporciona mejor logging

## 🚀 Pasos para Desplegar en Coolify

### Paso 1: Configurar Variables de Entorno en Coolify

Ve a **Environment Variables** en tu proyecto de Coolify y agrega:

```
BACKEND_URL=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
NUXT_PUBLIC_API_BASE=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
DB_DATABASE=tienda_sistema
DB_USERNAME=postgres
DB_PASSWORD=1307
```

⚠️ **IMPORTANTE:** La variable se llama `NUXT_PUBLIC_API_BASE` (sin guión bajo antes de PUBLIC)

### Paso 2: Reconstruir el Proyecto

1. En Coolify: **Projects → Deployments**
2. Haz clic en **"Deploy"**
3. Espera a que se reconstruyan ambos servicios (puede tardar 5-10 minutos)

### Paso 3: Ejecutar Migraciones (en caso necesario)

Si es la primera vez:
```bash
docker-compose exec backend php artisan migrate --force
```

### Paso 4: Verificar que Funcione

1. **En Coolify, revisa los logs del build** - Busca `Building with NUXT_PUBLIC_API_BASE=...`
2. **Abre la página de registro en tu navegador**
3. **Abre la consola (F12 → Network)**
4. **Intenta registrarte**
5. **Deberías ver una petición POST a `/api/register` exitosa**

## 📋 Archivos Modificados

| Archivo | Cambio |
|---------|--------|
| `backend/.env` | Configuración de producción |
| `backend/config/sanctum.php` | CORS habilitado |
| `backend/bootstrap/app.php` | Middleware CORS |
| `backend/docker-entrypoint.sh` | Reintentos mejorados |
| `frontend/Dockerfile` | **NUEVO: ARG para NUXT_PUBLIC_API_BASE** |
| `docker-compose.yml` | Build args agregados |
| `.env.example` | **NUEVO: Variables de ejemplo** |

## 🔍 Solucionar si Persiste el Error

### Si el error "Failed to fetch" sigue apareciendo:

1. **Verifica las variables en Coolify:**
   - Ve a Environment Variables
   - Confirma que `NUXT_PUBLIC_API_BASE` está exactamente así (con PUBLIC, no con guión bajo)

2. **Limpia el caché de build:**
   - En Coolify: **Projects → Settings → Clear Build Cache**
   - Luego redeploy

3. **Verifica los logs:**
   ```bash
   # Frontend debe mostrar la URL correcta
   # "Building with NUXT_PUBLIC_API_BASE=http://vgoc4gsscwk0o8okcg4ggg08..."
   
   # Backend debe responder a OPTIONS (CORS preflight)
   curl -vvv -X OPTIONS http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io/api/register
   ```

4. **Test manual en la consola del navegador:**
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

## 📊 Checklist Final

- [ ] Variables de entorno configuradas en Coolify
- [ ] Proyecto reconstruido (deploy completado)
- [ ] Migraciones ejecutadas
- [ ] `NUXT_PUBLIC_API_BASE` visible en los logs del build
- [ ] Página de registro carga sin errores
- [ ] Petición POST al API se completa exitosamente
- [ ] Usuario se registra correctamente
