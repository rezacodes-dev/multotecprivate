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


    <a href="{{ route('delWbUser', array('id' => $pc->id)) }}" onclick="return confirm('Sure To Delete This Item ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>

  </td>

  <td>{{ ucfirst($pc->name) }}</td>


  <td>{{ ucfirst($pc->email_id) }}</td>
  <td>{{ ucfirst($pc->contact_no) }}</td>
  <td>{{ ucfirst($pc->company) }}</td>
  <td>{{ ucfirst($pc->country) }}</td>
  <td> {{ date('m-d-Y', strtotime($pc->created_at)) }} </td>
</tr>
@php $sl++; @endphp
@empty
@endforelse
@endif
