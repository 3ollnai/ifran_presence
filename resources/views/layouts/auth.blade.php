<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Connexion')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md bg-blue-200 shadow-md rounded-lg p-6 bg-blue-200">
        @yield('content')
    </div>
</body>
</html>
