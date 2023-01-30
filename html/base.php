<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo Online</title>
    <link rel="shortcut icon"
        href="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcT5jDbqiQncO1PBdN9aMtDR5xHBe4RiGLoo7NLb2L9Zc3cqxpoY"
        type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/tp/estilos/login.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/rowreorder/1.3.1/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-sha512@0.8.0/src/sha512.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
</head>
<header>

    <?php
    //seteo maximo tiempo de vida de sesión a 10 minutos (60 seg * 10)
    ini_set('session.gc_maxlifetime', 600);
    session_start();

    $loggedin = isset($_SESSION['usuario']);

    //si se quiere ingresar a otra pagina que no sea la principal y no se inició sesión como usuario,
    //va al inicio
    /* if (!$loggedin && !in_array($_SERVER['REQUEST_URI'], array("/tp/php/login.php", "/tp/index.php", "/tp/php/registro.php"))) {
    header("Location: /tp/index.php");
    } else {
    if (!in_array($_SERVER['REQUEST_URI'], array("/tp/html/inicio.php", "/tp/php/form_producto.php"))) {
    header("Location: /tp/html/inicio.php");
    }
    } */
    ?>

    <nav id="containerHeader" class="container-flex mx-0 px-2">
        <div class="row">
            <div class="col-7 mr-0 pr-0">
                <a href="<?php if ($loggedin)
                    echo "/tp/html/inicio.php";
                else
                    echo "/tp/index.php"; ?>" style="text-decoration: none !important;">
                    <h2 id="titulobase" class="d-inline">Catálogo Online</h2>
                </a>
            </div>
            <?php
            if (isset($_SESSION['usuario'])) {
                echo '<div class="col me-2" >
                <div class="dropdown" style="float:right !important">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownmb" data-bs-toggle="dropdown" aria-expanded="false">
                    <p class="d-none d-sm-inline">' . $_SESSION['usuario'] . '</p><img src="https://secure.gravatar.com/avatar/28571dc6f966b96928f7b4cab6081fcc?s=35&amp;d=mm" class="ms-2 me-1 rounded-circle" role="presentation" aria-bs-hidden="true" width="35" height="35">
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="dropdownmb">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalAjustes">Ajustes de cuenta</a></li>
                    <li><a class="dropdown-item" href="/tp/php/cerrarSesion.php">Cerrar Sesión</a></li>
                </ul>
            </div>
            </div>';
            } else if ($_SERVER['REQUEST_URI'] != '/tp/php/login.php') {

                echo ('<div class="col me-2">
                    <div id="btn_iniciarSesion" style="margin-top:-0.2rem">
                        <a href="/tp/php/login.php" class="btn btn-sm btn-light">Iniciar Sesión</a>
                        <br/>
                        <small id="lregistro" style="font-size:0.8rem;margin-left:0.75rem"><a style="color:#f8f9fa  !important" href="/tp/php/registro.php">O registrarse</a></small>
                    </div>
                   </div>');
            }
            ?>
        </div>
    </nav>
</header>

<body class="container-flex">

    <!-- modal ajustes de cuenta -->
    <div class="modal fade" id="modalAjustes" tabindex="-1" role="dialog" aria-labelledby="labelModalAjustes"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelModalAjustes">Ajustes de cuenta</h5>
                    <button type="button" style="background-color: none" class="btn btn-sm" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="d-flex justify-content-center">Cambiar contraseña</h6>
                    <label class="small" style="color:black; float:left" for="email">Nueva contraseña: </label>
                    <input class="form-control w-100 mx-0" type="password" name="newpass" id="newpass">
                    <label class="small" style="color:black; float:left" for="newpass2">Repetir contraseña: </label>
                    <input class="mb-4 form-control w-100 mx-0" type="password" name="newpass2" id="newpass2">
                    <label class="small" style="color:black; float:left" for="pass">Contraseña actual: </label>
                    <input class="form-control w-100 mx-0" type="password" name="pass" id="pass">
                    <br />
                    <div class="row">
                        <div class="col"></div>
                        <div class="col btn btn-warning">Cambiar</div>
                        <div class="col"></div>
                    </div>
                    <br /><br />
                    <hr class="mt-1 mb-2">
                    <h6 class="d-flex justify-content-center">Eliminar cuenta</h6>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalelimina"
                            data-bs-dismiss="modal">Eliminar
                        </div>
                        <div class="col"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal elimina cuenta -->
    <div class="modal fade" id="modalelimina" tabindex="-1" role="dialog" aria-labelledby="labelModalElimina"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelModalElimina">¿Eliminar cuenta?</h5>
                    <button type="button" style="background-color: none" class="btn btn-sm" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="small" style="color:black; float:left" for="pass">Ingrese su contraseña: </label>
                    <input class="form-control w-100 mx-0" type="password" name="pass" id="pass">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>