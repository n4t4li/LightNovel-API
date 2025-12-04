<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Monopage SPA') }}</title>

    <!-- CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Laravel Mix CSS & JS -->
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    
    <!-- Load webpack dependencies in correct order -->
    <script src="{{ mix('/js/manifest.js') }}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>

    <style>
      [v-cloak] { display: none; }
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
    <!-- Make the auth payload available to the frontend -->
    <script>
        window.__USER_AUTH__ = "{{ $user_auth_base64 }}";
    </script>

    <!-- Root element for Vue to mount -->
    <div id="app" v-cloak>
        <div id="spa-loading" aria-hidden="true">
            Loading...
        </div>
    </div>

    <!-- Optional: remove loading fallback once Vue mounts -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
          const loading = document.getElementById('spa-loading');
          if (loading && document.getElementById('app').childElementCount === 1) {
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
