@extends($extendsName, $extendsData)

@foreach ($sections as $section)
    @section($section['name'])
    {{ $section['value'] }}
    @endsection
@endforeach

@foreach ($stacks as $stack)
    @push($stack['name'])
    {{ $stack['value'] }}
    @endpush
@endforeach

@section($sectionName)
@datatable($datatable)
@endsection
