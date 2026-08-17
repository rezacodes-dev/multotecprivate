@if(isset($home_banners) && count($home_banners) > 0)
<section class="banner">
    <div class="owl-carousel">
        @foreach($home_banners as $hb)
            @if(isset($hb->BannerImages) && !empty($hb->BannerImages) && $hb->BannerImages->status == '1')
            <div class="item">
                <div class="innerslide">
                    @if($hb->BannerImages->title == 'Electra Mining Africa 2026')
                            <a href="https://tickets.tixsa.co.za/event/electra-mining-africa-2026-visitor-registration/tag/multotec"
                            target="_blank"
                            rel="noopener noreferrer">
                        @endif

                        <img src="{{ asset('public/uploads/files/media_images/' . $hb->BannerImages->image) }}"
                            alt="{{ $hb->BannerImages->alt_title }}"
                            title="{{ $hb->BannerImages->title }}"
                            fetchpriority="high"
                            loading="eager"
                        />

                        @if($hb->BannerImages->title == 'Electra Mining Africa 2026')
                            </a>
                        @endif
                    {{-- <img src="{{ asset('public/uploads/files/media_images/' . $hb->BannerImages->image) }}"
                         alt="{{ $hb->BannerImages->alt_title }}"
                         title="{{ $hb->BannerImages->title }}"
                         fetchpriority="high"
                         loading="eager"
                    /> --}}
                    @if(!empty($hb->BannerImages->caption))
                        <div class="caption">
                            <p>{{ $hb->BannerImages->caption }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        @endforeach
    </div>
</section>
@endif
