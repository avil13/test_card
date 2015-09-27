<!DOCTYPE html>
<html lang="en" ng-app="APP">

<head>
    <meta charset="UTF-8">
    <title>Кошельки</title>
    <link rel="stylesheet" href="/content/bwr/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/content/bwr/sweetalert/dist/sweetalert.css">
    <link rel="stylesheet" href="/content/bwr/angucomplete-alt/angucomplete-alt.css">
    <style>
    .data-piker td:not(.text-muted) {
        cursor: pointer;
    }
    </style>
</head>

<body>
    <input type="hidden" value="{{ csrf_token() }}" id="csrf">
    <div class="container-fluid">
        <div class="row">
            {{-- --}}
            <div class="navbar navbar-default">
                <div class="col-xs-offset-11 col-xs-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="{!! route('logout') !!}">
                                <i class="glyphicon glyphicon-off"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- --}}
            <div class="view" ui-view></div>
            <div class="clearfix"></div>
        </div>
    </div>
    <script src="/content/js/script.js"></script>
</body>

</html>