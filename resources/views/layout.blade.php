<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        @if (Auth::check())
          <a class="navbar-brand" href="/upcoming">FootyCal</a>
        @else
          <a class="navbar-brand" href="/login">FootyCal</a>
        @endif

      </div>
      <ul class="nav navbar-nav">
        @if (Auth::check())
          <li {{strpos(Request::url(), 'upcoming') ? "class=active" : ""}}><a href="/upcoming">Upcoming Matches</a></li>
          <li {{strpos(Request::url(), 'profile') ? "class=active" : ""}}><a href="/profile">profile</a></li>
          <li ><a href="/logout">logout</a></li>
        @else
          <li {{strpos(Request::url(), 'login') ? "class=active" : ""}}><a href="/login">login</a></li>
          <li {{strpos(Request::url(), 'signup') ? "class=active" : ""}}><a href="/signup">signup</a></li>
        @endif

      </ul>
    </div>
  </nav>
  <div class="container .col-md-10 col-md-offset-1">
    @yield('main')
  </div>
</body>
</html>
