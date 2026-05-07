@if( isset($resFiles) )

<div class="row">
    <div class="col-sm-4"><h2 style="margin-top:20px;">Latest Additions</h2></div>
    <div class="col-sm-4">
        @if( isset($resFiles) )
        <nav aria-label="Page navigation" style="text-align: center;">
          <ul class="pagination">
            @if( $resFiles->previousPageUrl() != '' )
            <li class="page-item">
                <a class="page-link" href="{{ $resFiles->previousPageUrl() }}">Prev</a>
            </li>
            @endif
            @if( $resFiles->nextPageUrl() != '' )
            <li class="page-item">
                <a class="page-link" href="{{ $resFiles->nextPageUrl() }}">Next</a>
            </li>
            @endif
          </ul>
        </nav>
        @endif
    </div>
    <div class="col-sm-4">
        <div class="search">
            <input type="text" id="artksrcVal" class="form-control" placeholder="Search">
            <button type="button" id="artksrc" value=""><i class="fa fa-search" aria-hidden="true"></i></button>
        </div>    
    </div>
</div>

<div class="additions_block">
    @php $break = 1; @endphp
    @if( isset($resFiles) && count($resFiles) > 0)
        @foreach( $resFiles as $v )
            @if( isset($v->FileIds) && count($v->FileIds) > 0 )
            <div class="block_thumb">
                <div class="image" style="width: 180px;">
                    @if( isset($v) && isset($v->ImageIds) && count($v->ImageIds) > 0 )
                    @php $i = 0; @endphp
                    @foreach( $v->ImageIds as $imgs )
                    @if( $imgs->image_type == 'MAIN_IMAGE' )
                      @if( isset($imgs->imageInfo) && $i == 0 )
                      <img src="{{ asset('public/uploads/files/media_images/'.$imgs->imageInfo->image) }}" style="width: 100%;" title="{{ $imgs->title }}" alt="{{ $imgs->alt_tag }}" caption="{{ $imgs->caption }}">
                      @php $i++; @endphp
                      @endif
                    @endif
                    @endforeach
                  @endif
                  @if($v->publish_date != '')<p>{{ date('d F Y', strtotime( $v->publish_date ) ) }}</p>@endif
                </div>
                <div class="content_sec">
                    <h5>{{ $v->name }}</h5>
                    <p>
                        {!! str_limit(html_entity_decode( $v->description ), 125, '...') !!}
                        
                        @if( isset($v) && isset($v->FileIds) && count($v->FileIds) > 0 )
                            @php $i = 0; @endphp
                            @foreach( $v->FileIds as $fils )
                                @if( $fils->file_type == 'MAIN_FILE' )
                                    @if( isset($fils->fileInfo) && $i == 0 )
                                <a href="{{ asset('public/uploads/files/media_files/'. $fils->fileInfo->file) }}" target="_blank">
                                Read More</a>
                                    @php $i++; @endphp
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </p>
                </div>
            </div>
            @if( $break % 2 == 0 )
            <div class="clearfix"></div>
            @endif
            @php $break++; @endphp
            @endif
        @endforeach
    @else
        <h5 style="font-weight: normal; color: #ccc;">Sorry! No Record Founds</h5>
    @endif
    <div class="clearfix"></div>
</div>

@endif