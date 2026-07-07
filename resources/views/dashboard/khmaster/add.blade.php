@extends('dashboard.layouts.app')

@push('page_css')

<style>
  .select2-container--default .select2-results__options {
    max-height: 200px;  /* Adjust as needed */
    overflow-y: auto;   /* Enable vertical scroll */
} 
.select2-container{
        width:auto;
        display:block !important;
    }
    .brochureItem {
    width: 91%;
}
.brochureItem {
    width: 91%;
    border: 1px solid #d2d6de;
    padding: 25px;
    margin-bottom: 15px;
}
</style>
 
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($prodCat))
    Edit Knowledge Hub
    @else
    Add Knowledge Hub
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allKhallId') }}">All Knowledge Hub</a></li>
    @if(isset($prodCat))
    <li class="active">Edit Knowledge Hub</li>
    @else
    <li class="active">Add Knowledge Hub</li>
    @endif
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class=" @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allKhallId') }}" class="btn btn-primary"> All Knowledge Hub</a>
      @if(isset($prodCat) && $prodCat->is_duplicate == '0')
      <a href="{{ url($prodCat->slug) }}" target="_blank" class="btn btn-primary"> View Page</a>
      @endif
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($prodCat)) Edit Knowledge Hub @else Add Knowledge Hub  @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>   

        <div class="box-body">
        <form name="jfrm" id="frmx" 
      action="@if(isset($khdetails)){{ route('updateKhallId', ['id' => $content_id]) }}@else{{ route('saveKhallId') }}@endif" 
      method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="container-fluid">
        <div class="row">
            <!-- Brochure Name -->
             <div style="width:70%">
            <div class="col-md-10">
                <div class="form-group">
                    <label> Name (H1): <em>*</em></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Brochure Name" 
                           value="{{ isset($khdetails) ? $khdetails->name : '' }}">
                </div>
            </div>
            </div>

            <!-- Slug (Only if exists) -->
            @if(!empty($khdetails->slug))
            <div style="width:70%">
                <div class="col-md-10">
                    <div class="form-group">
                        <label>Slug:</label>
                        <input type="text" class="form-control" readonly value="{{ $khdetails->slug }}">
                    </div>
                </div>
                </div>
            @endif

            <!-- Brochure Image -->
            <div style="width:40%">
            <div class="col-md-10">
                <div class="form-group">
                    <label>Image: <em>*</em></label>
                    <input type="file" name="brochure_image" id="brochure_image" class="form-control" accept="image/*">
                    <small id="fileError" style="color:red; display:none;">Only image files (JPG, PNG, etc.) are allowed.</small>
                    <br>
                    <img id="imagePreview"
                         src="{{ isset($khdetails->image) ? asset('public/' . $khdetails->image) : '#' }}"
                         alt="Preview"
                         style="{{ isset($khdetails->image) ? '' : 'display:none;' }} max-width:200px; margin-top:10px;">
                </div>
            </div>
            </div>

            <!-- Desktop Content -->
        

            <div class="col-md-12">
          <!-- Your existing form container -->
          <div id="brochureContainer" class="mt-2">
            @if (!empty($knowledge_hub) && count($knowledge_hub) > 0) 
           @foreach ($knowledge_hub['knowledge_details'] as $index => $detail) 
                        <div class="brochureItem">
                            <div class="row"> 
                                <!-- Hidden SL No -->
                                <input type="hidden" name="sl_no[]" id="sl_no_{{ $index + 1 }}" value="{{ $index + 1 }}">


                            <div class="col-md-12">
                            <div class="form-group">
                                <label>Short Description:</label>

                                <textarea 
                                    name="short_description[]" 
                                    class="form-control"
                                    id="pg_short_cont_{{ $index }}"
                                    data-error-container="#pg_short_cont_error_{{ $index }}"
                                    maxlength="150"
                                >{{ isset($detail['short_description']) ? html_entity_decode($detail['short_description'], ENT_QUOTES) : '' }}</textarea>

                                <div id="pg_short_cont_error_{{ $index }}"></div>
                            </div>
                        </div>
                            

                    <div class="col-md-3">
                    <div class="form-group">
                        <label for="language_{{ $index + 1 }}">Products</label>
                        <select class="form-control" id="products_{{ $index + 1 }}" name="product[]" required>
                            <option value="">Select Products</option>
                            @foreach ($product as $value)
                                <option value="{{ $value->id }}" 
                                    {{ $detail['product_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                  <!-- Brochure Language -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="language_{{ $index + 1 }}">Commodities</label>
                        <select class="form-control" id="commodities_{{ $index + 1 }}" name="commodities[]" required>
                            <option value="">Select Commodities</option>
                            @foreach ($commodities as $value)
                                <option value="{{ $value->id }}" 
                                    {{ $detail['commodity_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Brochure Language -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="language_{{ $index + 1 }}">Brochure Language</label>
                        <select class="form-control" id="language_{{ $index + 1 }}" name="language[]" required>
                            <option value="">Select Language</option>
                            @foreach ($language as $value)
                                <option value="{{ $value->id }}" 
                                    {{ $detail['language_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

              

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="brand_{{ $index + 1 }}">Location</label>
                        <select class="form-control" id="brand_{{ $index + 1 }}" name="location[]" required>
                            <option value="">Select Location</option>
                            @foreach ($location as $value)
                                <option value="{{ $value->id }}" 
                                    {{ $detail['location_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                       {{-- <div class="col-md-12">
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description[]" class="form-control" id="pg_cont" data-error-container="#pg_cont_error">
        {{ isset($detail['description']) ? html_entity_decode($detail['description'], ENT_QUOTES) : '' }}
                </textarea>
                <div id="pg_cont_error"></div>
            </div>
        </div> --}}
  <div class="col-md-12">
    <div class="form-group">
        <label>Description:</label>

        <textarea 
            name="description[]" 
            class="form-control pg_cont"
            id="pg_cont_{{ $index }}"
            data-error-container="#pg_cont_error_{{ $index }}"
        >{{ isset($detail['description']) ? html_entity_decode($detail['description'], ENT_QUOTES) : '' }}</textarea>

        <div id="pg_cont_error_{{ $index }}"></div>
    </div>
</div>





               <div class="col-md-4">
                <div class="form-group">
                    <label for="webinar_link_1">Webinar Link</label>
                    <input type="text" class="form-control" id="webinar_link_1" 
                          name="webinar_link[]" 
                          value="{{ $detail['webinar_link'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="podcast_link_1">Podcast Link</label>
                    <input type="text" class="form-control" id="podcast_link_1" 
                          name="podcast_link[]" 
                          value="{{ $detail['podcast_link'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="brochure_link_1">Brochure Link</label>
                    <input type="text" class="form-control" id="brochure_link_1" 
                          name="brochure_link[]" 
                          value="{{ $detail['brochure_link'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="podcast_time_1">Podcast Time</label>
                    <input type="text" class="form-control" id="podcast_time_1" 
                          name="podcast_time[]" 
                          value="{{ $detail['podcast_time'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="podcast_time_1">Podcast Title</label>
                    <input type="text" class="form-control" id="podcast_title_1" 
                          name="podcast_title[]" 
                          value="{{ $detail['podcast_title'] ?? '' }}">
                </div>
            </div>




                 <!-- Buttons -->
                <div class="col-md-3" style="padding:20px;">
                    <div class="form-group d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-primary addMoreBtn" style="margin-right: 15px;">
                            <span class="fa fa-plus"></span>
                        </button>
                        <button type="button" class="btn btn-danger removeBtn" style="{{ $loop->first ? 'display:none' : '' }}">
                            <span class="fa fa-trash"></span>
                        </button>
                    </div>
                </div>
           

      
            </div>
            </div>

       
            
        
     
           
           
            
        </div>
    @endforeach
    @else
    <!-- Default Empty Row -->
    <div class="brochureItem">
        <div class="row">
            <input type="hidden" name="sl_no[]" id="sl_no_1" value="1">


            <div class="col-md-12">
    <div class="form-group">
        <label>Short Description:</label>

     <textarea 
    name="short_description[]" 
    class="form-control"
    id="pg_short_cont_0"
    data-error-container="#pg_short_cont_error_0"
 maxlength="146"></textarea>

<div id="pg_short_cont_error_0"></div>
    </div>
</div>
            <!-- Product Multi-select -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="product_1">Product</label>
                    <select class="form-control select2" id="product_1" name="product[]"  required >
                          <option value="">Select Product</option>
                        @foreach ($product as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="col-md-3">
                <div class="form-group">
                    <label for="commodities_1">Commodities</label>
                    <select class="form-control select2" id="commodities_1" name="commodities[]" required>
                        <option value="">Select Commodities</option>
                        @foreach ($commodities as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Brochure Language -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="language_1"> Language</label>
                    <select class="form-control select2" id="language_1" name="language[]" required>
                        <option value="">Select Language</option>
                        @foreach ($language as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

         

            <!-- Brochure Brand -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="brand_1">Location</label>
                    <select class="form-control select2" id="brand_1" name="location[]" required>
                        <option value="">Select Location</option>
                        @foreach ($location as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>



                {{-- <div class="col-md-12">
                <div class="form-group">
                    <label>Decription: </label>
                    <textarea name="description[]" class="form-control" id="pg_cont" data-error-container="#pg_cont_error"></textarea>
                    <div id="pg_cont_error"></div>
                </div>
            </div> --}}

        <div class="col-md-12">
    <div class="form-group">
        <label>Description:</label>

        <textarea 
            name="description[]" 
            class="form-control pg_cont"
            id="pg_cont_0"
            data-error-container="#pg_cont_error_0"
        ></textarea>

        <div id="pg_cont_error_0"></div>
    </div>
</div>





             


         
        </div>

     

        <div class="row mt-2">

        {{-- <div class="col-md-2">
                    <div class="form-group">
                        <label for="size_1">Brochure Size</label>
                        <select class="form-control" id="size_1" name="size[]" >
                            <option value="">Select Size</option>
                            @foreach ($size as $value)
                                <option value="{{ $value->id }}" 
                                  >
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
            <!-- Download Name -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="download_name_1">Webinar Link</label>
                    <input type="text" class="form-control" id="webinar_link_1" name="webinar_link[]" placeholder="" >
                   
                </div>
            </div>

               <div class="col-md-4">
                <div class="form-group">
                    <label for="download_name_1">Podcast Link</label>
                    <input type="text" class="form-control" id="podcast_link_1" name="podcast_link[]" placeholder="" >
                   
                </div>
            </div>

                    <div class="col-md-4">
                <div class="form-group">
                    <label for="download_name_1">Brochure Link</label>
                    <input type="text" class="form-control" id="brochure_link_1" name="brochure_link[]" placeholder="" >
                   
                </div>
            </div>

                   <div class="col-md-3">
                <div class="form-group">
                    <label for="podcast_time_1">Podcast Time</label>
                    <input type="text" class="form-control" id="podcast_time_1" 
                          name="podcast_time[]" 
                          value="">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="podcast_time_1">Podcast Title</label>
                    <input type="text" class="form-control" id="podcast_title_1" 
                          name="podcast_title[]" 
                          value="">
                </div>
            </div>

            <!-- Brochure File -->
            <!-- <div class="col-md-3">
                <div class="form-group">
                    <label for="brochure_1">Brochure</label>
                    <input type="file" class="form-control" id="brochure_1" name="brochure[]" accept=".pdf,application/pdf">
                    <small id="fileErrorBrochure" style="color:red; display:none;">Only .PDF files are allowed.</small>
                </div>
            </div> -->

        


            <!-- Buttons -->
            <div class="col-md-3" style="padding:20px;">
                <div class="form-group d-flex gap-2 mt-2">
                    <button type="button" class="btn btn-primary addMoreBtn" style="margin-right: 15px;">
                        <span class="fa fa-plus"></span>
                    </button>
                    <button type="button" class="btn btn-danger removeBtn" style="display:none">
                        <span class="fa fa-trash"></span>
                    </button>
                </div>
            </div>
        </div>
        
    </div>
@endif



          </div>
          <!-- #region -->
          </div>

            <!-- Submit Button -->
            <div class="col-md-10" style="margin-top: 20px;">
                <div class="form-group">
                    <input type="submit" id="submitBtn" class="btn btn-primary" name="submit" value="Submit">
                </div>
            </div>
        </div>
    </div>
</form>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>
 

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/shortable/Sortable.min.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>

<script>

$(document).ready(function () {

    // ✅ Toggle panel
    $('body .pgb_rightControl #pageBuilderBtn').on('click', function () {
        $('.pgb_rightControl .cdiv').toggle('slide', { direction: 'right' }, 200);
    });

    // ✅ Initialize CKEditor (only description fields)
    initCkEditors();

    // ✅ Form submit → update all editors
    $('#frmx').on('submit', function () {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });

    // ✅ Add More
    $(document).on('click', '.addMoreBtn', function () {

        let originalItem = $('.brochureItem:first');
        let newItem = originalItem.clone();

        // Remove select2 + CKEditor UI
        newItem.find('.select2-container').remove();
        newItem.find('.cke').remove();

        // Clear inputs
        newItem.find('input[type="file"]').val('');
        newItem.find('input[type="text"]:not([name="sl_no[]"])').val('');
        newItem.find('select').val('');
        newItem.find('textarea').val('');

        newItem.find('small:contains("Current:")').remove();

        let count = $('.brochureItem').length + 1;

        // Update input/select IDs
        newItem.find('select, input').each(function () {
            let id = $(this).attr('id');
            if (id) {
                $(this).attr('id', id.split('_')[0] + '_' + count);
            }
        });

        // ✅ Description textarea (CKEditor)
        newItem.find('textarea.pg_cont').each(function () {
            let newId = 'pg_cont_' + count;
            $(this).attr('id', newId);
        });

        // ✅ Short Description textarea (normal)
        newItem.find('textarea[name="short_description[]"]').each(function () {
            let newId = 'pg_short_cont_' + count;
            $(this).attr('id', newId);
        });

        // Update error container IDs
        newItem.find('[id^="pg_cont_error"]').attr('id', 'pg_cont_error_' + count);
        newItem.find('[id^="pg_short_cont_error"]').attr('id', 'pg_short_cont_error_' + count);

        // Other updates
        newItem.find('.fileErrorBrochure')
            .attr('id', 'fileErrorBrochure_' + count)
            .hide();

        newItem.find('input[name="sl_no[]"]').val(count);
        newItem.find('.removeBtn').show();

        // Append
        // $('#brochureContainer').append(newItem);
          $('.brochureItem:last').after(newItem);

        // Re-init select2
        newItem.find('select.select2').select2({ width: '100%' });

        // ✅ Init CKEditor ONLY for description
        initCkEditors();

    });

    // ✅ Remove item
    $(document).on('click', '.removeBtn', function () {
        if ($('.brochureItem').length > 1) {
            $(this).closest('.brochureItem').remove();
        }
    });

});


// ✅ Common CKEditor init (ONLY for description)
function initCkEditors() {
    $('.pg_cont').each(function () {
        let id = $(this).attr('id');

        if (id && !CKEDITOR.instances[id]) {
            CKEDITOR.replace(id, {
                customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}"
            });
        }
    });
}

</script>

<script>
document.getElementById('brochure_image').addEventListener('change', function (e) {
  const file = e.target.files[0];
  const preview = document.getElementById('imagePreview');
  const error = document.getElementById('fileError');

  if (file) {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    
    if (!allowedTypes.includes(file.type)) {
      preview.style.display = 'none';
      error.style.display = 'inline';
      e.target.value = ''; // Clear the invalid file
      return;
    }

    error.style.display = 'none';

    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  } else {
    preview.style.display = 'none';
    error.style.display = 'none';
  }
});
</script>



<script>
document.getElementById('brochure_image').addEventListener('change', function (e) {
  const file = e.target.files[0];
  const preview = document.getElementById('imagePreview');
  const error = document.getElementById('fileError');

  if (file) {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    
    if (!allowedTypes.includes(file.type)) {
      preview.style.display = 'none';
      error.style.display = 'inline';
      e.target.value = ''; // Clear the invalid file
      return;
    }

    error.style.display = 'none';

    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  } else {
    preview.style.display = 'none';
    error.style.display = 'none';
  }
});
</script>



 

@endpush