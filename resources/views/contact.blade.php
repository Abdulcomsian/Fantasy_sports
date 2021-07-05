@extends('layouts.default')
@section('title', 'Home')
@section('content')
<section id="banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="title">Contact Us</h2>
                <span class="title-span">Get In Touch</span>
            </div>
        </div>
        <div class="multi-div">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="contact-box">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <h5>Phone</h5>
                        <p>A wonderful serenity has taken possession of my entire soul, like these.</p>
                        <a href="">+1-2345-2345</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="contact-box">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                        <h5>Email</h5>
                        <p>A wonderful serenity has taken possession of my entire soul, like these.</p>
                        <a href="">Contact@goodlayers.com</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="contact-box">
                        <i class="fa fa-location-arrow" aria-hidden="true"></i>
                        <h5>Location</h5>
                        <p>4 apt. Flawing Street. The Grand Avenue. Liverpool, UK 33342</p>
                        <a href="">View On Google Map</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="leave-your-message">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Leave us your info</h2>
                        <p>and we will get back to you.</p>
                        <div class="form-div">
                            <form action="">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="inputDiv">
                                            <input class="form-control" type="text" placeholder="Full Name*">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="inputDiv">
                                            <input class="form-control" type="text" placeholder="Email*">
                                        </div>
                                    </div>
                                </div>
                                <div class="inputDiv">
                                    <input class="form-control" type="text" placeholder="Email*">
                                </div>
                                <div class="inputDiv">
                                    <textarea name="" id="" cols="30" rows="5" class="form-control" placeholder="Message*"></textarea>
                                </div>
                                <button>Submit Now</button>
                            </form>
                            <div class="social-icon">
                                <a href=""><i class="fa fa-envelope"></i></a>
                                <a href=""><i class="fa fa-facebook"></i></a>
                                <a href=""><i class="fa fa-google-plus"></i></a>
                                <a href=""><i class="fa fa-linkedin"></i></a>
                                <a href=""><i class="fa fa-skype"></i></a>
                                <a href=""><i class="fa fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
       
    </div>
</section>
@endsection