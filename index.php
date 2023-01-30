<link rel="stylesheet" href="estilos/login.css">
<?php
require_once 'html/base.php';
?>

<?php
if (isset($_SESSION['flash_message_carga'])) {
    echo ('<div class="alert alert-success" role="alert">' . $_SESSION['flash_message_carga'] . '</div>');
}

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

$mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');
if ($mysqli->connect_errno != null) {
    echo "Error número $mysqli->connect_errno conectando a la base de datos.<br>Mensaje: $mysqli->connect_error.";
    exit();
}

$sql = 'SELECT id, nombre, descripcion, idcategoria, precio, stock, imgpath FROM productos WHERE stock > 0';
$result = $mysqli->query($sql);

$cant = 0;
echo ('<div class="container-flex text-center" id="conteinerItems"><br/>');
if ($result != false && $result != null) {
    $producto = $result->fetch_array(MYSQLI_ASSOC);
    while ($producto != null && $producto['stock'] > 0) {
        $cantprod = "prodID=" . $producto['id'] . ";cantAnt =" . $producto['stock'] . "; $('#cantidad').attr('max'," . $producto['stock'] . ");$('#cantidad').val(1)";
        if ($cant % 3 == 0)
            echo '<div class="d-flex justify-content-around row mb-1">';

        echo ('<div class="col-12 col-lg-3 mb-5 mb-sm-4" >
            <div class="card text-center" ><div class="d-flex justify-content-center my-3">');
        if ($producto['imgpath'] != "" && $producto['imgpath'] != null)
            echo ('<img src="' . $producto['imgpath'] . '" class="card-img-top" alt="...">');
        else
            echo ('<img src="img/missing.png" class="card-img-top" alt="...">');
        echo ('</div><div class="card-body">
            <h5 class="card-title" id="nombreprod">' . $producto['nombre'] . '</h5>
            <p class="card-text">' . $producto['descripcion'] . '</p>
            </div>
            </div>
            <div class="card-footer text-muted">
            <div class="row">
            <div class="col-5"><p>Precio: $' . $producto['precio'] . '</p></div>
            <div class="col-7"><p>Disponibles: ' . $producto['stock'] . ' unidad/es</p></div>
            <div class="row">
            <div class="col-3"></div>
            <div class="col-6 btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#mailModal" onclick="carganombre(\'' . $producto['nombre'] . '\')"> Consultar </div>
            <div class="col-3"></div>
            </div>
            </div>
            </div>
            </div>');
        $cant++;
        if ($cant % 3 == 0)
            echo '</div>';
        $producto = $result->fetch_array(MYSQLI_ASSOC);
    }
}

if ($cant == 0)
    echo '<h5>Aún no se han cargado productos</h5>';
echo ('</div>');

$mysqli->close();
?>

<!-- modal consulta mail -->
<div class="modal fade" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="labelModalMail" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelModalMail">Enviar mail</h5>
                <button type="button" style="background-color: none" class="btn btn-sm" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                <label style="color:black; float:left" for="email">Ingrese su email: </label>
                <input class="form-control w-100 mx-0" type="tel" name="email" id="email"
                    placeholder="ejemplo@email.com" required>
                <br />
                <textarea class="form-control mx-0 w-100" id="cuerpo" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Enviar Mail</button>
            </div>
        </div>
    </div>
</div>


<?php require_once 'html/footer.php'; ?>
<script>
    var cantAnt = 0;
    var prodID = '';
    jQuery(document).ready(function ($) {
        $('#table_id').DataTable();
    });
    function carganombre(nombre) {
        $("#cuerpo").html('Hola! Quiero comprar tu producto "' + nombre + '" que vi en el catálogo online.');
    }
</script>