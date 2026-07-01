@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<style type="text/css">
.mapcontainer {
    width: 100%;
    height: auto;
    padding: 6px;
}
.mapcontainer #map {
    width: 100%;
    height: 550px;
}
.continentcard {
    border: 1px solid #098c60;
    border-top-width: 8px;
    border-top-color: #098c60;
    border-radius: 10px;
    padding: 18px 20px;
    margin: 0px 0 26px;
}
.continentlogo {
    width: 110px;
    height: 76px;
    background: #f5f8f7;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #e3e3e3;
    border-radius: 8px;
    margin: 0 auto 5px;
}
.continentlogo img {
    width: 60px;
    border-radius: 4px;
}
.countryname {
    font-size: 28px;
    line-height: 1.2;
    font-weight: 600;
    margin: 0px;
    text-align: center
}
.midblock{
    margin: 0px !important
}
.bottomcont {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0 0;
    border-top: 1px solid #f5f7f6;
    margin: 10px 0 0;
    text-decoration: none;
}
.bottomcont span {
    color: #098c60;
    font-size: 16px;
    font-weight: 600;
}
.arrowicon {
    width: 32px;
    height: 32px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #e5f7e8;
    border-radius: 50%;
}
.arrowicon img {
    width: 18px;
}

</style>
@endpush




@section('page_content')


{{-- <section class="innerpage-banner">
    <img src="https://www.multotec.icedev.co.za/public/uploads/files/media_images/media_8a6748eef4ed9f8bf9ce3b62e5dbb666.jpg" title="Continent" alt="Continent" caption="Continent">
</section> --}}
@if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif

@if( isset($currContinent) && $currContinent->image_id != '' && isset($currContinent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$currContinent->imageInfo->image) }}" title="{{ $currContinent->image_title }}" alt="{{ $currContinent->image_alt }}" caption="{{ $currContinent->image_caption }}">
</section>
@endif



<section class="container">
    <div class="breadcrumb"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('front.distrbMap', array('lng' => $lng)) }}">Location</a></li>
            @if( isset($currContinent) )
            <li class="active">{{ 'Multotec '.$currContinent->name }}</li>
            @endif
        </ul>
    </div>
</section>


<!--- FIRST BLOCK ---> 
<!--
    Here Title, Main Content, Buttons, Eform Fix
-->
<section class="container">
<div class="row">
  
    <div class="col-sm-12">
        <div class="midblock" id="firstBlock">

            @if( isset($extraContent) )
            <h1>{{ 'Multotec '.$extraContent->title }}</h1>
            <p>{!! html_entity_decode($extraContent->page_content) !!}</p>
            @endif

            @if( isset($currContinent) )
            <h1>{{ 'Multotec '.$currContinent->name }}</h1>
            <p>{!! html_entity_decode($currContinent->description) !!}</p>
            @endif
            
            {{-- <div class="row">
                <div class="col-sm-3">
                    <select id="continent" class="form-control mapDD">
                        <option value="">SELECT CONTINENT</option>
                        @if(isset($allContinents))
                            @foreach($allContinents as $v)
                                <option value="{{ $v->id }}" @if(isset($currContinent) && $currContinent->id == $v->id) selected="selected" @endif>{{ $v->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-sm-3">
                    <select id="country" class="form-control mapDD">
                        <option value="">SELECT COUNTRY</option>
                        @if(isset($seleCountries))
                            @foreach($seleCountries as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div> 
                <div class="col-sm-3">
                    <select id="branch" class="form-control mapDD">
                        <option value="">SELECT BRANCH</option>
                    </select>
                </div>    
            </div> --}}

         


         
        </div>
    </div>
</div>
</section>


<div class="container">
  <h4 class="mb-2 pl-2">
    <strong>Multotec Across {{$currContinent->name??''}} </strong>
    
</h4>

    <div class="continents">
        <div class="row">
        <input type="hidden" id="branchURL" value="{{ url('/en/location') }}">

            @foreach($countriesFlag as $val)
            <div class="col-md-6 col-lg-3">
                <div class="continentcard">
                    <div class="continentlogo">
                        <img src="{{ asset('public/uploads/files/continent_images/' . $val->logo) }}"
                            alt="{{ $val->country_name }}">
                    </div>

                    <h4 class="countryname"> {{ preg_replace('/[^A-Za-z0-9\s]/', '', trim(str_ireplace('Multotec', '', $val->country_name))) }}</h4>

                    <a href="{{ url('en/location/' . $val->continent_name . '/' . $val->country_slug) }}"
                    class="bottomcont">
                        <span>Explore trusted support</span>
                        <div class="arrowicon">
                            <img src="{{ asset('public/uploads/files/continent_images/arrow-right.svg') }}"
                                alt="">
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            {{-- <div class="col-md-6 col-lg-4">
                <div class="continentcard">
                    <div class="continentlogo"><img src="{{ asset('public/uploads/files/continent_images/chile-logo.jpg') }}" alt="logo"></div>
                    <h4 class="countryname">Chile</h4>
                    <a href="#" class="bottomcont">
                        <span>View Country</span>
                        <div class="arrowicon"><img src="{{ asset('public/uploads/files/continent_images/arrow-right.svg') }}" alt=""></div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="continentcard">
                    <div class="continentlogo"><img src="{{ asset('public/uploads/files/continent_images/peru-logo.jpg') }}" alt="logo"></div>
                    <h4 class="countryname">Peru</h4>
                    <a href="#" class="bottomcont">
                        <span>View Country</span>
                        <div class="arrowicon"><img src="{{ asset('public/uploads/files/continent_images/arrow-right.svg') }}" alt=""></div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="continentcard">
                    <div class="continentlogo"><img src="{{ asset('public/uploads/files/continent_images/brazil-logo.jpg') }}" alt="logo"></div>
                    <h4 class="countryname">Brazil</h4>
                    <a href="#" class="bottomcont">
                        <span>View Country</span>
                        <div class="arrowicon"><img src="{{ asset('public/uploads/files/continent_images/arrow-right.svg') }}" alt=""></div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="continentcard">
                    <div class="continentlogo"><img src="{{ asset('public/uploads/files/continent_images/chile-logo.jpg') }}" alt="logo"></div>
                    <h4 class="countryname">Chile</h4>
                    <a href="#" class="bottomcont">
                        <span>View Country</span>
                        <div class="arrowicon"><img src="{{ asset('public/uploads/files/continent_images/arrow-right.svg') }}" alt=""></div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="continentcard">
                    <div class="continentlogo"><img src="{{ asset('public/uploads/files/continent_images/peru-logo.jpg') }}" alt="logo"></div>
                    <h4 class="countryname">Peru</h4>
                    <a href="#" class="bottomcont">
                        <span>View Country</span>
                        <div class="arrowicon"><img src="{{ asset('public/uploads/files/continent_images/arrow-right.svg') }}" alt=""></div>
                    </a>
                </div>
            </div> --}}
        </div>
    </div>


</div>
<div class="padtop">
                    <div class="container">Multotec products are engineered to optimise costs, lifespan and efficiency of mineral beneficiation plants.</div>
                </div>
<!--- END FIRST BLOCK --->
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
function initMap() {

    var mapdata = document.getElementById('mapdata').value;
    var objx = JSON.parse( mapdata );
    var objxLen = objx.length;

    var pageURL = document.getElementById('branchURL').value + '/en/location/';

    var citymap = {};
    var locations = [];

    var centerlat = 0;
    var centerlng = 0; 

    if( objxLen > 0 ) {
        
        for(var i = 0; i < objxLen; i++) {
            centerlat = objx[i].lat;
            centerlng = objx[i].lng;
        }
    }

    if( objxLen > 0 ) {
        
        for(var i = 0; i < objxLen; i++) {
            var innerArray = {};
            var arr = {};
            arr['lat'] = parseFloat(objx[i].lat);
            arr['lng'] = parseFloat(objx[i].lng);
            innerArray['center'] = arr;
            var str = objx[i].name;
            var str = str.replace(' ','_');
            citymap[ str ] = innerArray;
        }
    }

    if( objxLen > 0 ) {
        
        for(var i = 0; i < objxLen; i++) {
            var arr = {};
            
            var btype = objx[i].branch_type;
                btype = btype.replace('_',' ');

            var gmapview = '<a href="https://maps.google.com/?q=' + objx[i].lat + ',' + objx[i].lng + '" target="_blank">Google View</a>';
            var branchview = '<a href="' + pageURL + objx[i].continent_slug + '/' + objx[i].country_slug + '/' + objx[i].branch_slug +'">View Branch</a>';

            arr[0] = '<strong>' + objx[i].name + '</strong> ('+ btype +')' + '<br/>' + objx[i].address + '<br/><br/><u>' + gmapview + '</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>' + branchview + '</u>';
            arr[1] = parseFloat(objx[i].lat);
            arr[2] = parseFloat(objx[i].lng);
            arr[3] = parseInt(i);
            arr[4] = objx[i].branch_type;
            locations[ i ] = arr;
        }
    }

    var zoomvalue = 2;
    var radiusvalue = 2000000;

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 5,
        //zoom: zoomvalue,
        //center: new google.maps.LatLng(41.976816, -87.659916),
        center: new google.maps.LatLng(centerlat, centerlng),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    map.setOptions({minZoom: -2, maxZoom: 12});

    /*for (var city in citymap) {
      // Add the circle for this city to the map.
      var cityCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map,
        center: citymap[city].center,
        radius: radiusvalue
      });
    }*/

    var infowindow = new google.maps.InfoWindow({});

    var marker, i;

    var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < locations.length; i++) {
        
        var marker_icon = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';

        if(locations[i][4] == 'Regional_Representatives') {
           marker_icon = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'; 
        }

        bounds.extend(new google.maps.LatLng(locations[i][1], locations[i][2]));
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map, 
            icon: marker_icon   
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    map.fitBounds(bounds);
}

$( function() {

    $('.mapDD').on('change', function() { 
        if( $(this).val() != '' ) {
            var clkEvt = $(this).attr('id');
            $.ajax({
                type : "GET",
                url : "{{ route('front.distrbMapFilter', array('lng' => 'en')) }}",
                data : {
                    'continent_id' : $.trim($('#continent').val()),
                    'country_id' : $.trim($('#country').val()),
                    'branch_id' : $.trim($('#branch').val()),
                    'click_on' : clkEvt,
                    '_token' : "{{ csrf_token() }}"
                },
                beforeSend : function() {

                },
                success : function(jsn) {
                    var obj = JSON.parse( jsn );
                    $('#mapdata').val(JSON.stringify(obj.map_data));
                    if( obj.click_on == 'continent' ) {

                        var opHTML_country = '<option value="">-SELECT COUNTRY-</option>';
                        var jArr = obj.countries;
                        var jArrLen = jArr.length;
                        if( jArrLen > 0 ) {
                            for( var i = 0; i < jArrLen; i++) {
                                opHTML_country += '<option value="'+ jArr[i].id +'">'+ jArr[i].name +'</option>';
                            }
                        } 
                        $('#country').html(opHTML_country);
                        $('#branch').html('<option value="">-SELECT BRANCH-</option>');
                    }

                    if( obj.click_on == 'country' ) {
                        
                        var opHTML_branch = '<option value="">-SELECT BRANCH-</option>';
                        var jArr = obj.branches;
                        var jArrLen = jArr.length;
                        if( jArrLen > 0 ) {
                            for( var i = 0; i < jArrLen; i++) {
                                opHTML_branch += '<option value="'+ jArr[i].id +'">'+ jArr[i].name +'</option>';
                            }
                        } 
                        $('#branch').html(opHTML_branch);
                    }

                    initMap();
                }
            });
        }
    } );

} );
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ctyLhYi1UVzqbsc1fLA6evrrdGWeoWs&callback=initMap&sensor=false"></script>


@endpush

    