<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @livewireStyles

    <script src="/js/jquery-4.0.0.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="/css/jquery-ui.css" />
    <link rel="stylesheet" href="/css/jquery-ui.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.structure.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.theme.min.css" />
@if(Lang::has("DatepickerLocale", App::getLocale(), false))
    <script src="/js/datepicker-{{App::getLocale()}}.js"></script>
@endif    
    <script src="/js/spectrum.min.js"></script>
    <link rel="stylesheet" href="/css/spectrum.min.css" />
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
  <a class="navbar-brand" href="{{route('home')}}">{{__("Home")}}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{route('kfz.order.create')}}">{{__("Create order")}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('kfz.order.search')}}">{{__("Search order")}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('kfz.customer.search')}}">{{__("Search customer")}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('/admin')}}">{{__("Admin")}}</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{url("/images/" . App::getLocale() . ".png")}}">
          </a>
          <ul class="dropdown-menu">
            @foreach(config('app.available_locales') as $name => $locale)
              <li><a class="dropdown-item" href="{{route('locale', ['locale' => $locale])}}"><img border="1" src="{{url("/images/{$locale}.png")}}"></a></li>
            @endforeach
          </ul>
        </li>        
      </ul>
    </div>
  </div>
</nav>

{{ $slot }}

<div id="load-indicator" class="content-blocker d-none">
<div class="d-flex justify-content-center align-items-center vh-100">
  <div class="spinner-border" role="status"></div>
  <div class="p-3">
    <strong>Loading ...</strong>
  </div>
</div>
</div>

@livewireScripts

{{ $script ?? '' }}
</body>
</html>
