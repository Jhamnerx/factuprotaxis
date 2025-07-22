# WhatsApp API Module - Documentación

Este módulo proporciona integración con APIs de WhatsApp tanto oficiales como no oficiales para el envío de mensajes y multimedia.

## Configuración

### API Oficial (WhatsApp Cloud API)

Configurar en la tabla `companies`:

-   `ws_api_token`: Token de acceso de Facebook
-   `ws_api_phone_number_id`: ID del número de teléfono

### API No Oficial (Zoftware Solutions)

Configurar en la tabla `companies`:

-   `ws_unofficial_api_key`: Clave API del servicio
-   `ws_unofficial_sender`: Número remitente (ej: 62888xxxx)
-   `ws_unofficial_url`: URL del servicio (por defecto: https://messages.zoftwaresolutions.pro)

## Endpoints Disponibles

### 1. Servicio Unificado (Recomendado)

#### Enviar Mensaje

**POST** `/api/whatsapp/send-message`

```json
{
    "phone_number": "987654321",
    "message": "Hola, este es un mensaje de prueba",
    "prefix_number": "51"
}
```

#### Enviar Multimedia

**POST** `/api/whatsapp/send-media`

```json
{
    "phone_number": "987654321",
    "media_type": "image",
    "url": "https://example.com/image.jpg",
    "caption": "Descripción de la imagen",
    "prefix_number": "51"
}
```

#### Enviar con API Específica

**POST** `/api/whatsapp/send-message-with-api`

```json
{
    "api_type": "unofficial",
    "phone_number": "987654321",
    "message": "Mensaje usando API específica",
    "prefix_number": "51"
}
```

#### Estado de Configuración

**GET** `/api/whatsapp/config-status`

Respuesta:

```json
{
    "official_configured": true,
    "unofficial_configured": true,
    "media_available": true
}
```

### 2. API Oficial Directa

#### Enviar Mensaje

**POST** `/api/whatsapp-cloud/send-message`

```json
{
    "send_type": "text",
    "phone_number": "987654321",
    "message": "Mensaje vía API oficial",
    "prefix_number": "51"
}
```

### 3. API No Oficial Directa

#### Enviar Mensaje

**POST** `/api/whatsapp-unofficial/send-message`

```json
{
    "phone_number": "987654321",
    "message": "Mensaje vía API no oficial",
    "prefix_number": "51"
}
```

#### Enviar Multimedia

**POST** `/api/whatsapp-unofficial/send-media`

```json
{
    "phone_number": "987654321",
    "media_type": "image",
    "url": "https://example.com/image.jpg",
    "caption": "Descripción opcional",
    "prefix_number": "51"
}
```

## Tipos de Multimedia Soportados

La API no oficial soporta los siguientes tipos de archivo:

-   `image`: Imágenes
-   `video`: Videos
-   `audio`: Archivos de audio
-   `pdf`: Documentos PDF
-   `xls`, `xlsx`: Hojas de cálculo Excel
-   `doc`, `docx`: Documentos Word
-   `zip`: Archivos comprimidos

## Parámetros Especiales

### Para Archivos de Audio

```json
{
    "media_type": "audio",
    "ppt": true
}
```

-   `ppt: true` = Nota de voz
-   `ppt: false` = Archivo de audio

### Para Imágenes y Videos

```json
{
    "media_type": "image",
    "caption": "Texto descriptivo"
}
```

## Respuestas

### Éxito

```json
{
    "success": true,
    "message": "Mensaje enviado correctamente"
}
```

### Error

```json
{
    "success": false,
    "message": "Descripción del error"
}
```

## Funcionalidades del Servicio Unificado

1. **Failover Automático**: Si la API no oficial falla, automáticamente intenta con la oficial
2. **Detección de Configuración**: Verifica qué APIs están configuradas
3. **Envío Inteligente**: Usa la mejor API disponible según el tipo de mensaje
4. **Multimedia**: Solo disponible a través de la API no oficial

## Migración de Base de Datos

Ejecutar la migración para agregar los nuevos campos:

```bash
php artisan migrate --path=database/migrations/tenant/2025_07_21_000001_add_whatsapp_unofficial_fields_to_companies_table.php
```

## Uso en Código PHP

```php
use Modules\WhatsAppApi\Services\WhatsAppService;

$whatsapp = new WhatsAppService();

// Enviar mensaje simple
$result = $whatsapp->sendMessage([
    'phone_number' => '987654321',
    'message' => 'Hola mundo',
    'prefix_number' => '51'
]);

// Enviar multimedia
$result = $whatsapp->sendMedia([
    'phone_number' => '987654321',
    'media_type' => 'image',
    'url' => 'https://example.com/image.jpg',
    'caption' => 'Mi imagen'
]);

// Verificar configuración
$status = $whatsapp->getConfigStatus();
```
