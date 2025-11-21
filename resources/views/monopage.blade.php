<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Monopage SPA') }}</title>

    <!-- CSRF token for Axios / forms -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Compiled CSS (Laravel Mix) -->
    @if (file_exists(public_path(mix('css/app.css'))))
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @else
        <!-- Fallback if mix file not present during dev -->
        <link rel="stylesheet" href="/css/app.css">
    @endif

    <style>
      /* simple v-cloak fallback to hide unmounted template */
      [v-cloak] { display: none; }
      /* optional small reset for body so dev pages look fine */
      body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    </style>
</head>
<body>
    <!--
      Blade view that acts as the single entry point for the SPA.
      Vue will mount to #app and handle client-side routing.
    -->

    @php
        // Prepare auth payload for the frontend
        $user_auth_data = [
            'isLoggedin' => Auth::check(),
            'user' => Auth::check() ? Auth::user() : null,
        ];
        // Encode as JSON then Base64 so it can be safely passed into JS.
        $user_auth_base64 = base64_encode(json_encode($user_auth_data));
    @endphp

    <!-- Make the auth payload available to the frontend -->
    <script>
        // window.__USER_AUTH__ is a Base64-encoded JSON string
        // Frontend: const auth = JSON.parse(atob(window.__USER_AUTH__));
        window.__USER_AUTH__ = "{{ $user_auth_base64 }}";
    </script>

    <!-- Root element for Vue to mount -->
    <div id="app" v-cloak>
        <!-- Optionally add a fallback skeleton or loading indicator here -->
        <div id="spa-loading" aria-hidden="true">
            Loading...
        </div>
    </div>

    <!-- Compiled JS (Laravel Mix) -->
    @if (file_exists(public_path(mix('js/app.js'))))
        <script src="{{ mix('js/app.js') }}" defer></script>
    @else
        <!-- Fallback during local development if Mix file isn't present -->
        <script src="/js/app.js" defer></script>
    @endif

    <!-- Optional: small script to remove the loading fallback once Vue mounts.
         Your main app.js can also do this after mount. -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // If Vue hasn't mounted in a short while, keep the simple loading text visible.
        // Vue should replace the contents of #app when it mounts.
        setTimeout(function () {
          const loading = document.getElementById('spa-loading');
          if (loading && document.getElementById('app').childElementCount === 1) {
            // keep minimal message; Vue will replace on mount
            loading.textContent = 'Starting application...';
          }
        }, 300);
      });
    </script>

    <noscript>
      <strong>JavaScript is required</strong> to use this application. Please enable JavaScript in your browser.
    </noscript>
</body>
</html>
