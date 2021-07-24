@extends('layouts.default')
@section('title', 'Home')
@section('content')
<section id="banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="title text-center">Contact Us</h2>
                <!-- <span class="title-span text-center">We want your ideas and feedback on how we can improve! Please dont hesitate to reach out!</span> -->
            </div>
        </div>
        <div class="leave-your-message" style="margin-top:25px !important;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- <h2>Leave us your info</h2>
                        <p>and we will get back to you.</p> -->
                        <div class="form-div">
                            <form action="">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="inputDiv">
                                            <input class="form-control" type="text" required placeholder="Full Name*">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="inputDiv">
                                            <input class="form-control" required type="text" placeholder="Email*">
                                        </div>
                                    </div>
                                </div>
                                <div class="inputDiv">
                                    <input class="form-control" type="text"  required placeholder="Subject*">
                                </div>
                                <div class="inputDiv">
                                    <textarea name="" id="" cols="30" rows="5" placeholder="We want your ideas and feedback on how we can improve! Please dont hesitate to reach out!" required class="form-control" required placeholder="Message*"></textarea>
                                </div>
                                <button>Submit Now</button>
                            </form>
                            <!-- <div class="social-icon">
                                <a href=""><i class="fa fa-envelope"></i></a>
                                <a href=""><i class="fa fa-facebook"></i></a>
                                <a href=""><i class="fa fa-google-plus"></i></a>
                                <a href=""><i class="fa fa-linkedin"></i></a>
                                <a href=""><i class="fa fa-skype"></i></a>
                                <a href=""><i class="fa fa-twitter"></i></a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
       
    </div>
</section>
@endsection