@include('front_end.includes.header')
<section id="wrap">
    <header id="site_header" class="site_header1">
        <div class="container">
           @include('front_end.includes.top_header_bar')
           @include('front_end.includes.header_menu_bar')
        </div>
    </header>
    <section id="wrapper">
        @yield('page_content')
    </section>
    @include('front_end.includes.footer_upper')
</section>
@include('front_end.includes.footer')

