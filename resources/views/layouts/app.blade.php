<!DOCTYPE html>
<html lang="en">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Navigation -->
        <nav class="bg-light border-end p-3" style="width: 250px; min-height: 100vh;">
            <h5 class="text-primary">Admin Portal</h5>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a href="{{ route('rental','rental') }}" class="nav-link text-dark">Rental</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('rental','enquiry') }}" class="nav-link text-dark">Enquiry</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div class="flex-grow-1">
            <!-- Navigation Bar -->
            

            <!-- Page Heading -->
            @isset($header)
                <div class="bg-white shadow-sm p-3 mb-3">
                    <div class="container">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <main class="container">
            @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
