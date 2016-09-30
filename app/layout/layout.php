<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Encuentra a tu empleado</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="stylesheet" href="/app/layout/css/bootstrap.min.css">
        <link href="/app/layout/css/style.css" rel="stylesheet" type="text/css">

        <script src="/app/layout/javascript/jquery-3.1.0.min.js"></script>
        <script src="/app/layout/javascript/bootstrap.min.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-light bg-faded">
            <a class="navbar-brand" href="#"></a>
            <ul class="nav navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/jobseeker/edit">Publica tu Currículum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/company/list">Encuentra nuevos empleados</a>
                </li>
            </ul>

            <ul class="nav navbar-nav pull-xs-right">
                <?php $session = ResManager::getSession(); ?>
                <?php if ($session->isLogged()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="
                        <?= ($session->getProp('user')->getProp('type') == 0) ? '/jobseeker/edit' : '/company/edit' ?>
                         ">Hola <?= $session->getProp('user')->getProp('name') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/logout">Salir</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/register">Registro</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div id="maincontainer">
            <?php if (!empty($view_content)): ?>
                <?= $view_content ?>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Error:</strong> Página no encontrada.
                </div>
            <?php endif; ?>
        </div>

        <footer class="footer" id="main">
            <div class="container text-right">
                Hecho con el framework del TFG de Borja Vides
            </div>
        </footer>
    </body>
</html>
