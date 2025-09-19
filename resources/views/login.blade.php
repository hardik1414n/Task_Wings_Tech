<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container">
        @if (auth()->user())
            <h1>{{ auth()->user()->name }}</h1>
        @endif
        <div class="card mt-5">
            <div class="card-body">
                <form id="loginForm">
                    <span class="text-danger" id="invalid"></span>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" name="email" id="email"
                            aria-describedby="emailHelp" placeholder="Enter email">
                        <span class="text-danger" id="emailError"></span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Password">
                        <span class="text-danger" id="passwordError"></span>
                    </div>

                    <button type="button" id="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var token = localStorage.getItem('auth_token');
            if (token != null) {
                window.location.href='{{ route('dashboard') }}';
            }
            $('#submit').on('click', function() {
                    var email = $('#email').val();
                    var password = $('#password').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ url('api/login_check') }}",
                        method: "POST",
                        data: {
                            email: email,
                            password: password
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.errors) {
                                if (data.errors.email) {
                                    $('#emailError').text(data.errors.email);
                                }
                                if (data.errors.password) {
                                    $('#passwordError').text(data.errors.password);
                                }
                                if (data.errors.invalid) {
                                    $('#invalid').text(data.errors.invalid);
                                }
                            } else {
                                localStorage.setItem('auth_token', data.token)
                                window.location.href = "{{ route('dashboard') }}";
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }

                    });
                });
        });
    </script>
</body>

</html>
