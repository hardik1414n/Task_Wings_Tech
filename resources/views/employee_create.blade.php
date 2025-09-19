@extends('layout.master')
@section('title', 'Dashboard')

@section('content')
    <div class="card mt-5">
        <div class="card-header">
            <h3>Employee Form</h3>
        </div>
        <div class="card-body">
            <form id="employeeForm" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group">
                    <label for="emp_code">Employee Code</label>
                    <input type="number" class="form-control" name="emp_code" id="emp_code" placeholder="Enter Code">
                    <span class="text-danger" id="emp_codeError"></span>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                    <span class="text-danger" id="nameError"></span>
                </div>
                <div class="form-group mt-3">
                    <label for="email">email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="email">
                    <span class="text-danger" id="emailError"></span>
                </div>
                <div class="form-group mt-3">
                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" class="form-control" name="mobile_number" id="mobile_number"
                        placeholder="mobile_number">
                    <span class="text-danger" id="mobile_numberError"></span>
                </div>
                <div class="form-group mt-3">
                    <label for="address">Address</label>
                    <textarea class="form-control" name="address" id="address"></textarea>
                    <span class="text-danger" id="addressError"></span>
                </div>

                <br>
                <div class="form-group">
                    <label for="">Gender : </label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Male">
                        <label class="form-check-label" for="inlineRadio1">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Female">
                        <label class="form-check-label" for="inlineRadio2">Female</label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="profile_image">Employee Image</label>
                    <input type="file" class="form-control" name="profile_image" id="profile_image" placeholder="profile_image">
                    <span class="text-danger" id="profile_imageError"></span>
                </div>

                <br>
                <div class="form-group">
                    <label for="">Type : </label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="experience" value="experience">
                        <label class="form-check-label" for="experience">Experience</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="fresher" value="fresher">
                        <label class="form-check-label" for="fresher">Fresher</label>
                    </div>
                    <span class="text-danger" id="typeError"></span>
                </div>

                <div class="row" id="experience_fiels">
                    <div class="col-3">
                        <div class="form-group mt-3">
                            <label for="c_name">Company Name</label>
                            <input type="text" class="form-control" name="c_name" id="c_name"
                                placeholder="Company Name">
                            <span class="text-danger" id="c_nameError"></span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group mt-3">
                            <label for="designation">Designation</label>
                            <input type="text" class="form-control" name="designation" id="designation"
                                placeholder="designation">
                            <span class="text-danger" id="designationError"></span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group mt-3">
                            <label for="j_date">Join Date</label>
                            <input type="date" class="form-control" name="j_date" id="j_date"
                                placeholder="j_date">
                            <span class="text-danger" id="j_dateError"></span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group mt-3">
                            <label for="e_date">End Date</label>
                            <input type="date" class="form-control" name="e_date" id="e_date"
                                placeholder="e_date">
                            <span class="text-danger" id="e_dateError"></span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="is_experience" id="is_experience" value="false">

                <button type="submit" id="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#experience_fiels').hide(200);
            var token = localStorage.getItem('auth_token');

            if (!token) {
                window.location.href = "{{ route('login') }}";
            }
            $('input[name="type"]').on('change', function() {
                var value = $(this).val();
                if (value == "experience") {
                    $('#experience_fiels').show(200);
                    $('#is_experience').val(true);
                } else {
                    $('#experience_fiels').hide(200);
                    $('#is_experience').val(false);
                }
            });

            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ url('api/employee/create') }}",
                    type: "POST",
                    headers: {
                        'Authorization': 'Bearer ' + token + '',
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        if (data.errors) {
                            console.log(data.errors);
                            if (data.errors.address) {
                                $('#addressError').text(data.errors.address);
                            }
                            if (data.errors.email) {
                                $('#emailError').text(data.errors.email);
                            }
                            if (data.errors.emp_code) {
                                $('#emp_codeError').text(data.errors.emp_code);
                            }if (data.errors.gender) {
                                $('#genderError').text(data.errors.gender);
                            }
                            if (data.errors.profile_image) {
                                $('#profile_imageError').text(data.errors.profile_image);
                            }
                            if (data.errors.name) {
                                $('#nameError').text(data.errors.name);
                            }
                            if (data.errors.type) {
                                $('#typeError').text(data.errors.type);
                            }
                            if (data.errors.c_name) {
                                $('#c_nameError').text(data.errors.c_name);
                            }
                            if (data.errors.mobile_number) {
                                $('#mobile_numberError').text(data.errors.mobile_number);
                            }
                            if (data.errors.designation) {
                                $('#designationError').text(data.errors.designation);
                            }
                            if (data.errors.e_date) {
                                $('#e_dateError').text(data.errors.e_date);
                            }
                            if (data.errors.j_date) {
                                $('#j_dateError').text(data.errors.j_date);
                            }

                        } else {
                            if(data.status == true)
                            {
                                $('#employeeForm')[0].reset();
                                alert(data.message);
                            }
                        }
                    },
                    error: function(xhr) {
                        // handle validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let html = '<ul style="color:red">';
                            $.each(errors, function(key, value) {
                                html += '<li>' + value[0] + '</li>';
                            });
                            html += '</ul>';
                            $('#response').html(html);
                        } else {
                            $('#response').html(
                                '<span style="color:red">Something went wrong.</span>');
                        }
                        console.log(xhr);
                    }

                });
            });
        });
    </script>
@endpush
