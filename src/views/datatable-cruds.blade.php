@extends($extends, $extendsData)
@section($section)
<div id="datatablecruds">
<data-list :data="{{ json_encode($datatable) }}" />
</div>
<script src="/_datatablecrudsminfindjs" defer></script>
@endsection
