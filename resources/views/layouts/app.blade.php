@include('sections.mouse')
@include('sections.preloader')

@if(!isset($hide_header) || !$hide_header)
  @include('sections.header')
@endif

  <main id="main" @class([
      "main", 
      "hide-header" => isset($hide_header) && $hide_header, 
      "hide-footer" => isset($hide_footer) && $hide_footer
    ])>
    @yield('page-content')
  </main>

  @hasSection('sidebar')
    <aside class="sidebar">
      @yield('sidebar')
    </aside>
  @endif

@if(!isset($hide_footer) || !$hide_footer)
  @include('sections.footer')
@endif

@stack('post-app-script')