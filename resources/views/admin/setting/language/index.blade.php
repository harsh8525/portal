@extends('admin.layout.main')
@section('title', $header['title'])


@section('content')

      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">Language</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard')</a></li>
                <li class="breadcrumb-item active">Language</li>
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
                  <h3 class="mt-2 mb-0">Language List
                  </h3>
                  <div class="gridlist-icons">
                    <?php if(isset($_GET['per_page'])  && $_GET['per_page'] !== ""){
                      ?>
                    <div>
                      <h6>Showing {{ $languageData->firstItem() ? $languageData->firstItem() :'0' }} to {{ $languageData->lastItem() ? $languageData->lastItem() :'0' }}
                          of total {!! $languageData->total() !!} entries (filtered from {{ $languageListCount }} total entries) </h6>
                    </div>
                    <?php } else {?> 
                    <div>
                       <h6>Showing {{ $languageData->firstItem() ? $languageData->firstItem() :'0' }} to {{ $languageData->lastItem() ? $languageData->lastItem() :'0' }}
                          of total {!! $languageData->total() !!} entries</h6>
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
                  <form autocomplete="off" id="filter" method="GET" action="{{route('language.index')}}">
                    <div class="card-filter card-body w-100 mt-3">
                      <div class="row">
                        <div class="col-md-10">
                          <div class="row">
                            <div class="col-md-3 filter-form">
                              <div class="form-item form-float-style">
                                <input type="text" id="language_code" autocomplete="off" name="language_code" value="{{@$_GET['language_code']}}" class="is-valid">
                                <label for="language_code">Language Code</label>
                              </div>
                            </div>
                            <div class="col-md-3 filter-form">
                              <div class="form-item form-float-style">
                                <input type="text" id="language_name" autocomplete="off" name="language_name" value="{{@$_GET['language_name']}}" class="is-valid">
                                <label for="language_name">Language Name</label>
                              </div>
                            </div>
                             <div class="col-md-3 filter-form">
                          <div class="form-floating form-item mb-3">
                            <div class="form-item form-float-style serach-rem mb-3">
                              <div class="select top-space-rem after-drp form-float-style ">
                                <select data-live-search="true" name="language_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                <option value="" selected >Select Language Type</option>  
                                  <option @if($appliedFilter['language_type'] == 'LTR') selected="selected" @endif value="LTR" >LTR</option>
                                  <option @if($appliedFilter['language_type'] == 'RTL') selected="selected" @endif value="RTL" >RTL</option>
                                  </select>
                                  <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Language Type</label>
                              </div>                        
                              </div>
                            </div>
                          </div>
                            <div class="col-md-3 filter-form">
                              <div class="form-floating form-item mb-3">
                                  <div class="form-item form-float-style serach-rem mb-3">
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
                            <div class="col-md-3 filter-form">
                              <div class="form-floating form-item mb-3">
                                  <div class="form-item form-float-style serach-rem mb-3">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                      <select data-live-search="true" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                        <option value="" selected >Select Status</option>
                                        <option @if($appliedFilter['status'] == '1') selected="selected" @endif value="1" >Active</option>
                                        <option @if($appliedFilter['status'] == '0') selected="selected" @endif value="0" >In-active</option>
                                      </select>
                                      <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Status</label>
                                    </div>                        
                                  </div>
                                </div>
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
                              <a href="{{route('language.index')}}">
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
                              <th>Sr.No.</th>
                              <th>Language Code <a href="{{Request::url().$queryStringConcat}}&order_by=language_code&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Language Name <a href="{{Request::url().$queryStringConcat}}&order_by=language_name&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Language Type <a href="{{Request::url().$queryStringConcat}}&order_by=language_type&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Sort Order <a href="{{Request::url().$queryStringConcat}}&order_by=sort_order&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Is Default</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody class="td-data-color">
                            <tr>
                               @forelse($languageData as $key=>$data)
                              <td class="">
                              {{ ++$i}}
                              </td>
                              <td class="text-lowercase">
                              {{ $data['language_code']}}
                              </td>
                              <td>
                               {{ $data['language_name']}}
                              </td>
                              <td>
                               {{ $data['language_type']}}
                              </td>
                              <td>
                               {{ $data['sort_order']}}
                              </td>
                              <td>
                              <?xml version="1.0"  encoding="utf-8"?>
                                <?php if($data['language_code'] == $defaultLanguageCode){ //active ?>
                                  <strong>True</strong>
                                <?php }else{ //in-active ?>
                               False
                                <?php } ?>
                               </td>
                              <td>
                              <?xml version="1.0"  encoding="utf-8"?>
                                <?php if($data['status'] == 1){ //active ?>
                                <svg fill="#198754" version="1.1" id="Layer_1"
                                  x="0px" y="0px" width="20" height="20" viewBox="0 0 122.88 122.88"
                                  enable-background="new 0 0 122.88 122.88" xml:space="preserve">
                                  <g>
                                    <path
                                      d="M34.465,67.43c-1.461-1.322-1.574-3.579-0.252-5.041c1.322-1.461,3.58-1.574,5.041-0.252l13.081,11.862l31.088-32.56 c1.361-1.431,3.625-1.487,5.056-0.126c1.431,1.361,1.487,3.624,0.126,5.055L55.11,81.447l-0.005-0.004 c-1.33,1.398-3.541,1.489-4.98,0.187L34.465,67.43L34.465,67.43z M8.792,0h105.296c2.422,0,4.62,0.988,6.212,2.58 s2.58,3.791,2.58,6.212v105.295c0,2.422-0.988,4.62-2.58,6.212s-3.79,2.58-6.212,2.58H8.792c-2.421,0-4.62-0.988-6.212-2.58 S0,116.51,0,114.088V8.792C0,6.371,0.988,4.172,2.58,2.58S6.371,0,8.792,0L8.792,0z M114.088,7.17H8.792 c-0.442,0-0.847,0.184-1.143,0.479C7.354,7.945,7.17,8.35,7.17,8.792v105.295c0,0.442,0.184,0.848,0.479,1.144 c0.296,0.296,0.701,0.479,1.143,0.479h105.296c0.442,0,0.848-0.184,1.144-0.479c0.295-0.296,0.479-0.701,0.479-1.144V8.792 c0-0.443-0.185-0.848-0.479-1.143C114.936,7.354,114.53,7.17,114.088,7.17L114.088,7.17z" />
                                  </g>
                                </svg>
                                <?php }else if($data['status'] == 0){ //in-active ?>
                                <svg version="1.1" fill="#ee3137" id="Layer_1"
                                  x="0px" y="0px" width="20" height="20" viewBox="0 0 122.879 122.88"
                                  enable-background="new 0 0 122.879 122.88" xml:space="preserve">
                                  <g>
                                    <path
                                      d="M8.773,0h105.332c2.417,0,4.611,0.986,6.199,2.574c1.589,1.588,2.574,3.783,2.574,6.199v105.333 c0,2.416-0.985,4.61-2.574,6.199c-1.588,1.588-3.782,2.574-6.199,2.574H8.773c-2.416,0-4.611-0.986-6.199-2.574 C0.986,118.717,0,116.522,0,114.106V8.773c0-2.417,0.986-4.611,2.574-6.199S6.357,0,8.773,0L8.773,0z M80.549,37.291 c1.391-1.392,3.647-1.392,5.039,0s1.392,3.648,0,5.04L66.479,61.439l19.109,19.109c1.392,1.392,1.392,3.647,0,5.04 c-1.392,1.392-3.648,1.392-5.039,0L61.439,66.479L42.33,85.589c-1.392,1.392-3.648,1.392-5.04,0c-1.392-1.393-1.392-3.648,0-5.04 l19.109-19.109L37.291,42.331c-1.392-1.392-1.392-3.648,0-5.04s3.648-1.392,5.04,0L61.439,56.4L80.549,37.291L80.549,37.291z M114.105,7.129H8.773c-0.449,0-0.859,0.186-1.159,0.485c-0.3,0.3-0.486,0.71-0.486,1.159v105.333c0,0.448,0.186,0.859,0.486,1.159 c0.3,0.299,0.71,0.485,1.159,0.485h105.332c0.449,0,0.86-0.187,1.159-0.485c0.3-0.3,0.486-0.711,0.486-1.159V8.773 c0-0.449-0.187-0.859-0.486-1.159C114.966,7.315,114.555,7.129,114.105,7.129L114.105,7.129z" />
                                  </g>
                                </svg>
                                <?php } ?>
                               </td>
                              <td class="table-action">
                                @if($data['language_code'] != 'en')
                                <span>
                                  <a href="{{ route('language.translate.b2c',$data['id']) }}" title="B2C Translate">
                                  <i class="fas fa-language fa-2x" style="color: #7c1818;">&nbsp</i>
                                  </a>
                                </span>
                                <span>
                                  <a href="{{ route('language.translate.b2b',$data['id']) }}" title="B2B Translate">
                                  <i class="fas fa-language fa-2x" style="color: #0830f1;">&nbsp</i>
                                  </a>
                                </span>
                                @endif
                              </td>
                              </tr>
                             @endforeach
                              @if($languageData->isEmpty())
                              <tr>
                                <td colspan="12" style="text-align: center;">No Record Found</td>
                              </tr>
                              @endif
                          </tbody>
                        </table>
                      </div>
                       <nav class="pagination-grid" aria-label="Page  navigation example">
                        <ul class="pagination">
                            <?php
                            if($languageData->hasPages()) { ?>
                          {!! $languageData->appends(Request::except('page'))->render() !!}
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
  <!-- Page specific script -->
  <script>
   

    document.getElementById('flexCheckDefault1').onclick = function () {
      
      var checkboxes = document.getElementsByName('check');
      for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    }

  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

  @append