@extends('admin.layout.main')
@section('title', $header['title'])
  
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('rolePermission.dashboard') </a></li>
                <li class="breadcrumb-item active">{{$header['heading']}}</li>
              </ol>
              <div class="breadcrumb-btn">
              </div>
            </div><!-- /.col -->

          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">
            <div class="card pb-3 pt-3 px-3 w-100">
              <div class="discount">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                           <?php echo session('success'); ?>
                        </div>
                    @endif  
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (isset($error))
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endif
                <div class="grid-list-head py-2">
                  <h3 class="mt-2 mb-0">Api Users List</h3>
                  <div class="gridlist-icons">
                    <?php if(isset($_GET['per_page'])  && $_GET['per_page'] !== ""){
                      ?>
                    <div>
                      <h6>Showing {{ $apiUserData->firstItem() ? $apiUserData->firstItem() :'0' }} to {{ $apiUserData->lastItem() ? $apiUserData->lastItem() :'0' }}
                          of total {!! $apiUserData->total() !!} entries (filtered from {{ $apiUserCount }} total entries) </h6>
                    </div>
                    <?php } else {?> 
                    <div>
                       <h6>Showing {{ $apiUserData->firstItem() ? $apiUserData->firstItem() :'0' }} to {{ $apiUserData->lastItem() ? $apiUserData->lastItem() :'0' }}
                          of total {!! $apiUserData->total() !!} entries</h6>
                    </div>
                    <?php } ?>
                  </div>
                  <!-- collapse -->
                  <div class="collapse filter-collapse w-100" id="collapseExample">
                  <form autocomplete="off" id="filter" method="GET" action="{{route('api-users')}}">
                    <div class="card-filter card-body w-100 mt-3">
                      <div class="row">
                        <div class="col-md-10">
                          <div class="row">
                            <div class="col-md-3 filter-form">
                            </div>
                            <div class="col-md-3 filter-form">
                            </div>
                            <div class="col-md-3 filter-form">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2 filter-buttons">
                        <button type="submit" class="submit-filter filter-btm-btn" title="Apply">

                          <a href="" class="submit-filter">
                            <svg fill="#ffffff" width="17" height="17" viewBox="0 0 448 512">
                              <path
                                d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z" />
                            </svg>
                          </a>
                          </button>
                          <div class="refress-filter filter-btm-btn" title="Refresh">
                              <a href="{{route('role-permission.index')}}">
                                  <svg fill="#ffffff" width="17" height="17" viewBox="0 0 512 512">
                                  <path
                                      d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z" />
                                  </svg>
                              </a>
                          </div>
                        </div>
                        </div>
                      </div>
                    </form>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-12">
                    <div class="table-card">
                      <!-- /.card-header -->
                      <div class="card-body table-radius table-responsive p-0">
                        <table class="table table-head-fixed">
                          <thead class="td-data-color">
                            <tr>
                              <th class="no-data-list"> <span>@lang('rolePermission.srNo') </span></th>
                              <th>Type</th>
                              <th class="table-heading">User Name</th>
                              <th class="no-data-list">Created At</a></th>
                            </tr>
                          </thead>
                          <tbody class="td-data-color">
                            @foreach($apiUserData as $key=>$data)
                              <td class="no-data-list">
                                {{++$i}}
                              </td>
                              <td style="text-transform: lowercase !important;">
                                {{$data['type']}}
                              </td>
                              <td>
                                {{ $data['name'] }}
                              </td>
                              <td class="no-data-list">
                                {{getDateTimeZone($data['created_at'])}}
                              </td>
                              </tr>
                              @endforeach
                              @if($apiUserData)
                                @if($apiUserData->isEmpty())
                                <tr>
                                  <td colspan="12" style="text-align: center;">No Record Found</td>
                                </tr>
                                @endif
                              @endif
                          </tbody>
                        </table>
                      </div>
                      <nav class="pagination-grid" aria-label="Page  navigation example">
                        <ul class="pagination">
                            <?php if($apiUserData->hasPages()) { ?>
                          {!! $apiUserData->appends(Request::except('page'))->render() !!}
                            <?php } ?>
                        </ul>
                      </nav>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!--/. container-fluid -->
      </section>
  @endsection
  @section('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append
