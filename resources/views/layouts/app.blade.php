<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Inventaris Laboratorium Informatika')</title>
    <!-- Pico.css Minimalist Framework -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        :root {
            --pico-font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --pico-primary: #0284c7;
            --pico-primary-hover: #0369a1;
        }
        body {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .header-title {
            margin-bottom: 0.5rem;
        }
        .header-subtitle {
            color: var(--pico-muted-color);
            margin-bottom: 2rem;
            font-weight: 400;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-available { background: #dcfce7; color: #166534; }
        .badge-in-use { background: #fef08a; color: #854d0e; }
        .badge-damaged { background: #fee2e2; color: #991b1b; }
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: var(--pico-border-radius);
            margin-bottom: 2rem;
        }
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        .actions a, .actions button {
            width: auto;
            margin: 0;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        .btn-delete {
            background-color: #ef4444;
            border-color: #ef4444;
        }
        .btn-delete:hover {
            background-color: #dc2626;
            border-color: #dc2626;
        }
        form.inline {
            display: inline;
            margin: 0;
        }
        .search-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .search-form input {
            margin: 0;
            max-width: 300px;
        }
        .search-form button {
            width: auto;
            margin: 0;
        }
        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: -1rem;
            margin-bottom: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <main class="container">
        <header>
            <h1 class="header-title">Inventaris Lab Informatika</h1>
            <p class="header-subtitle">Sistem Manajemen Data Barang Laboratorium Informatika Kampus</p>
        </header>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
        
        <footer style="margin-top: 4rem; text-align: center; color: var(--pico-muted-color); font-size: 0.9em;">
            <p>&copy; {{ date('Y') }} Tugas Praktikum Pemrograman Web - SILO LAB</p>
        </footer>
    </main>
</body>
</html>
