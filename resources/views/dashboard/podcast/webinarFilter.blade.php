    @if(isset($allProdCats))
      @php $sl = 1; @endphp
      @forelse($allProdCats as $pc)
        @php
          $lngcode = getLngCode($pc->language_id);
        @endphp
      <tr>
        <td>
          {{ $sl }}
          <input type="checkbox" name="ids[]" class="ckbs" value="{{ $pc->id }}">
        </td>
        <td>

          @php $url=route('editWb', array('id' => $pc->id))  @endphp

          <a href="{{ route('editWb', array('id' => $pc->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
          <a href="{{ route('delWb', array('id' => $pc->id)) }}" onclick="return confirm('Sure To Delete This Item ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
          <a title="View Attended Users" href="{{ route('viewWbUser', array('id' => $pc->id)) }}"><i class="fa fa-2x fa-eye base-green"></i></a>
         
         </td>
        <td>
          @if($pc->status == '1')
            <a href="{{ route('acInac') }}?id={{ $pc->id }}&val=2&tab=webinar"> 
              <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
            </a>
          @endif
          @if($pc->status == '2')
            <a href="{{ route('acInac') }}?id={{ $pc->id }}&val=1&tab=webinar"> 
              <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
            </a> 
          @endif
        </td>
        <td>{{ ucfirst($pc->name) }}</td>


        <!-- <td></td>
        <td></td> -->
        <td>{{ ucfirst($pc->url) }}</td>
        <td>{{($pc->conversion) }}/{{($pc->hit) }}</td>
       
        <td> {{ date('m-d-Y', strtotime($pc->created_at)) }} </td>
        <td> {{ date('m-d-Y', strtotime($pc->updated_at)) }} </td>
      </tr>
      @php $sl++; @endphp
      @empty
      @endforelse
    @endif
