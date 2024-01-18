@extends('admin.layout.main')
@section('title', $header['title'])


@section('content')

      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">Mail Templates</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard')</a></li>
                <li class="breadcrumb-item active">Mail Templates</li>
              </ol>
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
                  <h3>Mail Template List</h3> 
                  <div class="gridlist-icons">
                    <?php if(isset($_GET['per_page'])  && $_GET['per_page'] !== ""){
                      ?>
                    <div>
                      <h6>Showing {{ $mailTemplateData->firstItem() ? $mailTemplateData->firstItem() :'0' }} to {{ $mailTemplateData->lastItem() ? $mailTemplateData->lastItem() :'0' }}
                          of total {!! $mailTemplateData->total() !!} entries (filtered from {{ $mailTemplateCountData }} total entries) </h6>
                    </div>
                    <?php } else {?> 
                    <div>
                       <h6>Showing {{ $mailTemplateData->firstItem() ? $mailTemplateData->firstItem() :'0' }} to {{ $mailTemplateData->lastItem() ? $mailTemplateData->lastItem() :'0' }}
                          of total {!! $mailTemplateData->total() !!} entries</h6>
                    </div>
                    <?php } ?>

                    <div class="filter grid-icon-top" title="Filter">
                      <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                        aria-controls="collapseExample">
                        <svg fill="#fff" width="26px" height="26px" viewBox="0 0 24 24" version="1"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M19 6h-14c-1.1 0-1.4.6-.6 1.4l4.2 4.2c.8.8 1.4 2.3 1.4 3.4v5l4-2v-3.5c0-.8.6-2.1 1.4-2.9l4.2-4.2c.8-.8.5-1.4-.6-1.4z" />
                          </svg>
                      </a>
                    </div>
                  </div>
                  <!-- collapse -->
                  <div class="collapse filter-collapse w-100" id="collapseExample">
                  <form autocomplete="off" id="filter" method="GET" action="{{route('mail-template.index')}}">
                    <div class="card-filter card-body w-100 mt-3">
                      <div class="row">
                        <div class="col-md-10">
                          <div class="row">
                            <div class="col-md-3 filter-form mb-3">
                              <div class="form-floating">
                                <div class="form-item form-float-style">
                                  <input type="text" id="name" name="name" autocomplete="off" value="{{@$_GET['name']}}" class="is-valid">
                                  <label for="sort">Name</label>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3 filter-form">
                              <div class="form-floating form-item mb-0">
                                  <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                      <select data-live-search="true" name="per_page" class="order-td-input selectpicker select-text height_drp is-valid">
                                        <option value="" selected disabled>Select Per Page</option>
                                        <option @if($appliedFilter['per_page'] == 5) selected="selected" @endif value="5">5</option>
                                        <option @if($appliedFilter['per_page'] == 10) selected="selected" @endif value="10">10</option>                                    
                                        <option @if($appliedFilter['per_page'] == 15) selected="selected" @endif value="15">15</option>                                    
                                        <option @if($appliedFilter['per_page'] == 20) selected="selected" @endif value="20">20</option>                                    
                                        <option @if($appliedFilter['per_page'] == 25) selected="selected" @endif value="25">25</option>                                    
                                        <option @if($appliedFilter['per_page'] == 50) selected="selected" @endif value="50">50</option>                                    
                                        <option @if($appliedFilter['per_page'] == 100) selected="selected" @endif value="100">100</option>
                                      </select>
                                      <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Per Page Data</label>
                                    </div>                        
                                  </div>
                              </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-2 filter-buttons">
                          <button type="submit" class="submit-filter filter-btm-btn" title="Apply">
                              <a href="">
                                  <svg fill="#ffffff" width="17" height="17" viewBox="0 0 448 512">
                                  <path
                                      d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z" />
                                  </svg>
                              </a>
                          </button>
                          <div class="refress-filter filter-btm-btn" title="Refresh">
                              <a href="{{route('mail-template.index')}}">
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
                <div class="row mt-3">
                  <div class="col-12">
                    <div class="table-card">
                      <!-- /.card-header -->
                      <div class="card-body table-radius table-responsive p-0">
                        <table class="table table-head-fixed text-nowrap">
                          <thead class="td-data-color">
                            <tr>
                              <th class="list-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
                              </th>
                              <th>Sr. No.</th>
                              <th>Name <a href="{{Request::url().$queryStringConcat}}&order_by=name&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Subject<a href="{{Request::url().$queryStringConcat}}&order_by=subject&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody class="td-data-color">
                          @forelse($mailTemplateData as $key=>$data)
                            <tr>
                              <th class="list-checkbox">
                                <input class="form-check-input" name="check" type="checkbox" value="{{$data['id']}}"
                                  id="flexCheckDefault">
                              </th>
                              <td>
                                {{ ++$i }}
                              </td>
                              <td class="table_wrap_data">
                              <a href="{{ route('mail-template.edit',$data['id']) }}" title="Edit">@forelse($data->mailCodeName as $mail_name)  
                                {{$mail_name['name']}}<br>
                                @endforeach</a>
                              </td>
                               <td>@forelse($data->mailCodeName as $mail_name) 
                                {{ $mail_name['subject'] }}<br>
                                @endforeach
                              </td>
                              <td class="table-action">
                                <span>
                                  <a href="{{ route('mail-template.edit',$data['id']) }}" title="Edit">
                                    <?xml version="1.0"?><svg fill="#198754" viewBox="0 0 24 24" width="20" height="20">
                                      <path
                                        d="M 19.171875 2 C 18.448125 2 17.724375 2.275625 17.171875 2.828125 L 16 4 L 20 8 L 21.171875 6.828125 C 22.275875 5.724125 22.275875 3.933125 21.171875 2.828125 C 20.619375 2.275625 19.895625 2 19.171875 2 z M 14.5 5.5 L 3 17 L 3 21 L 7 21 L 18.5 9.5 L 14.5 5.5 z" />
                                    </svg></a>
                                </span>
                              </td>
                            </tr>
                            @endforeach
                              @if($mailTemplateData->isEmpty())
                            <tr>
                                <td colspan="12" style="text-align: center;">No Record Found</td>
                            </tr>
                            @endif
                          </tbody>
                        </table>

                      </div>
                       <nav class="pagination-grid" aria-label="Page  navigation example">
                        <ul class="pagination">
                            <?php if($mailTemplateData->hasPages()) { ?>
                          {!! $mailTemplateData->appends(Request::except('page'))->render() !!}
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
 <script>
   
    document.getElementById('flexCheckDefault1').onclick = function () {
      var checkboxes = document.getElementsByName('check');
      for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    }
  
  </script>
@append