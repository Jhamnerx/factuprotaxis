# Migración para Pagos Múltiples Consolidados

Esta migración añade nuevos campos a la tabla `subscription_invoices` que permiten almacenar pagos múltiples en un solo documento.

## Nuevos Campos

-   `es_pago_multiple`: Indica si este registro representa un pago de múltiples meses
-   `grupo_pago_id`: Identificador único para agrupar los pagos relacionados
-   `cantidad_meses`: Número de meses incluidos en este pago
-   `payment_details`: Detalles JSON de cada mes pagado con sus montos individuales

## Cómo Migrar

Ejecuta la siguiente migración:

```bash
php artisan migrate
```

## Beneficios

-   **Facturación unificada**: Solo se genera un documento para múltiples pagos
-   **Mejor organización**: Los pagos relacionados se agrupan bajo un identificador común
-   **Reportes simplificados**: Es más fácil identificar pagos consolidados vs. individuales
-   **Experiencia del usuario**: Los usuarios pueden ver todos los meses pagados en un solo recibo

## Cómo Funciona

1. Cuando se seleccionan múltiples meses para pago, el sistema ahora crea un solo registro en `subscription_invoices`
2. En el campo `data` se almacenan todos los meses pagados (como antes)
3. En el campo `payment_details` se almacena información detallada de cada mes, incluyendo:

    - Mes y año
    - Monto por mes
    - Descuento por mes
    - Fecha de pago

4. Los campos `monto` y `descuento` contienen el total global, mientras que `monto_por_mes` y `descuento_por_mes` contienen el promedio por mes
5. Los colores ya no se almacenan en la tabla `subscription_invoices`, sino a través de la relación polimórfica `paymentColors` del vehículo

## Gestión de Colores

-   Los colores se registran en la tabla `payment_colors` a través de la relación polimórfica `paymentColors` del modelo `Vehiculos`
-   Cada color está asociado a un vehículo específico, un año y un mes
-   Al realizar pagos múltiples, se crean registros de color individuales para cada mes pagado

## Funciones Adicionales

-   Se agregó un nuevo método `getMesesPagadosFormateados()` al modelo que devuelve una lista de meses con nombres legibles
-   La respuesta JSON incluye ahora estos meses formateados para facilitar su uso en la interfaz
-   Se mantiene la compatibilidad con la visualización de colores en el calendario para cada mes
