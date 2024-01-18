  @include('supplier.layout.section.header')

  <!-- Main Sidebar Container -->
  @include('supplier.layout.section.sidebar')
  

  
  <!-- {{-- @component('admin.components.messages') @endcomponent --}} -->

  <div class="content-wrapper">
    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
    @include('supplier.layout.section.footer')