# Sistema de Notificaciones de WhatsApp para Gestión de Taxis

## Resumen de Implementación

Este sistema proporciona notificaciones automáticas por WhatsApp para una plataforma de gestión de taxis, incluyendo mensajes de bienvenida, recordatorios de cumpleaños, alertas de vencimiento de licencias y servicios de vehículos.

## Características Implementadas

### 1. Mensajes de Bienvenida

-   **Disparador**: Automático al crear propietarios y conductores
-   **Integración**: PropietariosController y ConductoresController
-   **Plantilla**: `BIENVENIDA_PROPIETARIO` y `BIENVENIDA_CONDUCTOR`
-   **Variables**: `[nombre]`, `[empresa]`, `[fecha]`

### 2. Notificaciones de Cumpleaños

-   **Job**: `SendBirthdayMessagesJob`
-   **Horario**: Diario a las 08:00 AM
-   **Alcance**: Propietarios, conductores y personal
-   **Plantilla**: `FELIZ_CUMPLEANOS`
-   **Variables**: `[nombre]`, `[empresa]`, `[fecha]`

### 3. Alertas de Vencimiento de Licencias

-   **Job**: `CheckDriverLicenseExpirationJob`
-   **Horario**: Diario a las 09:00 AM
-   **Alcance**: Licencias de conductores próximas a vencer (30 días)
-   **Plantilla**: `VENCIMIENTO_LICENCIA`
-   **Variables**: `[nombre]`, `[numero_licencia]`, `[fecha_vencimiento]`, `[dias_restantes]`

### 4. Alertas de Servicios de Vehículos

-   **Job**: `CheckVehicleServicesExpirationJob`
-   **Horario**: Diario a las 10:00 AM
-   **Alcance**: SOAT y revisión técnica próximos a vencer
-   **Plantilla**: `VENCIMIENTO_SOAT` y `VENCIMIENTO_REVISION_TECNICA`
-   **Variables**: `[propietario]`, `[vehiculo_placa]`, `[servicio]`, `[fecha_vencimiento]`, `[dias_restantes]`

### 5. Gestión de Servicios de Vehículos

-   **Módulo**: Vehicle Services con interfaz completa
-   **Funcionalidades**: CRUD completo, notificaciones manuales, seguimiento de estados
-   **Campos**: Tipo de servicio, fechas, contactos, odómetro, descripciones

## Estructura de Archivos

### Modelos

-   `app/Models/PlantillaMensaje.php` - Gestión de plantillas
-   `app/Models/VehicleService.php` - Servicios de vehículos

### Jobs (Cron Jobs)

-   `app/Jobs/SendWelcomeMessageJob.php` - Mensajes de bienvenida
-   `app/Jobs/SendBirthdayMessagesJob.php` - Felicitaciones de cumpleaños
-   `app/Jobs/CheckDriverLicenseExpirationJob.php` - Vencimiento de licencias
-   `app/Jobs/CheckVehicleServicesExpirationJob.php` - Vencimiento de servicios

### Controladores

-   `app/Http/Controllers/PlantillaMensajeController.php` - CRUD de plantillas
-   `app/Http/Controllers/VehicleServiceController.php` - CRUD de servicios

### Comandos de Consola

-   `app/Console/Commands/SendBirthdayMessages.php`
-   `app/Console/Commands/CheckDriverLicenseExpiration.php`
-   `app/Console/Commands/CheckVehicleServicesExpiration.php`

### Migraciones de Base de Datos

-   `database/migrations/create_plantilla_mensajes_table.php`
-   `database/migrations/create_vehicle_services_table.php`

### Frontend

-   `resources/js/views/tenant/taxis/vehicle_services/VehicleServicesIndex.vue`
-   `resources/views/tenant/taxis/vehicle_services/index.blade.php`

### Configuración

-   Rutas en `routes/web.php`
-   Scheduler en `app/Console/Kernel.php`
-   Componente Vue registrado en `resources/js/app.js`
-   Menú de navegación en sidebar

## Configuración de Cron Jobs

```php
// En app/Console/Kernel.php
$schedule->job(new SendBirthdayMessagesJob())->dailyAt('08:00');
$schedule->job(new CheckDriverLicenseExpirationJob())->dailyAt('09:00');
$schedule->job(new CheckVehicleServicesExpirationJob())->dailyAt('10:00');
```

## Comandos de Ejecución Manual

```bash
# Enviar mensajes de cumpleaños
php artisan birthday:send

# Verificar vencimiento de licencias
php artisan license:check-expiration

# Verificar vencimiento de servicios de vehículos
php artisan vehicle-services:check-expiration
```

## Plantillas de Mensajes Predefinidas

### BIENVENIDA_PROPIETARIO

```
¡Bienvenido [nombre] a [empresa]!
Tu registro como propietario ha sido exitoso.
Fecha de registro: [fecha]
```

### BIENVENIDA_CONDUCTOR

```
¡Bienvenido [nombre] a [empresa]!
Tu registro como conductor ha sido exitoso.
Fecha de registro: [fecha]
```

### FELIZ_CUMPLEANOS

```
🎉 ¡Feliz cumpleaños [nombre]!
Todo el equipo de [empresa] te desea un día lleno de alegría.
Fecha: [fecha]
```

### VENCIMIENTO_LICENCIA

```
⚠️ Alerta: Tu licencia [numero_licencia] vence el [fecha_vencimiento].
Faltan [dias_restantes] días.
Por favor, renueva tu licencia a tiempo.
```

### VENCIMIENTO_SOAT / VENCIMIENTO_REVISION_TECNICA

```
⚠️ Alerta para [propietario]:
El [servicio] del vehículo [vehiculo_placa] vence el [fecha_vencimiento].
Faltan [dias_restantes] días.
```

## Integración con WhatsApp

El sistema utiliza tanto APIs oficiales como no oficiales de WhatsApp con failover automático:

1. Intenta envío por API oficial
2. En caso de fallo, utiliza API no oficial como respaldo
3. Registra todos los intentos y errores en logs

## Funcionalidades de la Interfaz Web

### Gestión de Plantillas

-   CRUD completo de plantillas de mensajes
-   Editor de variables disponibles
-   Activación/desactivación de plantillas

### Gestión de Servicios de Vehículos

-   Modal personalizado según diseño proporcionado
-   Selección de vehículos con información del propietario
-   Tipos de servicio predefinidos (SOAT, REVISION_TECNICA, etc.)
-   Campos de fecha, contacto y descripción
-   Estados visuales con badges de colores
-   Envío manual de notificaciones
-   Seguimiento de notificaciones enviadas

## Instalación y Configuración

1. **Ejecutar migraciones**:

```bash
php artisan migrate
```

2. **Configurar el cron del sistema**:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

3. **Crear plantillas iniciales** (opcional):

```bash
# Se pueden crear manualmente desde la interfaz web
# o mediante seeders personalizados
```

4. **Configurar APIs de WhatsApp**:
    - Configurar credenciales de WhatsApp oficial
    - Configurar respaldo de API no oficial
    - Verificar conectividad de ambos servicios

## Testing

Para probar el sistema:

```bash
# Verificar jobs programados
php artisan schedule:list

# Ejecutar manualmente un job específico
php artisan birthday:send
php artisan license:check-expiration
php artisan vehicle-services:check-expiration

# Revisar logs de ejecución
tail -f storage/logs/laravel.log
```

## Consideraciones de Seguridad

-   Todas las notificaciones incluyen validación de números de teléfono
-   Sistema de rate limiting para evitar spam
-   Logs detallados de todos los envíos
-   Respaldo automático entre APIs oficial/no oficial
-   Validación de plantillas y variables antes del envío

## Mantenimiento

-   Revisar logs regularmente para detectar fallos
-   Actualizar plantillas según necesidades del negocio
-   Monitorear tasa de entrega de mensajes
-   Verificar funcionamiento de cron jobs diarios
-   Mantener actualizada la información de contacto

---

**Nota**: Este sistema está completamente integrado con el módulo de taxis existente y respeta la arquitectura multi-tenant de la aplicación.
