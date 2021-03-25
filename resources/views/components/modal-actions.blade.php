@php
$btnType = $action === 'create' ? 'btn-primary' : 'btn-warning';
@endphp

<div class="d-flex justify-content-end align-items-center">
  <button class="btn btn-light mr-3" data-dismiss="modal">Close</button>
  <button type="submit" class="btn {{ $btnType }}">{{ ucwords($action) }}</button>
</div>
