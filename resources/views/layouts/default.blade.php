<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
</head>
<body class="season_fall">
  <button class="openBtn"><i class="fa fa-bars" aria-hidden="true"></i></button>
    <section id="side-nav">
        <div class="side-nav-content">
          <button><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            <ul>
                <li>
                    <a href="/home">Home</a>
                </li>
                <li>
                    <a href="league/create">Create League</a>
                </li>
                <li>
                    <a href="">Draft Board</a>
                </li>
                <li>
                    <a href="">Squads</a>
                </li>
                <li>
                    <a href="">Draft Room</a>
                </li>
                <li>
                    <a href="">Settings</a>
                </li>
                <li>
                    <a href="">Contact & Feedback </a>
                </li>
                <li>
                    <a href="/login">Login</a>
                </li>
                <li>
                    <a href="/register">Register</a>
                </li>
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
  <div class="copy-right text-center">
    <p style="color: #fff;">Copyrigth @ 2021 Website Name. All rights reserved</p>
  </div>
    @include('includes.scripts')
    @yield('js')
</body>
</html>