<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Error - @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Thanapat Pirmphol">
        @include('_layouts.styles')
        <link href="{{URL::asset('assets/css/error.css')}}" rel="stylesheet">
    </head>
    </head>

    <body>
        <div class="container">
            @yield('content')
        </div>
        
    </body>
</html>