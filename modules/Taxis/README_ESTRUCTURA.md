# Sistema de Taxis - Estructura Organizacional

## Resumen de la Implementación

Este documento describe la estructura organizacional implementada para el sistema de taxis, separando las funcionalidades entre **Conductores** y **Propietarios**.

## Estructura de Carpetas

```
modules/Taxis/
├── Http/
│   ├── Controllers/
│   │   ├── ConductorController.php       # Controlador para conductores
│   │   ├── PropietarioController.php     # Controlador para propietarios
│   │   └── TaxisController.php          # Controlador base
│   ├── Middleware/
│   │   ├── TaxisConductorMiddleware.php  # Middleware para conductores
│   │   └── TaxisPropietarioMiddleware.php # Middleware para propietarios
│   └── Resources/
└── Resources/
    └── views/
        ├── conductor/                    # Vistas para conductores
        │   ├── dashboard.blade.php
        │   ├── permisos/
        │   │   └── index.blade.php
        │   ├── pagos/
        │   │   └── index.blade.php
        │   ├── servicios/
        │   └── perfil/
        ├── propietario/                  # Vistas para propietarios
        │   ├── dashboard.blade.php
        │   ├── vehiculos/
        │   │   ├── index.blade.php
        │   │   └── show.blade.php
        │   ├── conductores/
        │   │   └── index.blade.php
        │   ├── contratos/
        │   │   └── index.blade.php
        │   ├── solicitudes/
        │   ├── constancias/
        │   ├── pagos/
        │   ├── permisos/
        │   ├── servicios/
        │   └── perfil/
        └── auth/
            └── login.blade.php
```

## Funcionalidades por Tipo de Usuario

### CONDUCTORES

El conductor puede acceder a:

1. **Dashboard del Conductor**

    - Estadísticas básicas
    - Información del vehículo asignado
    - Accesos rápidos

2. **Mi Vehículo**

    - Ver detalles del vehículo asignado
    - Información del propietario

3. **Mis Permisos**

    - Lista de permisos del vehículo
    - Estado de vigencia
    - Fechas de vencimiento

4. **Mis Pagos**

    - Historial de pagos del vehículo
    - Estado de pagos
    - Montos y fechas

5. **Mis Servicios**

    - Servicios programados
    - Historial de servicios

6. **Mi Perfil**
    - Actualizar información personal
    - Datos de licencia
    - Contacto

### PROPIETARIOS

El propietario puede acceder a:

1. **Dashboard del Propietario**

    - Estadísticas completas
    - Resumen de vehículos
    - Accesos rápidos

2. **Mis Vehículos**

    - Lista de todos los vehículos
    - Detalles individuales
    - Estados y conductores asignados

3. **Mis Conductores**

    - Lista de conductores asociados
    - Vehículos por conductor
    - Información de contacto

4. **Mis Contratos**

    - Todos los contratos activos
    - Historial de contratos
    - Estados y vencimientos

5. **Mis Solicitudes**

    - Solicitudes pendientes
    - Historial de solicitudes
    - Estados de aprobación

6. **Mis Constancias**

    - Constancias de baja
    - Documentos relacionados

7. **Mis Pagos**

    - Pagos de todos los vehículos
    - Consolidado de pagos
    - Estados financieros

8. **Mis Permisos**

    - Permisos de todos los vehículos
    - Vigencias y vencimientos

9. **Mis Servicios**

    - Servicios de toda la flota
    - Programación y mantenimiento

10. **Mi Perfil**
    - Información personal
    - Datos de contacto

## Rutas Implementadas

### Rutas del Conductor

```
/taxis/conductor/dashboard          # Dashboard principal
/taxis/conductor/vehiculo           # Mi vehículo
/taxis/conductor/permisos           # Mis permisos
/taxis/conductor/pagos              # Mis pagos
/taxis/conductor/servicios          # Mis servicios
/taxis/conductor/perfil             # Mi perfil
```

### Rutas del Propietario

```
/taxis/propietario/dashboard        # Dashboard principal
/taxis/propietario/vehiculos        # Mis vehículos
/taxis/propietario/conductores      # Mis conductores
/taxis/propietario/contratos        # Mis contratos
/taxis/propietario/solicitudes      # Mis solicitudes
/taxis/propietario/constancias      # Mis constancias
/taxis/propietario/pagos            # Mis pagos
/taxis/propietario/permisos         # Mis permisos
/taxis/propietario/servicios        # Mis servicios
/taxis/propietario/perfil           # Mi perfil
```

## Sistema de Autenticación

### Middlewares

-   `taxis.conductor`: Verifica que el usuario sea un conductor autenticado
-   `taxis.propietario`: Verifica que el usuario sea un propietario autenticado

### Sesión

El sistema utiliza sesiones de Laravel para mantener la información del usuario:

```php
session([
    'taxis_authenticated' => true,
    'taxis_user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'type' => 'conductor|propietario',
        // ... otros datos del usuario
    ]
]);
```

## Controladores API

Cada controlador incluye métodos API para obtener datos:

-   `*Records()`: Métodos para obtener datos paginados
-   Filtros específicos por usuario (propietario_id, conductor_id)
-   Respuestas en formato JSON usando Resources de Laravel

## Componentes Vue (Pendientes)

Se deben crear componentes Vue.js específicos para cada sección:

-   `tenant-taxis-conductor-permisos`
-   `tenant-taxis-conductor-pagos`
-   `tenant-taxis-propietario-vehiculos`
-   `tenant-taxis-propietario-contratos`
-   etc.

## Próximos Pasos

1. Crear los componentes Vue.js correspondientes
2. Implementar las vistas faltantes (servicios, perfil, etc.)
3. Agregar validaciones específicas por tipo de usuario
4. Implementar notificaciones push
5. Añadir reportes y estadísticas avanzadas
6. Implementar sistema de mensajería entre propietarios y conductores

## Configuración Requerida

Para que el sistema funcione correctamente:

1. **Modelos de Base de Datos**: Asegurarse de que existan los modelos:

    - `App\Models\Tenant\Taxis\Conductores`
    - `App\Models\Tenant\Taxis\Propietarios`
    - `App\Models\Tenant\Taxis\Vehiculos`
    - Otros modelos relacionados

2. **Relaciones**: Configurar las relaciones entre modelos:

    - Conductor -> Vehículos
    - Propietario -> Vehículos
    - Vehículo -> Permisos, Pagos, etc.

3. **Middleware**: Los middlewares están registrados automáticamente
4. **Rutas**: Las rutas están organizadas por tipo de usuario
5. **Vistas**: Las vistas siguen la estructura modular de Laravel
