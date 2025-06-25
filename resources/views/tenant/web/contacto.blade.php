@extends('tenant.layouts.guest')


@section('contenido')
    <section class="contact_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    ¿Algún problema? </br>
                    ¿Alguna pregunta?
                </h2>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5  offset-md-1">
                    <div class="contact_form">
                        <h4>
                            Contáctanos
                        </h4>
                        <form action="">
                            <input type="text" placeholder="Nombre">
                            <input type="text" placeholder="Número de Teléfono">
                            <input type="text" placeholder="Mensaje" class="message_input">
                            <button>Enviar</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 px-0">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/contact-img.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
