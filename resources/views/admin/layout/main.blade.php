  @include('admin.layout.section.header')

  <!-- Main Sidebar Container -->
  @include('admin.layout.section.sidebar')



  <!-- {{-- @component('admin.components.messages') @endcomponent --}} -->

  <div class="content-wrapper">
    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
  @include('admin.layout.section.footer')