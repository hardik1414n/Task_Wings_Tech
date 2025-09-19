@extends('layout.master')
@section('title','Dashboard')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Employees</h2>

    <table class="table table-bordered" id="employeesTable">
        <thead>
        <tr>
            <th>Profile</th>
            <th>Code</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    var token = localStorage.getItem('auth_token');
    if(!token)
    {
        window.location.href="{{ route('login') }}";
    }

    $('#employeesTable').DataTable({
    ajax: {
        url: '/api/employees',
        headers: { 'Authorization': 'Bearer ' + token },
        dataSrc: ''
    },
    columns: [
        { data: 'profile_image', render: d => `<img src="${d}" width="50">` },
        { data: 'emp_code' },
        { data: 'name' },
        { data: 'gender' },
        { data: 'email' },
        { data: 'mobile_number' },
        { data: 'address' },
        { data: 'type' }
    ]
    });
});
</script>
@endpush
