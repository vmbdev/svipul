<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Encuentra a tu empleado</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="stylesheet" href="/app/layout/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">

        <link href="/app/layout/css/style.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <nav class="navbar navbar-light bg-faded">
            <a class="navbar-brand" href="#"></a>
            <ul class="nav navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Publica tu Currículum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Encuentra nuevos empleados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Acerca de...</a>
                </li>
            </ul>

            <ul class="nav navbar-nav pull-xs-right">
                <li class="nav-item">
                    <a class="nav-link" href="#">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Registro</a>
                </li>
            </ul>
        </nav>

        <?= $view_content ?>

        <footer class="footer" id="main">
            <div class="container text-right">
                Made with Svipul Framework - 2016
            </div>
        </footer>

        <script src="/app/layout/javascript/jquery.min.js"></script>
        <script src="/app/layout/javascript/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
    </body>
</html>
