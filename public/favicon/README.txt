Laravel (Blade) usage (put in resources/views/layouts/app.blade.php <head>):

<!-- Animated favicon (works in many browsers). Keep the static .ico too for max compatibility. -->
<link rel="icon" href="{{ asset('favicon/favicon.ico') }}" sizes="any">
<link rel="icon" type="image/gif" href="{{ asset('favicon/favicon-32x32.gif') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
<link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

(Optional) If a browser doesn’t animate the GIF favicon, it will fall back to the .ico/.png above.

Where to place files:
public/favicon/   (copy everything from this zip into that folder)
