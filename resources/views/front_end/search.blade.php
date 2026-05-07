@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@section('page_content')

@if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif


<section class="container">

    <div class="breadcrumb"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>Search</li>
        </ul>
    </div>

</section>

<section class="container">
  <div class="row">
    <div class="col-sm-8">
      <div class="midblock" id="firstBlock">
      @php $slug = ''; @endphp
      @if( isset($_GET['q']) && $_GET['q'] != '' ) <h2>Search Results For "{{ $_GET['q'] }}"</h2> @endif
      @if( isset($allData) && count($allData) > 0 )
        @foreach($allData as $v)
          @if($v->name != '' && $v->slug != '' && $v->type != '')
          <div class="row">
            <div class="col-sm-12" style="border-bottom: 1px solid #ccc;">
              @if($v->type=='fsearch')
              <h4>{{ $v->description }}</h4>
              @elseif($v->type=='vid')
              <h4>{{ $v->name }}</h4>
              @else
              <h4>{{ $v->name }}</h4>
              @endif
        
           
              @if($v->type=='fsearch')
              <p>{!! trim( html_entity_decode( str_limit($v->name, 80, '...'), ENT_QUOTES ) ) !!}</p>
              @elseif($v->type=='vid')
              <p>
    {!! $v->description 
        ? implode(' ', array_slice(explode(' ', strip_tags(html_entity_decode($v->description, ENT_QUOTES, 'UTF-8'))), 0, 50)) . '...' 
        : '' 
    !!}
</p>
              @else
              <p>{!! trim( html_entity_decode( str_limit($v->description, 150, '...'), ENT_QUOTES ) ) !!}</p>
              @endif
        
             <!--  <p>{{ str_limit($v->description, 150, '...') }}</p> -->
              @php 
              
                if($v->type == 'article') {
                  $slug = route('front.artCont', array('lng' => 'en', 'slug' => $v->slug));
                }
                elseif($v->type == 'event') {
                  $slug = route('front.evtCont', array('lng' => 'en', 'slug' => $v->slug));
                }
                elseif($v->type == 'people') {
                  $slug = route('front.profCont', array('lng' => 'en', 'slug' => $v->slug));
                }
                elseif($v->type == 'wbnar') {
                  $slug = route('front.webinarCont', array('lng' => 'en', 'id' => $v->slug));
                }
                else {
                  $slug = route('front_cms_page', array('lng' => 'en', 'slug' => $v->slug));
                }
              @endphp
       
              @if($v->type=='fsearch')
      
              <p>   
                
                <a class="btn1 btn2-default" href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLinkFront($v->id)) }}" download>
                Download  File <i class="fa fa-download" aria-hidden="true"></i></a></p>
              @elseif($v->type=='vid')
              <div class="video-new-thumb" style="width:30%;height:30%;">
            <div class="video-thumb">
              <div class="videooverlay yvid" id="{{ $v->video_link }}" data="{{ $v->description }}"></div>
              <div class="imgxfix">
                  <a href="javascript:void(0);" class="yvid" id="{{ $v->video_link }}" data="">
                  <img src="https://img.youtube.com/vi/{{ $v->video_link }}/hqdefault.jpg" title="{{ $v->name }}" />
                  </a>
              </div>
            </div>
            </div>
            @else
            <p><a href="{{ $slug }}" class="btn1 btn2-default">Read More</a></p>
     
              @endif
      
            </div>
          </div>
          @endif
        @endforeach
      @else
      <h4>Sorry! No Records Found.</h4>
      @endif
      </div>
      <?php 
      if( isset($pagination) ) {
        $search_term = (isset($_GET['q'])? $_GET['q'] : '');
        echo $pagination->appends(array('q'=>$search_term))->links();
      }
      ?>
    </div>
    <div class="col-sm-4">
      <div class="rightpanel">
      </div>
    </div>
  </div>
</section>

<div id="vidModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body" id="vidBox">
        
      </div>
      <div class="modal-footer" id="vidCap" style="text-align: left;">
        
      </div>
    </div>

  </div>
</div>
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
<script type="text/javascript">
$( function() {
    $('.yvid').on('click', function() {
        var vPlayer = '<iframe width="100%" height="315" src="https://www.youtube.com/embed/'+ $(this).attr('id') +'?autoplay=1"></iframe>';
        $('#vidBox').html( vPlayer );
        $('#vidCap').html( $(this).attr('data') );
        $('#vidModal').modal();
    } );
} );
</script>
@endpush


    