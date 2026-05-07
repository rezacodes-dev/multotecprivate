@if( isset($allVideo) )
<div style="max-height: 300px; overflow-y: auto;">
	@foreach( $allVideo as $allVids)
		@if( $allVids->video_type == '1' )
		<div class="col-md-6 vbox" id="vidBox_{{ $allVids->id }}" style="margin-top: 6px;">
			<div>	
				<iframe width="100%" height="200" src="https://www.youtube.com/embed/{{ $allVids->video_link }}"></iframe>
				<div style="text-align: right;">
					<input type="button" class="vidAddToArrayBtn btn btn-xs btn-primary" value="Add" id="{{ $allVids->id }}">
					<input type="hidden" id="vid_name_{{ $allVids->id }}" value="{{ $allVids->name }}">
					<input type="hidden" id="vid_title_{{ $allVids->id }}" value="{{ $allVids->title }}">
					<input type="hidden" id="vid_caption_{{ $allVids->id }}" value="{{ $allVids->video_caption }}">
				</div>
			</div>
		</div>
		@endif
	@endforeach 
</div>
<div class="row">
	<div class="col-md-12">
		{{ $allVideo->appends(request()->query())->links() }}
	</div>
</div>
{{--!! $allImages->render() !!--}}
@endif