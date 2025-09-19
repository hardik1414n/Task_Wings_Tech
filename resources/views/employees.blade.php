@extends('layout.master')
@section('title', 'Dashboard')

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

    <div class="modal fade" id="employeeModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="employeeForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="employee_id">
                        <div class="mb-3">
                            <label>Code</label>
                            <input type="text" name="emp_code" class="form-control">
                            <span class="text-danger error-text emp_code_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                            <span class="text-danger error-text email_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control">
                            <span class="text-danger error-text mobile_number_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Gender</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderMale"
                                    value="Male">
                                <label class="form-check-label" for="genderMale">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                    value="Female">
                                <label class="form-check-label" for="genderFemale">Female</label>
                            </div>
                            <span class="text-danger error-text gender_error"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Type</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="experience"
                                    value="experience">
                                <label class="form-check-label" for="experience">Experience</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="fresher"
                                    value="fresher">
                                <label class="form-check-label" for="fresher">Fresher</label>
                            </div>
                            <span class="text-danger error-text type_error"></span>

                            <div id="experienceFields" style="display:none; margin-top:15px;">
                                <input type="text" class="form-control" name="c_name" placeholder="Company Name">
                                <input type="text" class="form-control" name="designation" placeholder="Designation">
                                <input type="date" class="form-control" name="j_date" placeholder="Joining Date">
                                <input type="date" class="form-control" name="e_date" placeholder="End Date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-8">
                                    <label>Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control">
                                </div>
                                <div class="col-4">
                                    <img src="" id="profile_image" name="profile_image" width="50px"
                                        height="50px">
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = "{{ route('login') }}";
            }

            var table = $('#employeesTable').DataTable({
                ajax: {
                    url: '/api/employees',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    dataSrc: ''
                },
                columns: [{
                        data: 'profile_image',
                        render: d => `<img src="${d}" width="50">`
                    },
                    {
                        data: 'emp_code'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'mobile_number'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'id',
                        render: (data) => `
                    <button class="btn btn-sm btn-primary" onclick="editEmployee(${data})">Edit</button>
                    <button class="btn btn-sm btn-danger delete" data-id="${data}" ">Delete</button>
                `
                    }
                ]
            });

            window.editEmployee = function(id) {
                fetch('/api/employees_edit/' + id, {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(res => res.json())
                    .then(emp => {
                        $('#employee_id').val(emp.id);
                        $('input[name=emp_code]').val(emp.emp_code);
                        $('input[name=name]').val(emp.name);
                        $('input[name=email]').val(emp.email);
                        $('input[name=mobile_number]').val(emp.mobile_number);
                        $('input[name=address]').val(emp.address);
                        $('input[name="gender"][value="' + emp.gender + '"]').prop('checked', true);
                        $('input[name="type"][value="' + emp.type + '"]').prop('checked', true);
                        if (emp.type === 'experience') {
                            $('#experienceFields').show();

                            // Fill values
                            $('input[name="c_name"]').val(emp.c_name);
                            $('input[name="designation"]').val(emp.designation);
                            $('input[name="j_date"]').val(emp.j_date);
                            $('input[name="e_date"]').val(emp.e_date);
                        }
                        $('input[name=c_name]').val(emp.c_name);
                        $('input[name=designation]').val(emp.designation);
                        $('input[name=j_date]').val(emp.j_date);
                        $('input[name=e_date]').val(emp.e_date);
                        $('#profile_image').attr('src', emp.profile_image);
                        $('#employeeModal').modal('show');
                    });
            }

            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        url: '/api/employee_delete/' + id, // API route
                        type: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON?.message || 'Something went wrong!');
                        }
                    });
                }
            });

            $('input[name="type"]').on('change', function() {
                if ($(this).val() === 'experience') {
                    $('#experienceFields').show();
                } else {
                    $('#experienceFields').hide();
                }
            });

            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();

                var employeeId = $('#employee_id').val();
                var formData = new FormData(this);
                console.log(formData.get('name'));
                $.ajax({
                    url: '/api/employee_update/' + employeeId,
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message);
                        $('#employeeForm')[0].reset();
                        $('#experienceFields').hide();
                        $('#profile_image').attr('src', '');
                        $('.error-text').text('');

                        // Optional: refresh DataTable
                        if (typeof table !== 'undefined') {
                            table.ajax.reload(null, false);
                        }

                        // Close modal if using Bootstrap
                        $('#employeeModal').modal('hide');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '_error').text(value[0]);
                            });
                        } else {
                            alert(xhr.responseJSON?.message || 'Something went wrong!');
                        }
                    }
                });
            });
        });
    </script>
@endpush
