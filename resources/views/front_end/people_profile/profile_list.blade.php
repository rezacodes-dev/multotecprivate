@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@section('page_content')

@if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif


<section class="container">
    <div class="breadcrumb2"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>People Profile</li>
        </ul>
    </div>
</section>



<section class="container">

@if( isset($allData) && count($allData) > 0 )
  @foreach($allData as $v)
    @if( isset($v->orderByDisplay) && !empty($v->orderByDisplay) && count($v->orderByDisplay) > 0 )
    <div class="row">
      <div class="col-sm-12">
        <h1>{{ $v->name }}</h1>
          <div class="peopleprofile">
              <div class="row">
                @php $i = 1; @endphp
                @foreach($v->orderByDisplay as $pro)
                  <div class="col-sm-6">
                    <div class="peopleinner">
                      <div class="row">
                        <div class="col-sm-4">
                          @if(isset($pro->ProfileImageId) && isset($pro->ProfileImageId->imageInfo) )
                          <img src="{{ asset('public/uploads/files/media_images/'. $pro->ProfileImageId->imageInfo->image) }}" title="{{ $pro->ProfileImageId->title }}" alt="{{ $pro->ProfileImageId->alt_tag }}"> 
                          @endif
                        </div>
                        <div class="col-sm-8">
                          <div class="headingpeople">
                            <span><a href="{{ route('front.profCont', array('lng' => 'en', 'slug' => $pro->slug) ) }}">{{ $pro->name }}</a></span>
                            {{ $pro->designation }}
                          </div>
                          <p class="proDes">{{ str_limit($pro->description, '164', '...') }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  @if($i % 2 == 0) <div class="clearfix"></div> @endif
                  @php $i++; @endphp 
                @endforeach
              </div>
          </div>
      </div>
    </div>
    @endif
  @endforeach
@endif
</section>
@endsection




@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
<script type="text/javascript">
//Initialize 2nd demo:
ddaccordion.init({
  headerclass: "accor_heading", //Shared CSS class name of headers group
  contentclass: "accor_body", //Shared CSS class name of contents group
  revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
  mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
  collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
  defaultexpanded: [true], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
  onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
  animatedefault: false, //Should contents open by default be animated into view?
  scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
  persiststate: false, //persist state of opened contents within browser session?
  toggleclass: ["closed_arrow", "open_arrow"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
  togglehtml: ["prefix", "<img src='{{ asset('public/front_end/images/arrow_down_accor.png') }}' style='width:24px; height:24px' /> ", "<img src='{{ asset('public/front_end/images/arrow_up_accor.png') }}' style='width:24px; height:24px' /> "],  //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
  animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
  oninit:function(expandedindices){ //custom code to run when headers have initalized
    //do nothing
  },
  onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
    //do nothing
  }
});
</script>
@endpush

    