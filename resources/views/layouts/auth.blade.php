<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'KostKita')</title>
  <link rel="icon" href="{{ asset('img/favicon.ico') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-gray-100">
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl">
      @yield('content')
    </div>
  </div>
</body>

</html>