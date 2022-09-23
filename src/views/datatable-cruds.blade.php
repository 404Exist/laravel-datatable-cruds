@extends($extends, $extendsData)
@section($section)
<div id="datatablecruds">
<data-list :data="{{ json_encode($datatable) }}" />
</div>
<script src="{{ config('datatablecruds.script_file_url') }}" defer></script>
@endsection
