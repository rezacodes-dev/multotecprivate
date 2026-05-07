@if( isset($home_banners) )
<section class="banner">
    <div class="owl-carousel">
        @foreach( $home_banners as $hb )
            @if( isset($hb->BannerImages) && !empty($hb->BannerImages) && $hb->BannerImages->status == '1' )
            <div class="item">
                <div class="innerslide">
                    <img src="{{ asset('public/uploads/files/media_images/' . $hb->BannerImages->image) }}"  alt="{{ $hb->BannerImages->alt_title }}" title="{{ $hb->BannerImages->title }}"/>
                    <div class="caption">
                        <p>{{ $hb->BannerImages->caption }}</p>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</section>
@endif