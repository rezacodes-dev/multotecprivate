@extends('dashboard.layouts.app')

@push('page_css')
 
 
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($prodCat))
    Edit Webinar
    @else
    Add Webinar
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('prodCats') }}">All Webinar</a></li>
    @if(isset($prodCat))
    <li class="active">Edit Webinar</li>
    @else
    <li class="active">Add Webinar</li>
    @endif
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allWb') }}" class="btn btn-primary"> All Webinar</a>
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
          <h3 class="box-title">@if(isset($prodCat)) Edit Webinar @else Add Webinar @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($prodCat)){{ route('updateWb', array('id' => $content_id)) }}@else{{ route('saveWb') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          
          <div class="row">

          @if( isset($prodCat) ) 
              <input type="hidden" id="table_id" name="table_id" value="{{ $prodCat->id }}"> 
              @else 
              <input type="hidden" id="table_id" value="0" name="table_id"> 
              @endif

            <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Name : <em>*</em></label>
                <input type="text" name="name" required id="name" class="form-control" placeholder="Enter Webinar Name " value="@if( isset($prodCat) ){{ $prodCat->name }}@endif">
              </div> 
              </div>


              <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Slug : <em>*</em></label>
                <input type="text" readonly name="slug" required id="slug" class="form-control"  value="@if( isset($prodCat) ){{ $prodCat->slug }}@endif">
              </div> 
              </div>

             <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Type : <!-- <em>*</em> --></label>
                <select class="form-control" name="webinar_type" id="webinar_type">
                  <option value="">-Select Webinar Type-</option>
                  <option value="1" @if(isset($prodCat) && $prodCat->webinar_type=='1') selected @endif>Multotec Webinar</option>
                   <option value="2" @if(isset($prodCat) && $prodCat->webinar_type=='2') selected @endif>Collaboration</option>
                </select>
              </div> 
              </div>


              <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Start Date : <em>*</em></label>
                <input type="date" name="webinar_start_date" required id="webinar_start_date" class="form-control"  value="@if( isset($prodCat) ){{ $prodCat->webinar_start_date }}@endif">
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Webinar End Date : <em>*</em></label>
                <input type="date" name="webinar_end_date" required id="webinar_end_date" class="form-control"  value="@if( isset($prodCat) ){{ $prodCat->webinar_end_date }}@endif">
              </div> 
              </div>  

              <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Duration : </label>
                <input type="text" name="duration" id="duration" class="form-control" placeholder="Enter Webinar Duration " value="@if( isset($prodCat) ){{ $prodCat->duration }}@endif">
              </div> 
              </div>            

              <div class="col-md-10">
              <div class="form-group">
                <label>Sub Heading : <em>*</em></label>
                <input type="text" name="sub_heading" required id="sub_heading" class="form-control" placeholder="Enter Sub Heading " value="@if( isset($prodCat) ){{ $prodCat->sub_heading }}@endif">
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Speaker : <em>*</em></label>
                <input type="text" name="speaker" required id="speaker" class="form-control" placeholder="Enter Speaker" value="@if( isset($prodCat) ){{ $prodCat->speaker }}@endif">
              </div> 
              </div>
  
              <div class="col-md-10">
              <div class="form-group">
                <label>Short Description : <em>*</em></label>
                <textarea  name="short_description" required id="short_description" class="form-control" placeholder="Enter Short Description" >@if( isset($prodCat) ){{ $prodCat->short_description }}@endif</textarea>
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Description : <em>*</em></label>
                <textarea  name="description" required id="description" class="form-control" placeholder="Enter Description " >@if( isset($prodCat) ){{ $prodCat->description }}@endif</textarea>
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Product(s) : <em>*</em></label> 
                <select name="webinar_category[]" required class="form-control" multiple>
                  <option value="">-Select Webinar Product-</option>

                  <?php
                  if(isset($prodCat->webinar_category)){
                    $pto=explode(',',$prodCat->webinar_category);
                  }
                  
                  ?>


                  @if( isset($allSource) && !empty($allSource) )
                    @foreach( $allSource as $pc )


                    <option value="{{ $pc->id }}" @if( isset($prodCat) && in_array($pc->id, $pto)) selected="selected" @endif>{{ ucfirst( $pc->name ) }}</option>
                    @endforeach
                  @endif
                </select>
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Topic(s) : <em>*</em></label> 
                <select name="webinar_topic[]" required class="form-control" multiple>
                  <option value="">-Select Webinar Topic-</option>
                  
<?php

if(isset($prodCat->webinar_topic)){
$topic=explode(',',$prodCat->webinar_topic);

}?>
                  @if( isset($alltopics) && !empty($allSource) )
                    @foreach( $alltopics as $pc )


                    <option value="{{ $pc->id }}" @if( isset($prodCat) && in_array($pc->id, $topic)) selected="selected" @endif>{{ ucfirst( $pc->name ) }}</option>
                    @endforeach
                  @endif
                </select>
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Webinar Industry  : <em>*</em></label> 
                <select name="webinar_industry[]" required class="form-control" multiple>
                  <option value="">-Select Webinar Industry-</option>
                  
<?php

if(isset($prodCat->webinar_industry)){
$topic=explode(',',$prodCat->webinar_industry);

}?>
                  @if( isset($allindustry) && !empty($allSource) )
                    @foreach( $allindustry as $pc )


                    <option value="{{ $pc->id }}" @if( isset($prodCat) && in_array($pc->id, $topic)) selected="selected" @endif>{{ ucfirst( $pc->name ) }}</option>
                    @endforeach
                  @endif
                </select>
              </div> 
              </div>
 
              <div class="col-md-10">
              <div class="form-group">
                <label>Image : <em>*</em></label>
                <input type="file" name="image" id="image" class="form-control" >
 
                @if( isset($prodCat) )

                    @if($prodCat->image != '' && $prodCat->image != null)
                      @php
                      $imageURL = asset('public/uploads/user_images/thumb/'.$prodCat->image);
                      @endphp
                      <img src="{{ $imageURL }}" id="user_image_preview" class="ar_img_preview" data="{{ $imageURL }}">
                    @else
                      <!-- <img src="{{ asset('public/images/user-avatar.png') }}" id="user_image_preview" class="ar_img_preview" 
                      data="{{ asset('public/images/user-avatar.png') }}"> -->
                    @endif
                   
                @endif    
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Video Image : <em>*</em></label>
                <input type="file" name="video_image" id="video_image" class="form-control" >
 
                @if( isset($prodCat) )

                    @if($prodCat->video_image != '' && $prodCat->video_image != null)
                      @php
                      $imageURL = asset('public/uploads/user_images/thumb/'.$prodCat->video_image);
                      @endphp
                      <img style="width: unset;" src="{{ $imageURL }}" id="user_image_preview" class="ar_img_preview" data="{{ $imageURL }}">
                    @else
                      <!-- <img src="{{ asset('public/images/user-avatar.png') }}" id="user_image_preview" class="ar_img_preview" 
                      data="{{ asset('public/images/user-avatar.png') }}"> -->
                    @endif
                   
                @endif    
              </div> 
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>URL : <em>*</em></label>
                <input type="text" name="url" required id="scName" class="form-control" placeholder="Paste Youtube Embed URL" value="@if( isset($prodCat) ){{ $prodCat->url }}@endif">
              </div>
              <input type="submit" class="btn btn-primary" value="Save"> 
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
<script type="text/javascript">


$( function() {
 
  $('#name').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#slug').val( string_to_slug( $(this).val() ) );
    }
  });
  
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



$( function() {
  $('body .pgb_rightControl #pageBuilderBtn').on('click', function() {
    $('.pgb_rightControl .cdiv').toggle('slide', { direction:'right' }, 200);
  });
} );
var editor_pg_cont = CKEDITOR.replace( 'description', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );


var editor_pg_cont = CKEDITOR.replace( 'short_description', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );


var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.pg_cont.updateElement();
});
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    name: {
      required: true,
      minlength: 3
    },
    slug: {
      required: true,
      nowhitespace: true,
      pattern: /^[A-Za-z\d-.]+$/,
      remote:{
        url: "{{ route('checkSlugUrlWb') }}",
        type: "post",
        data: {
          "slug_url": function() {
            return $( "#slug" ).val();
          },
          "_token": function() {
            return "{{ csrf_token() }}";
          },
          "id": function() {
            return $( "#table_id" ).val();
          }
        }
      }
    },
    
    
  },
  messages: {

    name: {
      required: 'Please Enter Category or Subcategory Name.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
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


 

@endpush