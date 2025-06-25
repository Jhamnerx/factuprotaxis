@extends('tenant.layouts.guest')

@section('contenido')
    <section class="about_section layout_padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-5 offset-lg-2 offset-md-1">
                    <div class="detail-box">
                        <h2>
                            Acerca de <br>
                            {{ $company->name }}
                        </h2>
                        <p>
                            {{ $web_page->about['text'] }}
                        </p>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="img-box">
                        <img src="{{ asset('tenant/images/about-img.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
