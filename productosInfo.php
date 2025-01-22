<?php
// Inicia la sesión al comienzo del archivo
session_start();
require "config/database.php";
$db = conectarDB();

$errores = [];

$id = $_GET['id'] ?? null;

if (!empty($id)) {
    $id_producto = filter_var($id, FILTER_VALIDATE_INT);

    if ($id_producto === false) {
        $errores[] = 'ID de producto no válido';
    } else {
        $consulta = "SELECT * FROM productos WHERE id = ${id_producto}";
        $resultado = mysqli_query($db, $consulta);
        $producto = mysqli_fetch_assoc($resultado);

        $query = "SELECT * FROM talles";
        $res = mysqli_query($db, $query);

        if (!$producto) {
            $errores[] = 'Producto no encontrado';
        }
    }
} else {
    $errores[] = 'ID de producto no proporcionado';
}

// Verificar si se presiona el botón "Agregar al carrito"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que el usuario esté logueado
    if (!isset($_SESSION['email'])) {
        // Redirigir a login y pasar como referencia el producto actual
        header("Location: login.php?redirect=productosInfo.php?id={$id}");
        exit;
    }

    // Si el usuario está logueado, procesa la lógica de agregar al carrito aquí
    $producto_id = $_POST['producto_id'] ?? null;
    $usuario_id = $_SESSION['id'] ?? null;
    $talle = $_POST['size'] ?? null;
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);

    if ($producto_id && $usuario_id && $talle && $cantidad) {
        // Insertar en la tabla carrito
        $query = "INSERT INTO carrito (producto_id, usuario_id, cantidad, talle) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('iiis', $producto_id, $usuario_id, $cantidad, $talle);

        if ($stmt->execute()) {
            header("Location: index.php"); // Redirigir al carrito
            exit;
        } else {
            $errores[] = "Error al agregar al carrito.";
        }
    } else {
        $errores[] = "Todos los campos son obligatorios.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coco Verde</title>

    <!-- Estilo CSS -->
    <link rel="stylesheet" href="/css/style.css" />

    <!-- Iconos -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />

    <!-- js -->
    <script defer src="/js/menuHamburguesa.js"></script>
    <script defer src="/js/productoInfo.js"></script>
    <script defer src="/js/productos.js"></script>
  </head>
  <body class="body-productosInfo">
    <!-- Navbar -->

    <header>
      <nav class="navbar">
        <div>
          <button class="abrir" id="iconoAbrir">
            <i class="bx bx-menu"></i>
          </button>
          <button class="cerrar" id="iconoCerrar">
            <p>X</p>
          </button>
        </div>
        <div class="logo">
          <img src="/image/logo navbar.png" alt="Logo Coco Verde" />
        </div>
        <div class="nav-links" id="nav-links">
          <a class="links" href="/">Inicio</a>
          <a class="links" href="index.html#productos">Productos</a>
          <a class="links" href="index.html#nosotros">Nosotros</a>
          <a class="links" href="index.html#proximamente">Próximamente</a>
        </div>
        <div class="cart-icon"><i class="bx bx-cart"></i></div>
      </nav>
    </header>

    <div class="container">
      <div class="product">
        <!-- Sección de la imagen -->
        <div class="image-section">
          <img
            id="mainImage"
            src="/image/<?php echo $producto['video']; ?>.png"
            alt="Remera Blanca"
            class="main-image"
          />
          <div class="thumbnail-container">
            <img
              src="/image/<?php echo $producto['image']; ?>.png"
              alt="Remera Blanca"
              class="thumbnail selected"
              id="imagen1"
            />
            <img
              src="/image/<?php echo $producto['image2']; ?>.png"
              alt="Buzo"
              class="thumbnail"
              id="imagen2"
            />
          </div>
        </div>

        <!-- Sección de detalles -->
        <div class="details-section">
          <div class="header">
            <!-- Este es el h2 que cambiará -->
            <h2 id="nombreProducto" class="name"><?php echo $producto['nombre']; ?></h2>
            <p id="precioProducto" class="price">$<?php echo $producto['precio']; ?></p>
            <p><?php echo $producto['descripcion']; ?></p>
          </div>

          <form action="" method="post">
    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>" />
    <div class="options">
        <div class="size">
            <p>Talle:</p>
            <?php while ($talle = mysqli_fetch_assoc($res)) : ?>
                <label>
                    <input type="radio" name="size" value="<?php echo $talle['talle']; ?>" required />
                    <?php echo $talle['talle']; ?>
                </label>
            <?php endwhile; ?>
        </div>

        <div class="quantity">
            <p>Cantidad:</p>
            <input id="cantidad" name="cantidad" type="number" value="1" min="1" max="4" required />
        </div>
    </div>

    <button type="submit" class="add-to-cart">AGREGAR AL CARRITO</button>
</form>

        </div>
      </div>
    </div>
    <!-- Pie de página -->
    <footer>
      <p>&copy; 2024 Coco Verde. Todos los derechos reservados.</p>
    </footer>
  </body>
</html>