@include('sections.mouse')
@include('sections.preloader')
@include('sections.header')

  <main id="main" class="main">
    @yield('page-content')
  </main>

  @hasSection('sidebar')
    <aside class="sidebar">
      @yield('sidebar')
    </aside>
  @endif

@include('sections.footer')

@stack('post-app-script')