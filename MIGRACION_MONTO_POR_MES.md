# Migración para Monto Por Mes y Descuento Por Mes

Para añadir los nuevos campos a la tabla `subscription_invoices`, ejecuta la siguiente migración:

```bash
php artisan migrate
```

Esta migración añade dos nuevos campos a la tabla:

-   `monto_por_mes`: Para almacenar el monto por mes (montoTotal / número de meses)
-   `descuento_por_mes`: Para almacenar el descuento por mes (descuentoTotal / número de meses)

Estos campos son utilizados en los pagos múltiples para tener un mejor control y reportes más detallados.
