<style>
    .slick-slide .inner {
        margin: 0 15px;
    }

    .clearfix {
        clear: both;
    }

    .green-block,
    .red-block {
        width: 350px;
        animation: scroll 10s linear infinite;
        padding: 2px 10px;
        color: #fff;
        font-family: Arial, Helvetica, sans-serif;
    }

    .green-block {
        background-color: #238657;
    }

    .red-block {
        background-color: #c7232c;
    }

    .neme-block,
    .price-block {
        float: left;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 0px;
        line-height: 20px;
    }

    .neme-block {
        width: 40%;
        display: flex;
        flex-wrap: nowrap;
    }

    .price-block {
        text-align: right;
        width:25%;
    }

    .neme-block img {
        width: 10px !important;
        height: 10px !important;
        margin-top: 5px;
        margin-left: 10px;
    }

    .percent-block1 {
        float: left;
        width: 50%;
        font-size: 12px;
    }

    .percent-block2 {
        float: right;
        width: 15%;
        font-size: 14px;
        text-align: right;
        line-height:20px;
    }

    .percent-block3 {
        float: right;
        width: 20%;
        font-size: 14px;
        text-align: right;
        line-height:20px;
    }

    .list-block {
        font-size: 11px;
        margin-top: 0;
    }

    .commodities_ticker {
        margin-top: 10px;
    }

    .upadate {
        text-align: right;
        color: #333;
        font-size: 12px;
        padding: 10px 10px 0 0;
        font-style: italic;
    }
</style>
<link href='https://cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css' rel='stylesheet'>


<section class="commodities_ticker">
    <div class="slick marquee">
        @php

        @endphp
        @if(isset($commodities) && !empty($commodities))
        @foreach ($commodities as $commodity)
            @php
                $commodity_price = 1 / $commodity->current_price;
                $current_price = 1/$commodity->current_price;
                $previous_price = 1/$commodity->previous_price;

                $diff_price = $current_price - $previous_price;

                $percentage_difference = ($diff_price / $previous_price) * 100;

                $percentage_difference = abs($percentage_difference);

                $diff_price_formatted = number_format((float) $diff_price, 3, '.', '');
                $percentage_difference_formatted = number_format((float) $percentage_difference, 3, '.', '');

                $imgsrc = url('public/images/arrow-up.png');
                $blockColor = 'green';
                $diff_type_sign = '+';
                $total_diff_price_formatted = $diff_type_sign . ' ' . $diff_price_formatted;

                if ($diff_price < 0) {
                    $diff_type_sign = '-';
                    $imgsrc = url('public/images/arrow-down.png');
                    $blockColor = 'red';

                    $total_diff_price_formatted = $diff_price_formatted;

                    $percentage_difference_formatted = number_format((float) $percentage_difference, 3, '.', '');

                    
                }
                // echo $total_diff_price_formatted;
            @endphp
            <div class="slick-slide">

                <div class="inner">
                    <div class="{{ $blockColor }}-block">
                        <div class="neme-block">{{ $commodity->metals }}* <img src="{{ $imgsrc }}" /></div>
                        <div class="percent-block3">{{ $diff_type_sign }} {{ $percentage_difference_formatted }}%</div>
                        <div class="percent-block2">{{ $total_diff_price_formatted }}</div>
                        <div class="price-block">$ {{ number_format((float) $commodity_price, 3, '.', '') }}</div>

                        {{-- <div class="percent-block1"></div> --}}

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        @endforeach
        @else
        @endif


    </div>
    <div class="upadate">LAST | {{ date('H.i.s a', strtotime($commodity->updated_at)) }} EST (updated twice a day)</div>
</section>
