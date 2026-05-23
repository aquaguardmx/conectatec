# Configuración de Coolify - Backend, Frontend y Base de Datos

## 🎯 URLs de tu Proyecto

| Servicio | URL |
|----------|-----|
| Backend | `http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io` |
| Frontend | `http://m8c8g80sk80oc8cosskcsgc4.172.245.228.126.sslip.io` |
| Base de Datos | PostgreSQL en Coolify |

---

## ⚙️ PASO 1: Configurar BACKEND

En Coolify, ve a tu aplicación Backend y configura estas **Environment Variables**:

```
APP_NAME=Conectatec Backend
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:SGFIBVhsZ0wwvQSihANCWxsvitnrak3iEwI+G9cWd8Q=
APP_URL=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io

DB_CONNECTION=pgsql
DB_HOST=[NOMBRE_DEL_CONTENEDOR_BD]
DB_PORT=5432
DB_DATABASE=tienda_sistema
DB_USERNAME=postgres
DB_PASSWORD=1307

LOG_LEVEL=error
FILESYSTEM_DISK=public

SANCTUM_STATEFUL_DOMAINS=m8c8g80sk80oc8cosskcsgc4.172.245.228.126.sslip.io,vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
```

⚠️ **IMPORTANTE:** El `DB_HOST` depende de cómo está configurada tu BD en Coolify. Puede ser:
- Nombre del contenedor: `postgresql-database-a8cs4s849000ca4w8cocg8gw`
- O una URL externa si tu BD está en un servidor separado

---

## ⚙️ PASO 2: Configurar FRONTEND

En Coolify, ve a tu aplicación Frontend y configura estas **Environment Variables**:

```
NUXT_PUBLIC_API_BASE=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
PORT=3000
```

**Eso es todo para el frontend.**

---

## ⚙️ PASO 3: Configurar BASE DE DATOS

En la aplicación PostgreSQL en Coolify, asegúrate que tenga estas variables:

```
POSTGRES_DB=tienda_sistema
POSTGRES_USER=postgres
POSTGRES_PASSWORD=1307
```

---

## 🚀 PASO 4: Desplegar (Deploy)

**Para el Backend:**
1. Ve a la aplicación Backend en Coolify
2. Haz clic en **"Deploy"** o **"Redeploy"**
3. Espera a que termine (5-10 minutos)
4. Verifica en los logs que las migraciones se ejecutaron

**Para el Frontend:**
1. Ve a la aplicación Frontend en Coolify
2. Haz clic en **"Deploy"** o **"Redeploy"**
3. Espera a que termine (5-10 minutos)

---

## ✅ PASO 5: Verificar que Funcione

### Test 1: Verifica que el Backend esté corriendo
```bash
curl http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io/up
```
Deberías obtener un `200 OK`

### Test 2: Abre el Frontend en el navegador
```
http://m8c8g80sk80oc8cosskcsgc4.172.245.228.126.sslip.io
```

### Test 3: Intenta registrarte
1. Ve a la página de registro
2. Abre la consola del navegador (F12)
3. Intenta crear una cuenta
4. En la pestaña "Network" deberías ver:
   - `POST /api/register` → Status **201** (exitoso)
   - No debería haber error CORS

---

## 🔍 Si Algo Falla

### Error: "Failed to fetch" en el navegador
**Solución:** Verifica que `NUXT_PUBLIC_API_BASE` en Frontend sea exactamente:
```
http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
```

### Error: "No puedo conectar a la BD"
**Solución:** En Backend, verifica el `DB_HOST`. Debería ser el nombre del contenedor PostgreSQL en Coolify.

Para encontrarlo:
1. Ve a Coolify → Base de Datos PostgreSQL
2. Busca el "Container Name" o "Hostname"
3. Úsalo en `DB_HOST`

### Error: CORS bloqueado
**Solución:** Verifica que `SANCTUM_STATEFUL_DOMAINS` incluya ambos dominios sin `http://`:
```
m8c8g80sk80oc8cosskcsgc4.172.245.228.126.sslip.io,vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io
```

---

## 📋 Checklist

- [ ] Backend tiene todas las variables de entorno
- [ ] Frontend tiene `NUXT_PUBLIC_API_BASE` configurada
- [ ] Base de datos está corriendo en Coolify
- [ ] Backend desplegado exitosamente
- [ ] Frontend desplegado exitosamente
- [ ] Test de registro funciona
- [ ] Usuario se crea en la BD
