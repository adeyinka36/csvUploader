<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&family=Roboto:wght@300&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
   @vite('resources/js/app.js')
</head>
<body>
    <div id="app">
        <App></App>
    </div>
</body>
</html>
