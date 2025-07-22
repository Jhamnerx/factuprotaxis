# Sistema de Notificaciones WhatsApp para Taxis

## Resumen de Funcionalidades Implementadas

### 1. Mensajes de Bienvenida Automáticos

-   ✅ **Propietarios**: Se envía automáticamente cuando se crea un nuevo propietario
-   ✅ **Conductores**: Se envía automáticamente cuando se crea un nuevo conductor
-   ✅ Incluye datos del vehículo si el conductor tiene uno asignado

### 2. Jobs de Notificaciones Programadas

#### 2.1 Cumpleaños (SendBirthdayMessagesJob)

-   ✅ Envía mensajes de feliz cumpleaños diariamente a las 08:00
-   ✅ Soporta: Propietarios, Conductores, Personal
-   ✅ Calcula automáticamente la edad
-   ✅ Plantillas personalizadas por tipo de usuario

#### 2.2 Vencimiento de Licencias (CheckDriverLicenseExpirationJob)

-   ✅ Verifica licencias de conductores próximas a vencer
-   ✅ Ejecuta diariamente a las 09:00
-   ✅ Configurable días de anticipación (por defecto 30 días)
-   ✅ Envía notificación al conductor

#### 2.3 Servicios Vehiculares (CheckVehicleServicesExpirationJob)

-   ✅ Verifica SOAT y Revisión Técnica próximos a vencer
-   ✅ Ejecuta diariamente a las 10:00
-   ✅ Envía a propietario del vehículo
-   ✅ También envía al teléfono configurado en el servicio
-   ✅ Marca servicios como vencidos automáticamente

### 3. Modelos Creados

#### 3.1 PlantillaMensaje

**Ubicación**: `app/Models/Tenant/PlantillaMensaje.php`
**Funcionalidades**:

-   Gestión de plantillas de mensajes por tipo
-   Reemplazo automático de variables `[variable]`
-   Scopes para filtrado por estado y tipo

#### 3.2 VehicleService

**Ubicación**: `app/Models/Tenant/VehicleService.php`
**Funcionalidades**:

-   Gestión de servicios vehiculares (SOAT, Revisión Técnica, etc.)
-   Cálculo automático de días hasta vencimiento
-   Scopes para próximos a vencer y vencidos
-   Relaciones con vehículos

### 4. Controladores Implementados

#### 4.1 PlantillaMensajeController

**Ubicación**: `app/Http/Controllers/Tenant/PlantillaMensajeController.php`
**Endpoints**:

-   CRUD completo de plantillas
-   Previsualización con variables de ejemplo
-   Obtener variables disponibles por tipo

#### 4.2 VehicleServiceController

**Ubicación**: `app/Http/Controllers/Tenant/VehicleServiceController.php`
**Endpoints**:

-   CRUD completo de servicios vehiculares
-   Dashboard con resúmenes
-   Envío manual de notificaciones

### 5. Jobs Implementados

#### 5.1 SendWelcomeMessageJob

**Ubicación**: `app/Jobs/Tenant/SendWelcomeMessageJob.php`
**Parámetros**:

-   `userType`: 'propietario', 'conductor', 'personal'
-   `userData`: Array con datos del usuario
-   `vehicleData`: Array con datos del vehículo (opcional)

#### 5.2 SendBirthdayMessagesJob

**Ubicación**: `app/Jobs/Tenant/SendBirthdayMessagesJob.php`
**Ejecución**: Diario a las 08:00
**Funcionalidad**: Busca cumpleaños del día y envía felicitaciones

#### 5.3 CheckDriverLicenseExpirationJob

**Ubicación**: `app/Jobs/Tenant/CheckDriverLicenseExpirationJob.php`
**Ejecución**: Diario a las 09:00
**Parámetros**: Días de anticipación (por defecto 30)

#### 5.4 CheckVehicleServicesExpirationJob

**Ubicación**: `app/Jobs/Tenant/CheckVehicleServicesExpirationJob.php`
**Ejecución**: Diario a las 10:00
**Parámetros**: Días de anticipación (por defecto 30)

### 6. Comandos de Consola

#### 6.1 SendBirthdayMessages

```bash
php artisan taxi:send-birthday-messages
```

#### 6.2 CheckDriverLicenseExpiration

```bash
php artisan taxi:check-driver-license-expiration --days=30
```

#### 6.3 CheckVehicleServicesExpiration

```bash
php artisan taxi:check-vehicle-services-expiration --days=30
```

### 7. Programación Automática (Cron)

En `app/Console/Kernel.php`:

```php
$schedule->command('taxi:send-birthday-messages')->dailyAt('08:00');
$schedule->command('taxi:check-driver-license-expiration')->dailyAt('09:00');
$schedule->command('taxi:check-vehicle-services-expiration')->dailyAt('10:00');
```

### 8. Rutas Web Implementadas

```php
// Plantillas de Mensajes WhatsApp
Route::get('plantillas-mensajes', 'Tenant\PlantillaMensajeController@index');
Route::get('plantillas-mensajes/columns', 'Tenant\PlantillaMensajeController@columns');
Route::get('plantillas-mensajes/records', 'Tenant\PlantillaMensajeController@records');
// ... más rutas CRUD

// Servicios de Vehículos
Route::get('vehicle-services', 'Tenant\VehicleServiceController@index');
Route::get('vehicle-services/columns', 'Tenant\VehicleServiceController@columns');
Route::get('vehicle-services/records', 'Tenant\VehicleServiceController@records');
// ... más rutas CRUD
```

### 9. Plantillas de Mensajes Predefinidas

La migración `2025_07_10_000001_create_plantilla_mensajes_table.php` incluye:

1. **bienvenida**: Mensaje de bienvenida con datos del vehículo
2. **cumpleanos_propietario**: Felicitación personalizada para propietarios
3. **cumpleanos_conductor**: Felicitación personalizada para conductores
4. **cumpleanos_personal**: Felicitación personalizada para personal
5. **vencimiento_licencia_conductor**: Recordatorio de vencimiento de licencia
6. **vencimiento_soat**: Recordatorio de vencimiento de SOAT
7. **vencimiento_revision_tecnica**: Recordatorio de revisión técnica

### 10. Variables Disponibles en Plantillas

#### Variables Globales:

-   `[nombre]`: Nombre de la persona
-   `[fecha]`: Fecha actual
-   `[hora]`: Hora actual

#### Variables Específicas:

-   `[celular]`: Teléfono de contacto
-   `[flota]`: Número interno del vehículo
-   `[placa]`: Placa del vehículo
-   `[edad]`: Edad calculada
-   `[fecha_vencimiento]`: Fecha de vencimiento
-   `[dias_restantes]`: Días hasta vencimiento
-   `[licencia]`: Número/clase de licencia

### 11. Integración con WhatsApp API

Utiliza el módulo `WhatsAppApi` implementado anteriormente:

-   Soporte para API oficial y no oficial
-   Failover automático
-   Limpieza automática de números de teléfono
-   Logging completo de envíos y errores

### 12. Base de Datos

#### Tabla `plantilla_mensajes`:

-   `tipo`: Tipo de plantilla
-   `asunto`: Asunto del mensaje
-   `contenido`: Contenido con variables
-   `descripcion`: Descripción de la plantilla
-   `estado`: Activa/Inactiva

#### Tabla `vehicle_services`:

-   `device_id`: ID del vehículo
-   `name`: Tipo de servicio (SOAT, REVISION_TECNICA, etc.)
-   `expires_date`: Fecha de vencimiento
-   `mobile_phone`: Teléfono adicional para notificaciones
-   `event_sent`: Si ya se envió notificación
-   `expired`: Si está vencido

### 13. Logging y Monitoreo

Todos los jobs incluyen logging detallado:

-   Mensajes enviados exitosamente
-   Errores de envío
-   Usuarios sin teléfono
-   Estadísticas de procesamiento

### 14. Configuración Requerida

1. **Ejecutar migración**:

```bash
php artisan migrate --path=database/migrations/tenant/2025_07_10_000001_create_plantilla_mensajes_table.php
```

2. **Configurar WhatsApp API** en tabla `companies`:

    - API Oficial: `ws_api_token`, `ws_api_phone_number_id`
    - API No Oficial: `ws_unofficial_api_key`, `ws_unofficial_sender`, `ws_unofficial_url`

3. **Configurar Queue Worker**:

```bash
php artisan queue:work
```

4. **Configurar Cron** (en servidor):

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 15. Uso Práctico

#### Crear Servicios Vehiculares:

1. Ir a la sección "Servicios de Vehículos"
2. Crear registros para SOAT y Revisión Técnica
3. Configurar fechas de vencimiento
4. Opcionalmente agregar teléfonos adicionales

#### Personalizar Plantillas:

1. Ir a "Plantillas de Mensajes"
2. Editar las plantillas existentes
3. Usar variables entre corchetes `[variable]`
4. Previsualizar antes de guardar

#### Monitorear Envíos:

-   Revisar logs en `storage/logs/`
-   Los jobs se ejecutan automáticamente
-   También se pueden ejecutar manualmente con los comandos

### 16. Extensibilidad

El sistema está diseñado para ser fácilmente extensible:

-   Agregar nuevos tipos de plantillas
-   Crear nuevos jobs de notificación
-   Añadir más variables a las plantillas
-   Integrar con otros servicios de mensajería

## Resultado Final

✅ **Sistema completamente funcional** que envía automáticamente:

-   Mensajes de bienvenida a nuevos propietarios y conductores
-   Felicitaciones de cumpleaños diarias
-   Recordatorios de vencimiento de licencias
-   Alertas de vencimiento de SOAT y revisión técnica

Todo integrado con el módulo WhatsApp API y con interfaces de administración completas.
