<div class="row">
    <div class="col-sm-4">
        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ asset('public/front_end/images/221_small.png') }}" class="mpng" alt=""></a>
            <a href="{{ url('/') }}"><img src="{{ asset('public/front_end/images/multo_name.svg') }}" class="msvg" alt=""></a>
            <div class="mob-src">
            <a class="login-btn" href="https://eur01.safelinks.protection.outlook.com/?url=https%3A%2F%2Fapps.multotec.com%2F&data=05%7C01%7Candrew%40argantic.co.za%7C891f230e441d4028f54208dabca71657%7C6c1d9a626600413f9c4b8631674b75b3%7C0%7C0%7C638029723127363936%7CUnknown%7CTWFpbGZsb3d8eyJWIjoiMC4wLjAwMDAiLCJQIjoiV2luMzIiLCJBTiI6Ik1haWwiLCJXVCI6Mn0%3D%7C3000%7C%7C%7C&sdata=RRdZR1oTtazdq7bpbegJbGyXlomt9vC6LyYrIzr2QQA%3D&reserved=0" target="_blank">
            <i class="fa fa-sign-in" aria-hidden="true"></i> <span>Multotec Login</span></a>
                <a href="#" class="togsrcbtn"><i class="fa fa-search"></i></a>
                <div class="searchtoggle" style="display: none;">
                    <form name="msrcFRM" method="GET" action="{{ route('globalSearch', array('lng' => 'en')) }}">
                        <input type="text" class="form-control" placeholder="Search" name="q" value="@if(isset($_GET['q'])){{ $_GET['q'] }}@endif" required="required">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <ul class="arfordsk">
            <li><a class="login-btn" href="https://eur01.safelinks.protection.outlook.com/?url=https%3A%2F%2Fapps.multotec.com%2F&data=05%7C01%7Candrew%40argantic.co.za%7C891f230e441d4028f54208dabca71657%7C6c1d9a626600413f9c4b8631674b75b3%7C0%7C0%7C638029723127363936%7CUnknown%7CTWFpbGZsb3d8eyJWIjoiMC4wLjAwMDAiLCJQIjoiV2luMzIiLCJBTiI6Ik1haWwiLCJXVCI6Mn0%3D%7C3000%7C%7C%7C&sdata=RRdZR1oTtazdq7bpbegJbGyXlomt9vC6LyYrIzr2QQA%3D&reserved=0" target="_blank">
                <i class="fa fa-sign-in" aria-hidden="true"></i> <span>Multotec Login</span></a>
            </li>
            @if(isset($socialLinks))
                @foreach($socialLinks as $scl)
                    @if( $scl->link != '' )
                        @if (strpos($scl->link, 'facebook') !== false)
                        <li><a class="facebook" href="{{ $scl->link }}" target="_blank">
                            <i class="fa fa-facebook" aria-hidden="true"></i></a></li> 
                        @elseif (strpos($scl->link, 'linkedin') !== false)
                        <li><a class="linkedin" href="{{ $scl->link }}" target="_blank">
                            <i class="fa fa-linkedin" aria-hidden="true"></i></a></li> 
                        @elseif (strpos($scl->link, 'twitter') !== false)
                        <li><a class="twitter" href="{{ $scl->link }}" target="_blank">
                            <i class="fa fa-twitter" aria-hidden="true"></i></a></li> 
                        @elseif (strpos($scl->link, 'youtube') !== false)
                        <li><a class="youtube" href="{{ $scl->link }}" target="_blank">
                            <i class="fa fa-play" aria-hidden="true"></i></a></li> 
                        @elseif (strpos($scl->link, 'instagram') !== false)
                        <li><a class="facebook" href="{{ $scl->link }}" target="_blank">
                            <i class="fa fa-instagram" aria-hidden="true"></i></a></li> 
                        @elseif (strpos($scl->link, 'google') !== false)
                        <li><a class="youtube" href="{{ $scl->link }}" target="_blank">
                            <i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li> 
                        @else
                        <li><a class="facebook" href="{{ $scl->link }}" target="_blank">
                            <i class="{{ $scl->icon_css_class }}" aria-hidden="true"></i></a></li>
                        </li>
                        @endif
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
    <div class="col-sm-4">
        <div class="search">
            <form name="msrcFRM" method="GET" action="{{ route('globalSearch', array('lng' => 'en')) }}">
                <input type="text" class="form-control" placeholder="Search" name="q" value="@if(isset($_GET['q'])){{ $_GET['q'] }}@endif" required="required">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>
</div>