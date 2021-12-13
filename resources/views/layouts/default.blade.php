<!DOCTYPE html>
<html lang="en">

<head>
  @include('includes.head')
</head>
@php
$style="display:none";
if(request()->route()->getPrefix()=="/league")
{
$style="display:show";
}
@endphp

<body class="season_fall loginView">
  <div class="overlay"></div>
  <button class="openBtn"><i class="fa fa-bars" aria-hidden="true"></i></button>
  <section id="side-nav">
    <div class="side-nav-content">
      <button><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
      <ul>

        <li>
          <a href="/home">Draft Room</a>
        </li>
        <li>
          <a href="{{url('league/create')}}">Create League</a>
        </li>
        <!-- <li style="{{$style}}">
          <a href="{{ url('/league/'.request()->route('id').'/draft') }}">Draft Board</a>
        </li>
        <li style="{{$style}}">
          <a href="{{ url('/league/'.request()->route('id').'/squads') }}">Squads</a>
        </li> -->
        <!-- <li style="{{$style}}">
          <a href="#">Draft Room</a>
        </li> -->
        <li style="{{$style}}">
          <a href="{{ url('/league/'.request()->route('id').'/settings') }}">Account</a>
        </li>
        @if(Auth::check())
        <li>
          <a href="{{route('editprofile')}}">Account</a>
        </li>
        @endif
        <li>
          <a href="{{url('contact')}}">Contact & Feedback </a>
        </li>
        @guest
        <li>
          <a href="/login">Login</a>
        </li>
        @endguest
        <!-- <li>
                    <a href="/register">Register</a>
                </li> -->
        @auth
        <li>
          <a href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-in" aria-hidden="true"></i> Logout</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </li>
        @endauth
      </ul>
    </div>
  </section>
  <div>
    <div class="ajax-loader">
      <img src="{{ asset('images/ajax-loader.gif') }}" class="img-responsive" />
    </div>
    @yield('content')
  </div>
  <!-- <div class="list_sheet">
    @if(Auth::check())
    <div class="container-fluid">
      <ul class="list-unstyled list-inline mt-4">
        <li class="list-inline-item">
          <a href="{{ url('/home') }}">
            <div class="box">
              <img src="{{ asset('images/fresh.png') }}">
              <button>Home</button>
            </div>
          </a>
        </li>
        @if(in_array(Request::segment(3), ['order', 'rounds', 'draft', 'squads']))
        <li class="list-inline-item">
          <a href="{{ url('/league/'.Request::segment(2).'/settings') }}">
            <div class="box">
              <img src="{{ asset('images/user.png') }}">
              <button>League setting</button>
            </div>
          </a>
        </li>
        @endif
        @if(in_array(Request::segment(3), ['order', 'settings', 'rounds', 'draft', 'squads']))
          @if(!in_array(Request::segment(3), ['draft']))
            <li class="list-inline-item">
              <a href="{{ url('/league/'.Request::segment(2).'/draft') }}">
                <div class="box">
                  <img src="{{ asset('images/rooms.png') }}">
                  <button>Draft Room</button>
                </div>
              </a>
            </li>
          @endif
          @if(!in_array(Request::segment(3), ['squads']))
          <li class="list-inline-item">
            <a href="{{ url('/league/'.Request::segment(2).'/squads') }}">
              <div class="box">
                <img src="{{ asset('images/player.png') }}">
                <button>SQUADS</button>
              </div>
            </a>
          </li>
          @endif
        @endif
        <li class="list-inline-item">
          <div class="box">
            <img src="{{ asset('images/fresh.png') }}">
            <button>TRADES</button>
          </div>
        </li>
        @if(!in_array(Request::segment(3), ['order', 'rounds']))
        <li class="list-inline-item">
          <div class="box">
            <img src="{{ asset('images/sport.png') }}">
            <button>record book</button>
          </div>
        </li>
        @endif
      </ul>
    </div>
    @endif
  </div> -->
  <div class="copy-right text-center" style="position: fixed;bottom: 0px;text-align: center !important;left: 40%;">
    <p style="color: #fff;">Copyright @ 2021 Website Name. All rights reserved</p>
  </div>
  @include('includes.scripts')
  @yield('js')
  <script>
    $(document).ready(function() {
      if ($('#draftMode').is(':checked')) {
        $(".dropDownDiv").css("display", "block")
      } else {
        $(".dropDownDiv").css("display", "none")
      }
    });
    $(document).ready(function() {
      $('.draftPlayer').click(function() {
        $('.draftPlayer').select2('open');
      });
      // set time out 2 sec
      setTimeout(function() {
        $('.draftPlayer').trigger('click');
      }, 50);
    });
  </script>
</body>

</html>