<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Notes App') }} — @yield('title', 'Home')</title>
    <style>
        /* ===== NEOBRUTALISM BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: #FFFBF0;
            font-family: 'Arial Black', 'Arial Bold', Arial, sans-serif;
            color: #000;
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: #FFE500;
            border-bottom: 3px solid #000;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 900;
            text-decoration: none;
            color: #000;
            letter-spacing: -1px;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 16px;
            font-weight: 700;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-block;
            padding: 8px 18px;
            font-weight: 900;
            font-size: 0.875rem;
            border: 2px solid #000;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.1s, box-shadow 0.1s;
            box-shadow: 4px 4px 0px #000;
            background: #fff;
            color: #000;
            border-radius: 0;
            line-height: 1.4;
        }
        .btn:hover {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px #000;
        }
        .btn-yellow { background: #FFE500; }
        .btn-red    { background: #FF4D4D; color: #fff; }
        .btn-black  { background: #000; color: #fff; }
        .btn-sm     { padding: 5px 12px; font-size: 0.78rem; }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        /* ===== CARD ===== */
        .card {
            background: #fff;
            border: 3px solid #000;
            box-shadow: 6px 6px 0px #000;
            padding: 28px;
            margin-bottom: 24px;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 900;
            margin-bottom: 16px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        /* ===== FORM ===== */
        .form-group { margin-bottom: 14px; }
        .form-label {
            display: block;
            font-weight: 900;
            font-size: 0.85rem;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 2px solid #000;
            background: #FFFBF0;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 700;
            border-radius: 0;
            outline: none;
            transition: box-shadow 0.1s;
        }
        .form-control:focus {
            box-shadow: 4px 4px 0px #000;
        }
        textarea.form-control { resize: vertical; min-height: 90px; }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border: 2px solid #000;
            font-weight: 700;
            margin-bottom: 20px;
            box-shadow: 4px 4px 0px #000;
        }
        .alert-success { background: #B8FFB8; }
        .alert-error   { background: #FFB8B8; }

        /* ===== TAGS ===== */
        .tag-badge {
            display: inline-block;
            padding: 3px 10px;
            border: 2px solid #000;
            font-size: 0.75rem;
            font-weight: 900;
            background: #FFE500;
            margin: 2px;
            text-decoration: none;
            color: #000;
            transition: transform 0.1s, box-shadow 0.1s;
            box-shadow: 2px 2px 0px #000;
        }
        .tag-badge:hover {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0px #000;
        }
        .tag-badge.active {
            background: #000;
            color: #FFE500;
        }

        /* ===== NOTE GRID ===== */
        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .note-card {
            background: #fff;
            border: 3px solid #000;
            box-shadow: 5px 5px 0px #000;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .note-card-title {
            font-size: 1rem;
            font-weight: 900;
            word-break: break-word;
        }
        .note-card-body {
            font-size: 0.875rem;
            font-weight: 500;
            color: #333;
            flex: 1;
            word-break: break-word;
        }
        .note-card-tags { display: flex; flex-wrap: wrap; gap: 4px; }
        .note-card-actions { display: flex; gap: 8px; margin-top: 6px; }
        .note-public-badge {
            display: inline-block;
            padding: 2px 8px;
            border: 2px solid #000;
            font-size: 0.7rem;
            font-weight: 900;
            background: #B8FFB8;
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .filter-label {
            font-weight: 900;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        /* ===== MODAL ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: #FFFBF0;
            border: 3px solid #000;
            box-shadow: 8px 8px 0px #000;
            padding: 28px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-title {
            font-size: 1.1rem;
            font-weight: 900;
            margin-bottom: 18px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        /* ===== CHECKBOX ===== */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid #000;
            cursor: pointer;
            accent-color: #FFE500;
        }
        .checkbox-group label {
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            gap: 6px;
            margin-top: 28px;
            flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 6px 14px;
            border: 2px solid #000;
            font-weight: 900;
            font-size: 0.85rem;
            text-decoration: none;
            color: #000;
            box-shadow: 3px 3px 0px #000;
            transition: transform 0.1s, box-shadow 0.1s;
        }
        .pagination a:hover {
            transform: translate(3px, 3px);
            box-shadow: 0px 0px 0px #000;
        }
        .pagination .active-page {
            background: #FFE500;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            border: 3px dashed #000;
            font-weight: 700;
            color: #555;
        }
        .empty-state p { font-size: 1.1rem; margin-top: 8px; }

        /* ===== ERRORS ===== */
        .error-text {
            color: #CC0000;
            font-size: 0.8rem;
            font-weight: 700;
            margin-top: 4px;
        }
    </style>
</head>
<body>

@auth
<nav class="navbar">
    <a href="{{ route('notes.index') }}" class="navbar-brand">Notes App</a>
    <div class="navbar-user">
        <span>👤 {{ Auth::user()->username }}</span>
        <form action="{{ route('logout') }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn btn-black btn-sm">Logout</button>
        </form>
    </div>
</nav>
@endauth

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    @yield('content')
</div>

@yield('scripts')
</body>
</html>
