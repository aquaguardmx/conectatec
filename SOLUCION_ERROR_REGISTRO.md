# Solución: Error "Failed to fetch" en Registro (Producción)

## Problemas Identificados y Corregidos

### 1. **Tabla `personal_access_tokens` no existe**
El error principal en los logs es: `SQLSTATE[42P01]: Undefined table: relation "personal_access_tokens" does not exist`

**Causa:** Las migraciones de Laravel Sanctum no se ejecutaron correctamente.

**Solución:** 
```bash
# Ejecuta en el contenedor Docker del backend
docker-compose exec backend php artisan migrate --force
```

### 2. **Configuración de CORS/Sanctum**
El dominio de producción no estaba en la lista de dominios permitidos.

**Cambios realizados:**
- ✅ Actualizado `config/sanctum.php` para usar variable de entorno
- ✅ Configurado `.env` con `SANCTUM_STATEFUL_DOMAINS=vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io`
- ✅ Habilitado middleware CORS en `bootstrap/app.php`

### 3. **Configuración de Entorno (.env)**
**Cambios realizados:**
- ✅ `APP_ENV=production`
- ✅ `APP_DEBUG=false`
- ✅ `APP_URL=http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io`
- ✅ `DB_HOST=db` (para Docker)
- ✅ `SANCTUM_STATEFUL_DOMAINS` configurado

### 4. **Script de Entrada Docker Mejorado**
El `docker-entrypoint.sh` ahora:
- Intenta conexión a la BD hasta 30 veces (antes 5)
- Ejecuta migraciones en bucle hasta que tenga éxito
- Proporciona mejor logging

## Pasos para Desplegar en Producción

### Paso 1: Reconstruir el contenedor
```bash
docker-compose up -d --build
```

### Paso 2: Ejecutar Migraciones (si es necesario)
```bash
docker-compose exec backend php artisan migrate --force
```

### Paso 3: Verificar que todo funcione
```bash
# Verifica los logs del backend
docker-compose logs -f backend

# Prueba el endpoint de registro
curl -X POST http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test User",
    "Matricula": "TEST001",
    "Correo_Institucional": "test@example.com",
    "contrasena": "password123",
    "Telefono": "1234567890",
    "Rol": "usuario"
  }'
```

## Archivos Modificados

1. **backend/.env** - Configuración de producción
2. **backend/config/sanctum.php** - Eliminada línea comentada
3. **backend/bootstrap/app.php** - Middleware CORS habilitado
4. **backend/docker-entrypoint.sh** - Lógica de reintentos mejorada

## Posibles Problemas y Soluciones

### Si persiste el error "Failed to fetch"
1. Verifica CORS en los headers de respuesta:
```bash
curl -vvv -X OPTIONS http://vgoc4gsscwk0o8okcg4ggg08.172.245.228.126.sslip.io/api/register
```

2. Revisa los logs del backend:
```bash
docker-compose logs backend | tail -100
```

3. Asegúrate que el dominio en `.env` coincida exactamente con el dominio del frontend

### Si la migración falla
1. Verifica que PostgreSQL está corriendo:
```bash
docker-compose ps
```

2. Verifica credenciales de BD:
```bash
docker-compose exec db psql -U postgres -d tienda_sistema -c "\dt"
```

## Cambios en Producción

Después de hacer deploy:
1. Reconstruir images: `docker-compose build`
2. Reiniciar servicios: `docker-compose up -d`
3. Ejecutar migraciones: `docker-compose exec backend php artisan migrate --force`
4. Limpiar cache: `docker-compose exec backend php artisan cache:clear`
