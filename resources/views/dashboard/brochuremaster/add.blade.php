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
</style>
 
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($prodCat))
    Edit Brochures
    @else
    Add Brochures
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allBrbrandId') }}">All Brochures</a></li>
    @if(isset($prodCat))
    <li class="active">Edit Brochures</li>
    @else
    <li class="active">Add Brochures</li>
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
      <a href="{{ route('allBrallId') }}" class="btn btn-primary"> All Brochures</a>
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
          <h3 class="box-title">@if(isset($prodCat)) Edit Brochure @else Add Brochure  @endif</h3>

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
      action="@if(isset($prodCat)){{ route('updateBrallId', ['id' => $content_id]) }}@else{{ route('saveBrallId') }}@endif" 
      method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="container-fluid">
        <div class="row">
            <!-- Brochure Name -->
             <div style="width:70%">
            <div class="col-md-10">
                <div class="form-group">
                    <label>Brochure Name (H1): <em>*</em></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Brochure Name" 
                           value="{{ isset($prodCat) ? $prodCat->name : '' }}">
                </div>
            </div>
            </div>

            <!-- Slug (Only if exists) -->
            @if(!empty($prodCat->slug))
            <div style="width:70%">
                <div class="col-md-10">
                    <div class="form-group">
                        <label>Slug:</label>
                        <input type="text" class="form-control" readonly value="{{ $prodCat->slug }}">
                    </div>
                </div>
                </div>
            @endif

            <!-- Brochure Image -->
            <div style="width:40%">
            <div class="col-md-10">
                <div class="form-group">
                    <label>Brochure Image: <em>*</em></label>
                    <input type="file" name="brochure_image" id="brochure_image" class="form-control" accept="image/*">
                    <small id="fileError" style="color:red; display:none;">Only image files (JPG, PNG, etc.) are allowed.</small>
                    <br>
                    <img id="imagePreview"
                         src="{{ isset($prodCat->thumbnail_image) ? asset('public/' . $prodCat->thumbnail_image) : '#' }}"
                         alt="Preview"
                         style="{{ isset($prodCat->thumbnail_image) ? '' : 'display:none;' }} max-width:200px; margin-top:10px;">
                </div>
            </div>
            </div>

            <!-- Desktop Content -->
            <div class="col-md-11">
                <div class="form-group">
                    <label>Desktop Content: </label>
                    <textarea name="page_content" class="form-control" id="pg_cont" data-error-container="#pg_cont_error">@if(isset($prodCat)){{ html_entity_decode($prodCat->description, ENT_QUOTES) }}@endif</textarea>
                    <div id="pg_cont_error"></div>
                </div>
            </div>

            <div class="col-md-12">
          <!-- Your existing form container -->
          <div id="brochureContainer" class="mt-2">
@if (!empty($brochure['brochure_details']) && count($brochure['brochure_details']) > 0) 
    @foreach ($brochure['brochure_details'] as $index => $detail)
        <div class="brochureItem">
            <div class="row">
                <!-- Hidden SL No -->
                <input type="hidden" name="sl_no[]" id="sl_no_{{ $index + 1 }}" value="{{ $index + 1 }}">

                <!-- Product Multi-select -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product_{{ $index + 1 }}">Product</label>
                        <select class="form-control select2" id="product_{{ $index + 1 }}" name="product_{{ $index + 1 }}[]" multiple>
                            @foreach ($product as $value)
                                <option value="{{ $value->id }}" 
                                    @if(isset($detail['brochure_products']) && in_array($value->id, collect($detail['brochure_products'])->pluck('product_id')->toArray())) selected @endif>
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
                        <select class="form-control" id="language_{{ $index + 1 }}" name="language[]">
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

                <!-- Brochure Type -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type_{{ $index + 1 }}">Brochure Type</label>
                        <select class="form-control" id="type_{{ $index + 1 }}" name="type[]">
                            <option value="">Select Type</option>
                            @foreach ($type as $value)
                                <option value="{{ $value->id }}" 
                                    {{ $detail['type_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Brochure Brand -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="brand_{{ $index + 1 }}">Brochure Brand</label>
                        <select class="form-control" id="brand_{{ $index + 1 }}" name="brand[]">
                            <option value="">Select Brand</option>
                            @foreach ($brand as $value)
                                <option value="{{ $value->id }}" 
                                    {{ $detail['brand_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

       
            

            <div class="row mt-2">

            <div class="col-md-2">
                    <div class="form-group">
                        <label for="size_{{ $index + 1 }}">Brochure Size</label>
                        <select class="form-control" id="size_{{ $index + 1 }}" name="size[]">
                            <option value="">Select Size</option>
                            @foreach ($size as $value)
                                <option value="{{ $value->id }}" 
                                    {{ $detail['size_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Download Name -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="download_name_{{ $index + 1 }}">Download Name</label>
                        <input type="text" class="form-control" id="download_name_{{ $index + 1 }}" 
                               name="download_name[]" placeholder="Enter name"
                               value="{{ $detail['download_name'] ?? '' }}">
                               <small>Please Dont Include File Extension</small>
                    </div>
                </div>

                <!-- Brochure File -->
           

                <div class="col-md-3">
                <div class="form-group">
                    <label for="brochure_{{ $index + 1 }}">Brochure</label>
                    <input type="file" class="form-control brochure-input" id="brochure_{{ $index + 1 }}" name="brochure[]" accept=".pdf,application/pdf">
                      @if(!empty($detail['brochure_pdf']))
                            <small>Current: <a href="{{ asset('public/' . $detail['brochure_pdf']) }}" target="_blank">View File</a></small>
                        @endif
                    <small id="fileErrorBrochure_{{ $index + 1 }}" class="fileErrorBrochure" style="color:red; display:none;">Only .PDF files are allowed.</small>
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
            <hr>
        </div>
    @endforeach
@else
    <!-- Default Empty Row -->
    <div class="brochureItem">
        <div class="row">
            <input type="hidden" name="sl_no[]" id="sl_no_1" value="1">

            <!-- Product Multi-select -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="product_1">Product</label>
                    <select class="form-control select2" id="product_1" name="product_1[]" multiple >
                        @foreach ($product as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Brochure Language -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="language_1">Brochure Language</label>
                    <select class="form-control" id="language_1" name="language[]" >
                        <option value="">Select Language</option>
                        @foreach ($language as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Brochure Type -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="type_1">Brochure Type</label>
                    <select class="form-control" id="type_1" name="type[]" >
                        <option value="">Select Type</option>
                        @foreach ($type as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Brochure Brand -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="brand_1">Brochure Brand</label>
                    <select class="form-control" id="brand_1" name="brand[]" >
                        <option value="">Select Brand</option>
                        @foreach ($brand as $value)
                            <option value="{{ $value->id }}">{{ $value->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

     

        <div class="row mt-2">

        <div class="col-md-2">
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
                </div>
            <!-- Download Name -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="download_name_1">Download Name</label>
                    <input type="text" class="form-control" id="download_name_1" name="download_name[]" placeholder="Enter name" >
                    <small>Please Dont Include File Extension</small>
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

            <div class="col-md-3">
                <div class="form-group">
                    <label for="brochure_1">Brochure</label>
                    <input type="file" class="form-control brochure-input" id="brochure_1" name="brochure[]" accept=".pdf,application/pdf" >
                    <small id="fileErrorBrochure_1" class="fileErrorBrochure" style="color:red; display:none;">Only .PDF files are allowed.</small>
                </div>
            </div>


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
        <hr>
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

@if(isset($prodCat) && !empty($prodCat))
<script type="text/javascript">
$( function() {
  $('body .pgb_rightControl #pageBuilderBtn').on('click', function() {
    $('.pgb_rightControl .cdiv').toggle('slide', { direction:'right' }, 200);
  });
} );


var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.pg_cont.updateElement();
});
fm.validate({
  errorElement: 'span',
  errorClass: 'roy-vali-error',
  ignore: [],
  normalizer: function(value) {
    return $.trim(value);
  },
  rules: {
    name: {
      required: true,
      minlength: 3
    }

  
    // brochure_pdf: {
    //   required: true,
    //   extension: "pdf"
    // }
  },
  messages: {
    name: {
      required: "Please enter brochure name.",
      minlength: "Name must be at least 3 characters."
    }

   
    // brochure_pdf: {
    //   required: "Please upload a brochure PDF.",
    //   extension: "This file format is not allowed. Please upload a PDF."
    // }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) {
      error.appendTo($(element.attr("data-error-container")));
    } else if (element.hasClass("select2-hidden-accessible")) {
      error.insertAfter(element.next('span.select2'));
    } else {
      error.insertAfter(element);
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
$( function() {
  <?php //if( !isset($prodCat) || ( isset($prodCat) && $prodCat->is_duplicate == '1') ) { ?>
  $('#scName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php //} ?>
});
function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, "");
  str = str.toLowerCase();
  var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaaaeeeeiiiioooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
  }
  str = str
    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
    .replace(/\s+/g, "-") // collapse whitespace and replace by -
    .replace(/-+/g, "-") // collapse dashes
    .replace(/^-+/, "") // trim - from start of text
    .replace(/-+$/, ""); // trim - from end of text
  return str;
}
</script>
@else
<script type="text/javascript">
$( function() {
  $('body .pgb_rightControl #pageBuilderBtn').on('click', function() {
    $('.pgb_rightControl .cdiv').toggle('slide', { direction:'right' }, 200);
  });
} );


var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.pg_cont.updateElement();
});
fm.validate({
  errorElement: 'span',
  errorClass: 'roy-vali-error',
  ignore: [],
  normalizer: function(value) {
    return $.trim(value);
  },
  rules: {
    name: {
      required: true,
      minlength: 3
    },
    brochure_image:{
        required: true,  
    }
  
    // brochure_pdf: {
    //   required: true,
    //   extension: "pdf"
    // }
  },
  messages: {
    name: {
      required: "Please enter brochure name.",
      minlength: "Name must be at least 3 characters."
    },
    brochure_image: {
      required: "Please Upload The Image."
 
    }
   
    // brochure_pdf: {
    //   required: "Please upload a brochure PDF.",
    //   extension: "This file format is not allowed. Please upload a PDF."
    // }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) {
      error.appendTo($(element.attr("data-error-container")));
    } else if (element.hasClass("select2-hidden-accessible")) {
      error.insertAfter(element.next('span.select2'));
    } else {
      error.insertAfter(element);
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
$( function() {
  <?php //if( !isset($prodCat) || ( isset($prodCat) && $prodCat->is_duplicate == '1') ) { ?>
  $('#scName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php //} ?>
});
function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, "");
  str = str.toLowerCase();
  var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaaaeeeeiiiioooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
  }
  str = str
    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
    .replace(/\s+/g, "-") // collapse whitespace and replace by -
    .replace(/-+/g, "-") // collapse dashes
    .replace(/^-+/, "") // trim - from start of text
    .replace(/-+$/, ""); // trim - from end of text
  return str;
}
</script>
@endif

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
   $(document).ready(function() {
    $('.select2').select2({ width: '100%' });

    // Validate PDF file on change
    $(document).on('change', '.brochure-input', function() {
        let file = $(this).val();
        let extension = file.split('.').pop().toLowerCase();

        // Get dynamic count from input ID (brochure_1, brochure_2...)
        let count = $(this).attr('id').split('_')[1];

        if (file && extension !== 'pdf') {
            $(this).val(''); // Clear invalid file
            $('#fileErrorBrochure_' + count).show(); // Show error for this row
            $('#submitBtn').prop('disabled', true);
        } else {
            $('#fileErrorBrochure_' + count).hide(); // Hide error for this row
            $('#submitBtn').prop('disabled', false);
        }
    });

    // Add more brochure items
    $(document).on('click', '.addMoreBtn', function() {
    let originalItem = $('.brochureItem:first');
    let newItem = originalItem.clone();

    newItem.find('.select2-container').remove();

    // ✅ Clear only file input and select, not text fields like sl_no
    newItem.find('input[type="file"]').val('');
    newItem.find('input[type="text"]').val('');
    newItem.find('select').val('');

    // ✅ Remove the "Current: View File" section from cloned item
    newItem.find('small:contains("Current:")').remove();

    let count = $('.brochureItem').length + 1;

    newItem.find('select, input').each(function() {
        let id = $(this).attr('id');
        if (id) $(this).attr('id', id.split('_')[0] + '_' + count);

        let name = $(this).attr('name');
        if (name && name.startsWith('product')) {
            $(this).attr('name', 'product_' + count + '[]');
        }
    });

    // ✅ Update the error message ID
    newItem.find('.fileErrorBrochure').attr('id', 'fileErrorBrochure_' + count).hide();

    newItem.find('input[name="sl_no[]"]').val(count);
    newItem.find('.removeBtn').attr('style', 'display:inline-block');

    $('#brochureContainer').append(newItem);
    newItem.find('select.select2').select2({ width: '100%' });
});


    // Remove brochure item
    $(document).on('click', '.removeBtn', function() {
        if ($('.brochureItem').length > 1) {
            $(this).closest('.brochureItem').remove();
        }
    });
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

<script>
    $(document).on('input', 'input[name="download_name[]"]', function() {
    let value = $(this).val();

    // Remove file extensions (anything after a dot)
    value = value.replace(/\.[a-zA-Z0-9]+$/, '');

    $(this).val(value);
});
</script>


 

@endpush