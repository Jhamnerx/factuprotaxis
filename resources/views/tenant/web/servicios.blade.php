@extends('tenant.layouts.guest')


@section('contenido') <section class="service_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    Nuestros <br>
                    Servicios de Taxi
                </h2>
            </div>
            <div class="row"> <!-- Cambiamos a un sistema de filas y columnas -->
                @foreach ($web_page->services as $service)
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4"> <!-- Columnas responsive -->
                        <div class="box h-100">
                            <!-- Añadimos h-100 para igualar alturas -->
                            <div class="img-box">
                                <img src="{{ $service['image'] ? asset('storage/uploads/services/' . $service['image']) : asset('tenant/images/delivery-man.png') }}"
                                    alt="{{ $service['name'] }}">
                            </div>
                            <div class="detail-box">
                                <h5>
                                    {{ $service['name'] }}
                                </h5>
                                <p>
                                    {{ $service['description'] }}
                                </p>
                                {{-- <a href="">
                                   Leer Mas
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@stop
