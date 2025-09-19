@extends('layout.master')
@section('title','Dashboard')

@section('content')
    <h1>Dashboard</h1>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    var token = localStorage.getItem('auth_token');
    if(!token)
    {
        window.location.href="{{ route('login') }}";
    }else{

    }
});
</script>
@endpush
