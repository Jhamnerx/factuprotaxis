@extends('tenant.layouts.guest')


@section('contenido')

    <!-- contact section -->
    <section class="contact_section layout_padding-bottom">
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
    <!-- end contact section -->


    <!-- service section -->

    <section class="service_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    Nuestros <br>
                    Servicios de Taxi
                </h2>
            </div>
            <div class="service_container">
                @foreach ($web_page->services as $service)
                    <div class="box">
                        <div class="img-box">
                            <img src="{{ $service['image'] ? asset('storage/uploads/services/' . $service['image']) : asset('tenant/images/delivery-man.png') }}"
                                alt="">
                        </div>
                        <div class="detail-box">
                            <h5>
                                {{ $service['name'] }}
                            </h5>
                            <p>
                                {{ $service['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- end service section -->

    <!-- client section -->
    @if (count($web_page->client_says) > 0)


        <section class="client_section layout_padding-bottom">
            <div class="container">
                <div class="heading_container">
                    <h2>
                        Que <br>
                        Dicen los <br>
                        Clientes
                    </h2>
                </div>
                <div class="client_container">
                    <div class="carousel-wrap ">
                        <div class="owl-carousel">
                            @foreach ($web_page->client_says as $item)
                                <div class="item">
                                    <div class="box">
                                        <div class="img-box">
                                            <img src="{{ asset('tenant/images/client-1.png') }}" alt="">
                                        </div>
                                        <div class="detail-box">
                                            <h3>
                                                {{ $item['name'] }}
                                            </h3>
                                            <p>
                                                {{ $item['text'] }}
                                            </p>
                                            <img src="{{ asset('tenant/images/quote.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- end client section -->
    <!-- why section -->

    <section class="why_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    Por que <br>
                    Elegirnos
                </h2>
            </div>
            <div class="why_container">
                <div class="box">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/delivery-man-white.png') }}" alt="" class="img-1">
                        <img src="{{ asset('tenant/images/delivery-man-black.png') }}" alt="" class="img-2">
                    </div>
                    <div class="detail-box">
                        <h5>
                            Mejores Conductores
                            {{ $web_page->why_choose[0]['title'] }}
                        </h5>
                        <p>
                            {{ $web_page->why_choose[0]['description'] }}
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/shield-white.png') }}" alt="" class="img-1">
                        <img src="{{ asset('tenant/images/shield-black.png') }}" alt="" class="img-2">
                    </div>
                    <div class="detail-box">
                        <h5>
                            {{ $web_page->why_choose[1]['title'] }}
                        </h5>
                        <p>
                            {{ $web_page->why_choose[1]['description'] }}
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/repairing-service-white.png') }}" alt="" class="img-1">
                        <img src="{{ asset('tenant/images/repairing-service-black.png') }}" alt="" class="img-2">
                    </div>
                    <div class="detail-box">
                        <h5>
                            {{ $web_page->why_choose[2]['title'] }}
                        </h5>
                        <p>
                            {{ $web_page->why_choose[2]['description'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end why section -->

@stop
