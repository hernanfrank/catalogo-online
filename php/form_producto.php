<?php
require_once '../html/base.php';

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
$random_string = generate_string();
$_SESSION['token'] = $random_string;


if (isset($_GET['productoID']) && is_numeric(($_GET['productoID']))) {
    $edit = true;
    $idprod = intval($_GET['productoID']);

    if ($idprod == null || $idprod == false) {
        unset($_GET['pruductoID']);
        $edit = false;
    } else {

        $mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');
        if ($mysqli->connect_errno != null) {
            echo "Error número $mysqli->connect_errno conectando a la base de datos.<br>Mensaje: $mysqli->connect_error.";
            exit();
        }

        $sql = 'SELECT id, nombre, descripcion, idcategoria, precio, stock, imgpath FROM productos WHERE idvendedor = ' . $_SESSION['id'] . ' AND id = ' . $idprod;
        $result = $mysqli->query($sql);

        $producto = $result->fetch_array(MYSQLI_ASSOC);
        if ($producto == false || $producto == null) {
            $edit = false;
            unset($_GET['productoID']);
        }
    }
} else {
    $edit = false;
}
?>
<link rel="stylesheet" href="../estilos/login.css">
<div class="container" id="contenido">
    <a href="../html/inicio.php" style="color:white; text-decoration: none;"><button class="btn btn-secondary mb-3">←
            Volver</button></a>

    <div class="card mb-3">
        <div class="card-header" id="headerCargarP">
            Cargar Producto
        </div>

        <?php
        if (isset($_SESSION['flash_message'])) {
            echo ('<div class="alert alert-danger" role="alert">' . $_SESSION['flash_message'] . '</div>');
            unset($_SESSION['flash_message']);
        }
        ?>

        <div class="card-body">
            <form id="formulario" enctype="multipart/form-data" action="<?php if ($edit) {
            echo 'edit_productos.php';
        } else {
            echo 'carga_productos.php';
        } ?>" method="post">
                <input type="hidden" name="token" value="<?php echo $random_string; ?>">

                <?php if ($edit)
                echo '<input type="hidden" name="productoID" value="' . $idprod . '">
                ' ?>

                <div class="row mb-3 d-flex justify-content-center">
                    <img id="imgcargada" class="col-lg-8 col d-flex justify-content-center responsiveimg" src="<?php if ($edit) {
                    echo $producto['imgpath'];
                } else {
                    echo '../img/missing.png';
                } ?>" alt="Imagen del producto">
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-control btn btn-outline-secondary">Cargar imagen</label>
                    <input type="file" class="d-none" name="imagen" id="imagen" accept="image/png,image/jpg,image/jpeg">
                </div>

                <input id="nombre" type="text" placeholder="Nombre" name="nombre" class="form-control mb-3" value="<?php if ($edit)
                echo $producto['nombre'] ?>" required>

                <input id="desc" type="text" placeholder="Descripción" name="descripcion" class="form-control  mb-1"
                    value="<?php if ($edit)
                    echo $producto['descripcion'] ?>" required>

                <select name="idcategoria" id="categoria" form="formulario">
                    <option value="1" <?php if ($edit && $producto['idcategoria'] == 1)
                    echo 'selected' ?>>
                        Electrodomestico</option>
                    <option value="2" <?php if ($edit && $producto['idcategoria'] == 2)
                    echo 'selected' ?>>Ferreteria
                    </option>
                    <option value="3" <?php if ($edit && $producto['idcategoria'] == 3)
                    echo 'selected' ?>>Alimentos
                    </option>
                    <option value="4" <?php if ($edit && $producto['idcategoria'] == 4)
                    echo 'selected' ?>>Jardin
                    </option>
                </select>

                <input id="precio" type="number" placeholder="Precio unitario " name="precio"
                    class="form-control mb-3 mt-1" value="<?php if ($edit)
                    echo $producto['precio'] ?>" required>

                <input id="stock" type="number" min="1" placeholder="Cantidad" name="stock" class="form-control mb-3"
                    value="<?php if ($edit)
                    echo $producto['stock'] ?>" required>

                <div>
                    <input type="submit" class="btn btn-primary" id="botonIngresar" value="Guardar">
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(() => {
            $("#imagen").change(
                (evt) => {
                    evt.preventDefault();
                    var tgt = evt.target || window.event.srcElement,
                        files = tgt.files;

                    // FileReader support
                    if (FileReader && files && files.length) {
                        var fr = new FileReader();
                        fr.onload = function () {
                            $("#imgcargada").attr('src', fr.result);
                        }
                        fr.readAsDataURL(files[0]);
                    }

                    // Not supported
                    else {
                        $("#imgcargada").notify("Imagen cargada correctamente", {
                            className: "success",
                            position: "bottom",
                            autoHide: true,
                            autoHideDelay: 2000
                        });
                    }
                }
            );
        });
    </script>
    <?php require_once '../html/footer.php'; ?>