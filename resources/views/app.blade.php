<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Vortex</title>

        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />

        <script src="{{ mix('/js/app.js') }}" defer></script>

        @routes
    </head>
    <body class="flex flex-col h-full min-h-screen antialiased text-[#5e6278] transition duration-200 bg-[#f3f6f9]">
        @inertia
    </body>
</html>
