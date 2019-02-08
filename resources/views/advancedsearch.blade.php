<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Beer Bank : Advanced Search</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>

    @include('inc.navbar')

    <div id="app">
      <adsearch></adsearch>

    </div>

    <!-- <footer class="text-center">
      <p>
        Copyright 2019 &copy; BeerBank
      </p>
    </footer> -->

    <script type="text/javascript" src="/js/app.js"></script>
  </body>
</html>
