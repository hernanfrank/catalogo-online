<link rel="stylesheet" href="../estilos/login.css">
<?php
require_once '../html/base.php';

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
$random_string = generate_string();
$_SESSION['token'] = $random_string;

?>
<div class="container-flex">
    <div class="row">
        <div class="col-1 col-md-3"></div>
        <div class="col-10 col-md-3 me-0 pe-0" style="margin-top:-1.5rem">
            <br>
            <br>
            <div style="background-color:#1c3aa9; border-radius: 0;" class="card">
                <div class="card-header">
                    Registro
                </div>
                <div class="card-body" style="padding-top: 0 !important;">
                    <form id="formulario" action="procesoRegistro.php" method="post">
                        <div class="form-group mt-3 mb-2">
                            <label for="user">Usuario</label>
                            <input type="text" class="form-control" id="user" name="user" placeholder="Usuario">
                        </div>
                        <div class="form-group mt-3 mb-2">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="ejemplo@email.com">
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Contraseña">
                            <small style="text-align: justify;color:white; font-size:0.7rem; line-height:0.7rem;">*Al
                                menos 8 caracteres, una mayúscula, una minúscula, y un número.</small>
                        </div>
                        <div class="form-group mb-2">
                            <label for="password2">Repetir contraseña</label>
                            <input type="password" class="form-control" id="password2" name="password2"
                                placeholder="Contraseña">
                        </div>
                        <div class="mt-2 mb-2 form-check">
                            <input type="checkbox" class="form-check-input" id="terminos">
                            <label class="form-check-label" for="terminos">Acepto los terminos y condiciones.</label>
                        </div>

                        <div class="mb-2 row d-flex justify-content-center">
                            <img style="margin-left:0 !important" class="captcha-img d-inline" src="../php/rdnimg.php">
                            <input class="captcha d-inline" type="text" id="captcha" name="captcha"
                                placeholder="Ingrese el codigo" value="">
                            <input name="token" type="hidden" value="<?php echo $random_string ?>">
                        </div>

                        <div class="row">
                            <button type="submit" class="btn btn-light">Registrarse</button>
                        </div>


                    </form>
                </div>
            </div>


        </div>
        <div class="col-1 col-md-3 mt-4 ms-0 ps-0 d-none d-sm-inline">
            <img width="415" height="501" src="../img/imagenRegistro.jpg" alt="Imagen registración">
        </div>
    </div>
</div>
<script>
    document.getElementById("lregistro").remove();
    const form = document.getElementById('formulario');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let user = document.getElementById("user").value;
        let email = document.getElementById("email").value;
        let pwd = document.getElementById("password").value;
        let pwd2 = document.getElementById("password2").value;
        let term = document.getElementById("terminos").checked;
        let captcha = document.getElementById("captcha").checked;

        if (user == "") {
            $("#user").notify("Ingrese nombre de usuario.", {
                className: "warn",
                position: "top",
                autoHide: true,
                autoHideDelay: 2000
            });
            return;
        }
        if (email == "") {
            $("#email").notify("Ingrese un email.", {
                className: "warn",
                position: "top",
                autoHide: true,
                autoHideDelay: 2000
            });
            return;
        }
        if (pwd == "") {
            $("#password").notify("Ingrese una contraseña.", {
                className: "warn",
                position: "top",
                autoHide: true,
                autoHideDelay: 2000
            });
            return;
        }
        if (pwd2 == "") {
            $("#password2").notify("Repita la contraseña.", {
                className: "warn",
                position: "top",
                autoHide: true,
                autoHideDelay: 2000
            });
            return;
        }

        /*
        Validar password segura
            Minimo 8 caracteres {8,}
            Al menos 1 mayuscula (?=.*?[A-Z])
            Al menos 1 minuscula (?=.*?[a-z])
            Al menos 1 numero (?=.*?[0-9])
            Para pedir al menos 1 caracter especial agregar (?=.*?[#?!@$%^&*-])
        */
        var passwordRegex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/;
        if (!passwordRegex.test(pwd)) {
            $("#password").notify("Contraseña insegura.", {
                className: "warn",
                position: "top",
                autoHide: true,
                autoHideDelay: 2000
            });
            return;
        }

        if (pwd != pwd2) {
            $("#password2").notify("Las contraseñas no son iguales.", {
                className: "warn",
                position: "top",
                autoHide: true,
                autoHideDelay: 2000
            });
            return;
        }

        if (term != true) {
            $("#terminos").notify("Debe aceptar los terminos.", {
                className: "warn",
                position: "top",
                autoHide: true,
                autoHideDelay: 2000
            });
            return;
        }

        form.submit();
    });

    <?php

    if (isset($_SESSION['flash_message'])) {
        if (!isset($_SESSION['usuariorep']))
            $_SESSION['usuariorep'] = null;
        echo '$(document).ready(()=>{
            $("#user").val("' . $_SESSION['usuariorep'] . '");
                $.notify("' . $_SESSION['flash_message'] . '", {
                className: "error",
                position: "top",
                autoHide: true,
                autoHideDelay: 3000
                });
        });';
        unset($_SESSION['flash_message']);
    }

    ?>
</script>

<?php require_once '../html/footer.php'; ?>