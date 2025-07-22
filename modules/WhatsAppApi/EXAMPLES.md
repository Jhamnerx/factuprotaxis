# Ejemplos de Uso - WhatsApp API Module

## Testing con Postman/Insomnia

### Headers requeridos

```
Authorization: Bearer {tu_token_api}
Content-Type: application/json
Accept: application/json
```

### URL Base

```
https://tu-dominio.com/api/whatsapp
```

## 1. Verificar Estado de Configuración

**GET** `/whatsapp/config-status`

**Respuesta esperada:**

```json
{
    "official_configured": true,
    "unofficial_configured": true,
    "media_available": true
}
```

## 2. Enviar Mensaje Simple (Servicio Unificado)

**POST** `/whatsapp/send-message`

```json
{
    "phone_number": "987654321",
    "message": "¡Hola! Este es un mensaje de prueba desde nuestro sistema.",
    "prefix_number": "51"
}
```

## 3. Enviar Imagen con Descripción

**POST** `/whatsapp/send-media`

```json
{
    "phone_number": "987654321",
    "media_type": "image",
    "url": "https://example.com/path/to/image.jpg",
    "caption": "Esta es una imagen de ejemplo",
    "prefix_number": "51"
}
```

## 4. Enviar Video

**POST** `/whatsapp/send-media`

```json
{
    "phone_number": "987654321",
    "media_type": "video",
    "url": "https://example.com/path/to/video.mp4",
    "caption": "Video promocional de nuestros servicios",
    "prefix_number": "51"
}
```

## 5. Enviar Documento PDF

**POST** `/whatsapp/send-media`

```json
{
    "phone_number": "987654321",
    "media_type": "pdf",
    "url": "https://example.com/documents/factura.pdf",
    "caption": "Su factura electrónica",
    "prefix_number": "51"
}
```

## 6. Enviar Nota de Voz

**POST** `/whatsapp/send-media`

```json
{
    "phone_number": "987654321",
    "media_type": "audio",
    "url": "https://example.com/audio/mensaje.mp3",
    "ppt": true,
    "prefix_number": "51"
}
```

## 7. Forzar API Específica

**POST** `/whatsapp/send-message-with-api`

```json
{
    "api_type": "unofficial",
    "phone_number": "987654321",
    "message": "Este mensaje se enviará específicamente con la API no oficial",
    "prefix_number": "51"
}
```

## 8. Usar API Oficial Directamente

**POST** `/whatsapp-cloud/send-message`

```json
{
    "send_type": "text",
    "phone_number": "987654321",
    "message": "Mensaje directo vía WhatsApp Cloud API",
    "prefix_number": "51"
}
```

## 9. Usar API No Oficial Directamente

**POST** `/whatsapp-unofficial/send-message`

```json
{
    "phone_number": "987654321",
    "message": "Mensaje directo vía API no oficial",
    "prefix_number": "51"
}
```

## Configuración de la Base de Datos

### Campos necesarios en la tabla `companies`:

```sql
-- API Oficial (WhatsApp Cloud API)
UPDATE companies SET
    ws_api_token = 'tu_token_facebook',
    ws_api_phone_number_id = 'id_numero_telefono'
WHERE id = 1;

-- API No Oficial (Zoftware Solutions)
UPDATE companies SET
    ws_unofficial_api_key = 'tu_api_key',
    ws_unofficial_sender = '62888xxxxx',
    ws_unofficial_url = 'https://messages.zoftwaresolutions.pro'
WHERE id = 1;
```

## Códigos de Respuesta

### Éxito (200)

```json
{
    "success": true,
    "message": "Mensaje enviado correctamente"
}
```

### Error de Validación (422)

```json
{
    "message": "Los datos proporcionados no son válidos.",
    "errors": {
        "phone_number": ["El campo número de teléfono es obligatorio."],
        "message": ["El campo mensaje es obligatorio."]
    }
}
```

### Error de Configuración (400)

```json
{
    "success": false,
    "message": "No hay APIs configuradas disponibles"
}
```

### Error del Servidor (500)

```json
{
    "success": false,
    "message": "Error interno del servidor"
}
```

## Integración en el Sistema

### Desde un Controlador

```php
use Modules\WhatsAppApi\Services\WhatsAppService;

class FacturaController extends Controller
{
    public function enviarFacturaPorWhatsApp($facturaId, $phoneNumber)
    {
        $whatsapp = new WhatsAppService();

        $factura = Factura::find($facturaId);
        $pdfUrl = route('factura.pdf', $facturaId);

        $result = $whatsapp->sendMedia([
            'phone_number' => $phoneNumber,
            'media_type' => 'pdf',
            'url' => $pdfUrl,
            'caption' => 'Su factura N° ' . $factura->numero,
            'prefix_number' => '51'
        ]);

        if ($result['success']) {
            Log::info('Factura enviada por WhatsApp', ['factura_id' => $facturaId]);
        }

        return $result;
    }
}
```

### Desde un Job/Queue

```php
use Modules\WhatsAppApi\Services\WhatsAppService;

class EnviarNotificacionWhatsApp implements ShouldQueue
{
    public function handle()
    {
        $whatsapp = new WhatsAppService();

        $whatsapp->sendMessage([
            'phone_number' => $this->phoneNumber,
            'message' => $this->message,
            'prefix_number' => '51'
        ]);
    }
}
```

## Testing Automatizado

### Test de Integración

```php
public function test_puede_enviar_mensaje_whatsapp()
{
    $response = $this->postJson('/api/whatsapp/send-message', [
        'phone_number' => '987654321',
        'message' => 'Mensaje de prueba',
        'prefix_number' => '51'
    ]);

    $response->assertStatus(200)
             ->assertJson([
                 'success' => true
             ]);
}
```
