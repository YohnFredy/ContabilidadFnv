<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
    (function() {
        // Check if any preference is set. If not, default to dark.
        const key = 'flux-appearance';
        if (!localStorage.getItem(key)) {
            localStorage.setItem(key, 'dark');
        }

        // Also force the visual immediately if it's supposed to be dark (system or manual)
        // But since we just defaulted to dark, we assume dark.
        if (localStorage.getItem(key) === 'dark') {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

@fluxAppearance
