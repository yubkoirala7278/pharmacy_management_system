@extends('admin.layouts.master')

@section('content')
Here will be the content
@endsection

@push('script')
    <script>
        alert(1)
    </script>
@endpush