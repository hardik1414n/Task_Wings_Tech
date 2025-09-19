<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Task')</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('employee.create') }}">Employee Create</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('employee.index') }}">Employees</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" style="cursor: pointer;" id="logout">Logout</a>
                </li>

            </ul>

        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
    <script>
        $(document).ready(function() {

            $('#logout').on('click', function(e) {
                e.preventDefault();
                var token = localStorage.getItem('auth_token');
                if (token) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token + '',
                        }
                    });

                    $.ajax({
                        url: "{{ url('api/logout') }}",
                        type: "POST",
                        success: function(data) {
                            if (data == true) {
                                localStorage.removeItem('auth_token');
                                window.location.href = "{{ route('login') }}";
                            } else {
                                console.log(data);
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });


        });
    </script>
    @stack('scripts')
</body>

</html>
