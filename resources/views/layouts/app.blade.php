<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Beer Bank</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>

    @include('inc.navbar')
    <div class="container container-padding">

        <div class="row">
            <div class="col-md-12 col-lg-12">
              @yield('content')
            </div>
        </div>
    </div>

    <footer class="text-center">
      <p>
        Copyright 2019 &copy; BeerBank
      </p>
    </footer>

    <script type="text/javascript" src="/js/app.js"></script>
  </body>
</html>
