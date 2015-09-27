<!doctype html>
<html lang="en" ng-app="APP">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title> @yield('title', 'Авторизация') </title>

        <link rel="shortcut icon" type="image/x-icon" href="favicon.png">

        <link rel="stylesheet" href="/content/bwr/bootstrap/dist/css/bootstrap.min.css">

        <style>
        .row{margin-top:40px;}
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                 @yield('content')
            </div>
        </div>

    </body>
</html>

