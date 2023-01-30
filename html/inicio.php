<?php
require_once 'base.php';
?>

<link rel="stylesheet" href="../estilos/login.css">
<?php
if (isset($_SESSION['mensaje_correcto'])) {
    echo ('<div class="alert alert-success" role="alert">' . $_SESSION['mensaje_correcto'] . '</div>');
    unset($_SESSION['mensaje_correcto']);
}
if (isset($_SESSION['flash_message_edicion'])) {
    echo ('<div class="alert alert-success" role="alert">' . $_SESSION['flash_message_edicion'] . '</div>');
    unset($_SESSION['flash_message_edicion']);
}
if (isset($_SESSION['flash_message_carga'])) {
    echo ('<div class="alert alert-success" role="alert">' . $_SESSION['flash_message_carga'] . '</div>');
    unset($_SESSION['flash_message_carga']);
}
if (isset($_SESSION['captcha'])) {
    unset($_SESSION['captcha']);
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
if (isset($_SESSION['flash_message'])) {
    echo ('<div style="font-size:0.9rem" class="mb-0 alert alert-danger" role="alert">' . $_SESSION['flash_message'] . '</div>');
    unset($_SESSION['flash_message']);
    unset($_SESSION['usuario']);
}

try {
    if (isset($_SESSION['id'])) {
        $mysqli = new mysqli('127.0.0.1', 'root', '', 'tienda');

        if ($mysqli->connect_errno != null) {
            //echo "Error número $mysqli->connect_errno conectando a la base de datos.<br>Mensaje: $mysqli->connect_error.";
            echo "Ha ocurrido un error.";
            exit();
        }
        $iduser = $_SESSION['id'];
        $sql = "SELECT p.id, p.nombre, p.descripcion, c.nombre as categoria, p.precio, p.stock FROM productos p
                INNER JOIN categorias c ON p.idcategoria = c.id
                WHERE p.idvendedor = $iduser;";
        $result = $mysqli->query($sql);

        if ($result != false && $result != null)
            $producto = $result->fetch_array(MYSQLI_ASSOC);
        else
            $producto = null;

        echo ('<div class="container" id="mp">
    <h1>Mis productos</h1>
    <a type="button" href="../php/form_producto.php" id="agregarProducto" class="btn btn-info">Agregar producto</a>
</div>
');

        echo ('<table id="table_id"  class="display responsive nowrap" width="100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Tipo</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        ');

        while ($producto != null) {
            echo ('<tr>
            <td>' . $producto['nombre'] . '</td>');
            echo ('<td>' . $producto['descripcion'] . '</td>');
            echo ('<td>' . $producto['categoria'] . '</td>');
            echo ('<td>' . $producto['precio'] . '</td>');
            echo ('<td>' . $producto['stock'] . '</td>');
            echo ('<td>
                <div class="formularios">

                    <form action="../php/form_producto.php" method="get">
                        <input type="hidden" id="productoID" name="productoID" value="' . $producto['id'] . '">
                        <button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="EDITAR"><i class="fas fa-pen"></i></button>
                    </form>
                    <button style="margin-left:1rem;" onclick="prodID=' . $producto['id'] . '" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar" title="ELIMINAR"><i class="fas fa-trash-alt"></i></button>
                </div>

        </tr>');
            $producto = $result->fetch_array(MYSQLI_ASSOC);
        }
        echo ('
    </tbody>
</table>');
    }
    if (isset($mysqli) && $mysqli != null)
        $mysqli->close();
} catch (Exception $e) {
    error_log($e);
    echo "Ocurrió un error.";
    die();
}

?>

<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminacion"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="../php/deleteProducto.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">¿Desea eliminar el producto?</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="success" class="modal-body d-none">
                    <div class="alert alert-success">Producto eliminado correctamente.</div>
                </div>
                <div class="modal-footer">
                    <button id="eliminarprod" type="button" class="btn btn-danger" title="Eliminar"
                        value="">Eliminar</button>
                    <button onclick="prodID=''" type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        title="Cerrar">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>
<script>
    var prodID;
    jQuery(document).ready(function ($) {
        $('#table_id').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 10001, targets: 4 },
                { responsivePriority: 1, targets: -1 }
            ],
            language: {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron registros.",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No se encontraron registros.",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primera",
                    "last": "Ultima",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });

    $("#eliminarprod").click(
        () => {
            $.ajax({
                url: "../php/deleteProducto.php",
                method: "POST",
                data: {
                    productoID: prodID,
                    token: "<?php echo $random_string ?>"
                },
                beforeSend: () => {
                    $("#eliminarprod").remove();
                },
                success: () => {
                    prodID = "";
                    $("#success").removeClass('d-none');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            });
        }
    );
</script>