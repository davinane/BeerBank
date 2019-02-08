<nav class="navbar sticky-top navbar-expand-md navbar-dark navbar-color navbar-padding">
        <div class="form-inline">
          <a class="top-links {{Request::is('/') ? 'active' : ''}}" href="/">HOME</a>
          <a class="top-links {{Request::is('favourite') ? 'active' : ''}}" href="/favourite">FAVOURITE</a>
        </div>
      </div>
    </nav>
