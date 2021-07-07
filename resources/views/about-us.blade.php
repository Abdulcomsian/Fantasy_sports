@extends('layouts.default')
@section('title', 'Home')
@section('content')
<section id="banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="title">About Us</h2>
            </div>
        </div>
        <div class="story-div">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <h3>We are the best basketball team</h3>
                    <div class="underline"></div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                    <p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis risus eget urna mollis ornare vel eu leo. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit avmet risus</p>
                </div>
            </div>
        </div>
        <div class="multi-div about-div">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="contact-box">
                            <img src="images/003-basketball-player.png" alt="" class="img-fluid">
                            <h5>We pride ourselves on innovative.</h5>
                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live.</p>
                            <a href="">LEARN MORE</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="contact-box">
                            <img src="images/004-strategy.png" alt="" class="img-fluid">
                            <h5>We won many awards.</h5>
                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live.</p>
                            <a href="">LEARN MORE</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="contact-box">
                            <img src="images/download.png" alt="" class="img-fluid">
                            <h5>We are a team of genius people.</h5>
                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live.</p>
                            <a href="">LEARN MORE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="our-sponsors">
            <h2 class="text-center">Our Sponsors</h2>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 text-center">
                        <img src="images/logo-slam-1.png" alt="" class="img-fluid">
                    </div>
                    <div class="col-lg-3 col-md-3 text-center">
                        <img src="images/logo-slam-2.png" alt="" class="img-fluid">
                    </div>
                    <div class="col-lg-3 col-md-3 text-center">
                        <img src="images/logo-slam-3.png" alt="" class="img-fluid">
                    </div>
                    <div class="col-lg-3 col-md-3 text-center">
                        <img src="images/logo-slam-4.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection