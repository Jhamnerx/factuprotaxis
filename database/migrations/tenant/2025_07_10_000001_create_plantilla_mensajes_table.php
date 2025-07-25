<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePlantillaMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('plantilla_mensajes')) {
            Schema::create('plantilla_mensajes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('tipo', 50); // 'bienvenida', 'registro', 'cumpleanos', etc.
                $table->string('asunto', 255)->nullable();
                $table->text('contenido');
                $table->text('descripcion')->nullable();
                $table->boolean('estado')->default(true);
                $table->timestamps();
            });
        }

        // Verificar si ya existen datos antes de insertar
        if (DB::table('plantilla_mensajes')->count() == 0) {
            // Insertar plantillas predeterminadas
            DB::table('plantilla_mensajes')->insert(
                [
                    'tipo' => 'bienvenida',
                    'asunto' => 'Bienvenido a la familia San Pedro',
                    'contenido' => "Estimado [nombre]:

¡Bienvenido a la Familia San Pedro! Estamos encantados de tenerte con nosotros. 
flota: [flota]
Placa: [placa]
Celular: [celular]
Esperemos seguir trabajando y nuestra familia sea mucho mas grande 

Gracias por unirte a nuestra familia, y esperamos que juntos sigamos haciendo de Taxi San Pedro una opción confiable y excelente para todos.

¡Mucho éxito en esta nueva etapa!

Con aprecio,
Taxi San Pedro",
                    'descripcion' => 'Mensaje de bienvenida para nuevos conductores',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'registro',
                    'asunto' => 'Registro completado',
                    'contenido' => 'Estimado/a [nombre], su registro ha sido completado con éxito.',
                    'descripcion' => 'Mensaje de confirmación de registro',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'cumpleanos_propietario',
                    'asunto' => 'Feliz Cumpleaños',
                    'contenido' => "Estimado [nombre]:

En tu onomástico, la familia San Pedro te envía un cálido saludo y nuestras más sinceras felicitaciones. Agradecemos tu confianza y compromiso con nuestra empresa. ¡Te deseamos un día lleno de felicidad y éxito!

Con aprecio, tu equipo San Pedro",
                    'descripcion' => 'Mensaje de felicitación de cumpleaños para propietarios',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'cumpleanos_conductor',
                    'asunto' => 'Feliz Cumpleaños',
                    'contenido' => "Estimado [nombre]:

En tu onomástico, la familia San Pedro te envía un cálido saludo y nuestras más sinceras felicitaciones. Agradecemos tu dedicación y esfuerzo diario. ¡Te deseamos un día lleno de felicidad y éxito!

Con aprecio, tu equipo San Pedro",
                    'descripcion' => 'Mensaje de felicitación de cumpleaños para conductores',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'cumpleanos_personal',
                    'asunto' => 'Feliz Cumpleaños',
                    'contenido' => "Estimado [nombre]:

En tu onomástico, la familia San Pedro te envía un cálido saludo y nuestras más sinceras felicitaciones. Agradecemos tu gestión y dedicación con nuestros servicios. ¡Te deseamos un día lleno de felicidad y éxito!

Con aprecio, tu equipo San Pedro",
                    'descripcion' => 'Mensaje de felicitación de cumpleaños para personal',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'regalo_mes',
                    'asunto' => 'Regalo del mes',
                    'contenido' => 'Estimado/a [nombre], este mes tenemos un regalo especial para usted.',
                    'descripcion' => 'Mensaje para notificar sobre regalos mensuales',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'vencimiento_licencia_conductor',
                    'asunto' => 'Vencimiento de licencia',
                    'contenido' => 'Estimado/a conductor [nombre], le recordamos que su licencia vencerá el [fecha_vencimiento].',
                    'descripcion' => 'Mensaje de recordatorio de vencimiento de licencia para conductores',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'vencimiento_soat',
                    'asunto' => 'Vencimiento de SOAT',
                    'contenido' => 'Estimado/a [nombre], le recordamos que el SOAT de su vehículo [placa] vencerá el [fecha_vencimiento].',
                    'descripcion' => 'Mensaje de recordatorio de vencimiento de SOAT',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'vencimiento_afocat',
                    'asunto' => 'Vencimiento de AFOCAT',
                    'contenido' => 'Estimado/a [nombre], le recordamos que el AFOCAT de su vehículo [placa] vencerá el [fecha_vencimiento].',
                    'descripcion' => 'Mensaje de recordatorio de vencimiento de AFOCAT',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'vencimiento_revision_tecnica',
                    'asunto' => 'Vencimiento de revisión técnica',
                    'contenido' => 'Estimado/a [nombre], le recordamos que la revisión técnica de su vehículo [placa] vencerá el [fecha_vencimiento].',
                    'descripcion' => 'Mensaje de recordatorio de vencimiento de revisión técnica',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'tipo' => 'vencimiento_mantenimiento',
                    'asunto' => 'Vencimiento de mantenimiento',
                    'contenido' => 'Estimado/a [nombre], le recordamos que el mantenimiento de su vehículo [placa] vencerá el [fecha_vencimiento].',
                    'descripcion' => 'Mensaje de recordatorio de vencimiento de mantenimiento',
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plantilla_mensajes');
    }
}
