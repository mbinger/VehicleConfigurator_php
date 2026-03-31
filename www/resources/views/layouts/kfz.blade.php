<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @livewireStyles

    {{ $head ?? '' }}
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
  <a class="navbar-brand" href="{{route('home')}}">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{route('kfz.order.create')}}">Create order</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('kfz.order.search')}}">Search order</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('kfz.customer.search')}}">Search customer</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('/admin')}}">Admin</a>
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
