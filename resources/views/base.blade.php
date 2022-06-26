<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div id="app">
        <router-view></router-view>
    </div>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
