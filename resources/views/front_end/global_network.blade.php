@extends('front_end.layout.layout_master')

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
</style>
@endpush

@section('page_content')


<section class="innerpage-banner">
    
    
        <img src="{{ asset('public/front_end/images/globalnet.jpg') }}" style="height: 320px;">
    

</section>

<section class="container">

    <div class="breadcrumb"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>Multotec Global Network</li>
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

            <h1>Multotec Global Network</h1>
            <p>Would you like to speak to Multotec directly?<br/>
            Use our location finder to view the contact details of the branch closest to you.</p>

            <div class="row">
                <div class="col-sm-3">
                    <select id="continent" class="form-control">
                        <option value="">SELECT CONTINENT</option>
                        @if(isset($allContinents))
                            @foreach($allContinents as $v)
                                <option value="{{ $v->id }}">{{ $v->continents_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-sm-3">
                    <select id="country" class="form-control">
                        <option value="">SELECT COUNTRY</option>
                    </select>
                </div> 
                <div class="col-sm-3">
                    <select id="city" class="form-control">
                        <option value="">SELECT CITY</option>
                    </select>
                </div>    
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="mapcontainer">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="mapdata" value="@if(isset($mapdata)){{ $mapdata }}@endif">
        </div>
    </div>

</div>
</section>
<!--- END FIRST BLOCK --->
<!--
-------- -------------- -------------- -------------- -------------- --------------- -------------
-->

@endsection






@push('page_js')
<script type="text/javascript">
function initMap() {

    var mapdata = document.getElementById('mapdata').value;
    var objx = JSON.parse( mapdata );
    var objxLen = objx.length;
    var citymap = {};
    var locations = [];
    var show = '';
    var centerlat = 0;
    var centerlng = 0; 

    if( objxLen > 0 ) {
        
        for(var i = 0; i < objxLen; i++) {
            show = objx[i].show;
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
            arr[0] = objx[i].name;
            arr[1] = parseFloat(objx[i].lat);
            arr[2] = parseFloat(objx[i].lng);
            arr[3] = parseInt(i);
            locations[ i ] = arr;
        }
    }

    var zoomvalue = 2;
    var radiusvalue = 2000000;
    
    if( show == 'country' ) {
        zoomvalue = 3;
        radiusvalue = 450000;
    }

    if( show == 'city' ) {
        zoomvalue = 4;
        radiusvalue = 0;
    }

    if( show == 'office' ) {
        zoomvalue = 14;
        radiusvalue = 0;
    }

    var map = new google.maps.Map(document.getElementById('map'), {
        //zoom: 13,
        zoom: zoomvalue,
        //center: new google.maps.LatLng(41.976816, -87.659916),
        center: new google.maps.LatLng(centerlat, centerlng),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    for (var city in citymap) {
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
    }

    var infowindow = new google.maps.InfoWindow({});

    var marker, i;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,    
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }
}


$( function() {

    $('#continent').on('change', function() { 
        if( $(this).val() != '' ) {
            $.ajax({
                type : "POST",
                url : "{{ route('map.conti', array('lng' => 'en')) }}",
                data : {
                    'continent_id' : $(this).val(),
                    '_token' : "{{ csrf_token() }}"
                },
                beforeSend : function() {

                },
                success : function(jsn) {
                    var obj = JSON.parse( jsn );
                    $('#mapdata').val(JSON.stringify(obj.mapdata));

                    var opHTML = '<option value="">-SELECT COUNTRY-</option>';
                    var jArr = obj.countryList;
                    var jArrLen = jArr.length;
                    if( jArrLen > 0 ) {
                        for( var i = 0; i < jArrLen; i++) {
                            opHTML += '<option value="'+ jArr[i].id +'">'+ jArr[i].name +'</option>';
                        }
                    } 

                  $('#country').html(opHTML);

                    initMap();
                }
            });
        }
    } ); 

    $('body').on('change', '#country', function() { 
        if( $(this).val() != '' ) {
            $.ajax({
                type : "POST",
                url : "{{ route('map.count', array('lng' => 'en')) }}",
                data : {
                    'country_id' : $(this).val(),
                    '_token' : "{{ csrf_token() }}"
                },
                beforeSend : function() {

                },
                success : function(jsn) {
                    var obj = JSON.parse( jsn );
                    $('#mapdata').val(JSON.stringify(obj.mapdata));

                    var opHTML = '<option value="">-SELECT CITY-</option>';
                    var jArr = obj.cityList;
                    var jArrLen = jArr.length;
                    if( jArrLen > 0 ) {
                        for( var i = 0; i < jArrLen; i++) {
                            opHTML += '<option value="'+ jArr[i].id +'">'+ jArr[i].name +'</option>';
                        }
                    } 

                  $('#city').html(opHTML);

                    initMap();
                }
            });
        }
    } );

    $('body').on('change', '#city', function() { 
        if( $(this).val() != '' ) {
            $.ajax({
                type : "POST",
                url : "{{ route('map.city', array('lng' => 'en')) }}",
                data : {
                    'city_id' : $(this).val(),
                    '_token' : "{{ csrf_token() }}"
                },
                beforeSend : function() {

                },
                success : function(jsn) {
                    var obj = JSON.parse( jsn );
                    $('#mapdata').val(JSON.stringify(obj.mapdata));

                    initMap();
                }
            });
        }
    } );
    
} );
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ctyLhYi1UVzqbsc1fLA6evrrdGWeoWs&callback=initMap&sensor=false"></script>
@endpush

    