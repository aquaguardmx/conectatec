# Guía: Conectar Backend, Frontend y BD en Coolify

## Paso 1: Identificar tus Servicios en Coolify

En tu Coolify deberías tener:
1. **Backend** (Laravel) - URL pública
2. **Frontend** (Nuxt) - URL pública  
3. **Base de Datos** (PostgreSQL) - Host interno o externo

En la captura veo:
- `aquaguardmx/conectatec-main-kaoccoe0Bg0Bssocckq4w` → probablemente Backend
- `aquaguardmx/conectatec-main-rw4kckqpsosvs48scs4cs4c44` → probablemente Frontend
- `postgresql-database-a8cs4s849000ca4w8cocg8gw` → Base de Datos

## Paso 2: Configurar Variables de Entorno

Las variables deben estar en el **Environment** de cada servicio:

### Para el Backend (Laravel)
```
APP_ENV=production
APP_DEBUG=false
APP_URL=[URL_PUBLICA_BACKEND]
DB_HOST=[HOST_BD_EN_COOLIFY]
DB_PORT=5432
DB_DATABASE=tienda_sistema
DB_USERNAME=postgres
DB_PASSWORD=1307
SANCTUM_STATEFUL_DOMAINS=[DOMINIO_FRONTEND],[DOMINIO_BACKEND]
```

### Para el Frontend (Nuxt)
```
NUXT_PUBLIC_API_BASE=[URL_PUBLICA_BACKEND]
PORT=3000
```

## Paso 3: Dónde Configurar en Coolify

1. Ve a **Projects**
2. Haz clic en cada aplicación
3. Ve a **Environment** (o **Environment Variables**)
4. Agrega las variables
5. Haz **Deploy**

## Lo que Necesito de Ti

Para configurar correctamente, necesito saber:

| Pregunta | Tu Respuesta |
|----------|-------------|
| ¿Cuál es la URL pública del **Backend**? | Ej: `http://backend-xxxx.sslip.io` |
| ¿Cuál es la URL pública del **Frontend**? | Ej: `http://frontend-xxxx.sslip.io` |
| ¿Cuál es el **Host de la BD** en Coolify? | Generalmente es el nombre del contenedor |
| ¿Qué **Puerto** usa la BD? | Generalmente `5432` |

Comparte esto y te diré exactamente qué variables agregar en cada servicio.
