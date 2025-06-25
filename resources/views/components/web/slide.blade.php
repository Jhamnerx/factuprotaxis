<section class=" slider_section ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 ">
                <div class="box">
                    <div class="detail-box">
                        <h4>
                            Welcome to
                        </h4>
                        <h1>
                            TAXI
                        </h1>
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">

                                <div class="img-box">
                                    <img src="{{ asset('tenant/images/slider-img.png') }}" alt="">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="img-box">
                                    <img src="{{ asset('tenant/images/slider-img.png') }}" alt="">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="img-box">
                                    <img src="{{ asset('tenant/images/slider-img.png') }}" alt="">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="img-box">
                                    <img src="{{ asset('tenant/images/slider-img.png') }}" alt="">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="img-box">
                                    <img src="{{ asset('tenant/images/slider-img.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-box">
                        <a href="{{ route('tenant.web.servicios') }}" class="btn-1">
                            Servicios
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 ">
                <div class="slider_form">
                    <h4>
                        Get A Taxi Now
                    </h4>
                    <form action="">
                        <input type="text" placeholder="Car Type">
                        <input type="text" placeholder="Pick Up Location">
                        <input type="text" placeholder="Drop Location">
                        <div class="btm_input">
                            <input type="text" placeholder="Your Phone Number">
                            <button>Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>
