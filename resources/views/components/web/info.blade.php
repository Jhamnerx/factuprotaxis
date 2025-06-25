<section class="info_section layout_padding-top layout_padding2-bottom">
    <div class="container">
        <div class="box">

            <div class="info_links">
                <ul>
                    <li class=" ">
                        <a class="" href="{{ route('tenant.web.home') }}">Home <span
                                class="sr-only">(current)</span></a>
                    </li>
                    <li class="">
                        <a class="" href="{{ route('tenant.web.nosotros') }}"> Nosotros</a>
                    </li>
                    <li class="">
                        <a class="" href="{{ route('tenant.web.servicios') }}"> Servicios </a>
                    </li>

                    <li class="">
                        <a class="" href="{{ route('tenant.web.contacto') }}">Contacto</a>
                    </li>
                    <li class="">
                        <a class="" href="{{ route('login') }}">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
            @if ($login->show_socials)
                <div class="info_social">
                    @if ($login->facebook)
                        <div>
                            <a href="{{ $login->facebook }}">
                                <img src="{{ asset('tenant/images/fb.png') }}" alt="">
                            </a>
                        </div>
                    @endif
                    @if ($login->twitter)
                        <div>
                            <a href="{{ $login->twitter }}">
                                <img src="{{ asset('tenant/images/twitter.png') }}" alt="">
                            </a>
                        </div>
                    @endif
                    @if ($login->linkedin)
                        <div>
                            <a href="{{ $login->linkedin }}">
                                <img src="{{ asset('tenant/images/linkedin.png') }}" alt="">
                            </a>
                        </div>
                    @endif
                    @if ($login->instagram)
                        <div>
                            <a href="{{ $login->instagram }}">
                                <img src="{{ asset('tenant/images/instagram.png') }}" alt="">
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

</section>
