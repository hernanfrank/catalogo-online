<?php
require_once '../html/base.php';

if (isset($_SESSION['usuario'])) {
    header("Location: /tp/html/inicio.php");
}
?>
<link rel="stylesheet" href="../estilos/login.css">

<div class="container" id="contenido">
    <!-- bootstrap dos card-->
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-8">
                <img src="../img/imagenLogin.jpg" class="img-fluid" alt="...">
            </div>
            <div class="col-md-4">
                <div class="card-header">
                    Iniciar Sesión
                </div>
                <div class="card-body">
                    <?php
                    //genera token
                    function generate_string()
                    {
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $random_string = '';
                        for ($i = 0; $i < 20; $i++) {
                            $random_character = $permitted_chars[mt_rand(0, strlen($permitted_chars) - 1)];
                            $random_string .= $random_character;
                        }

                        return $random_string;
                    }
                    $random_string =  generate_string();
                    $_SESSION['token'] = $random_string;
                    if (isset($_SESSION['flash_message'])) {
                        if(isset($_SESSION['status']) && $_SESSION['status'] == 'ok'){
                            echo ('<div style="font-size:0.9rem" class="mb-0 alert alert-success" role="alert">' . $_SESSION['flash_message'] . '</div>');
                        }else{
                            echo ('<div style="font-size:0.9rem" class="mb-0 alert alert-danger" role="alert">' . $_SESSION['flash_message'] . '</div>');
                        }
                        unset($_SESSION['flash_message']);
                        unset($_SESSION['status']);
                        unset($_SESSION['usuario']);
                    }
                    ?>

                    <form id="formulario" action="../php/procesoLogin.php" method="post">
                        <input type="hidden" name="token" value="<?php echo $random_string ?>">

                        <label for="usuario" class="form-label"></label>
                        <input id="usuario" type="text" placeholder="Usuario" name="usuario" class="form-control" required>

                        <div class="mb-4">
                            <label for="contra" class="form-label"></label>
                            <input id="contra" type="password" placeholder="Contraseña" name="pass" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <img class="captcha-img" src="../php/rdnimg.php">
                            <input class="captcha d-inline" type="text" name="captcha" placeholder="Ingrese el codigo" value="" required>
                        </div>
                        <div class="mb-2">
                            <input type="submit" class="btn btn-primary" id="botonIngresar" value="Ingresar"><br/>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a style="color:white" href="registro.php">O registrarse</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 
            <div class="card mt-5">
                <h5 class="card-header">Iniciar Sesión</h5>
                <div class="card-body">-->
    <?php
    if (isset($_SESSION['flash_message'])) {
        if(isset($_SESSION['status']) && $_SESSION['status'] == 'ok'){
            echo ('<div class="h6 alert alert-success" role="alert">' . $_SESSION['flash_message'] . '</div>');
        }else{
            echo ('<div class="h6 alert alert-danger" role="alert">' . $_SESSION['flash_message'] . '</div>');
        }
        unset($_SESSION['flash_message']);
        unset($_SESSION['status']);
        unset($_SESSION['usuario']);
    }
    ?>
    <?php require_once '../html/footer.php';  ?>