<?php
// Base de datos
require "config/database.php";
$db = conectarDB();

$consult = "SELECT * FROM productos";
$res = mysqli_query($db, $consult);

session_start(); // Asegúrate de iniciar la sesión

// Validar si el usuario ha iniciado sesión
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    // Obtén el usuario_id basado en el correo electrónico
$email_usuario = $_SESSION['email'];
$queryUsuario = "SELECT id FROM usuario WHERE email = ?";
$stmtUsuario = $db->prepare($queryUsuario);
$stmtUsuario->bind_param('s', $email_usuario);
$stmtUsuario->execute();
$resultadoUsuario = $stmtUsuario->get_result(); 
$usuario = $resultadoUsuario->fetch_assoc();
$usuario_id = $usuario['id'];

// Usa el usuario_id para obtener los productos del carrito
$queryCarrito = "
SELECT carrito.id AS carrito_id, productos.nombre, productos.precio, carrito.cantidad, carrito.talle
FROM carrito
INNER JOIN productos ON carrito.producto_id = productos.id
WHERE carrito.usuario_id = ?
";

$stmtCarrito = $db->prepare($queryCarrito);
$stmtCarrito->bind_param('i', $usuario_id); // Cambia 's' por 'i' si usuario_id es un entero
$stmtCarrito->execute();
$resultado = $stmtCarrito->get_result();


} else {
    // Si no está logueado, inicializa $resultado como nulo
    $resultado = null;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);

  if ($id) {
      $query_delete = "DELETE FROM carrito WHERE id = ?";
      $stmt_delete = $db->prepare($query_delete);
      $stmt_delete->bind_param('i', $id);

      if ($stmt_delete->execute()) {
          echo "Producto eliminado exitosamente.";
      } else {
          echo "Error al eliminar el producto.";
      }

      header("Location: /");
      exit();
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
    <link rel="stylesheet" href="./css/style.css" />

    <!-- Iconos -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />

    <!-- js -->
    <script defer src="./JavaScript/menuHamburguesa.js"></script>
    <script defer src="./JavaScript/carritoClick.js"></script>
  </head>
  <body>
    <!-- Carrito Click -->

    <div id="carrito" class="carrito">
      <div class="carrito-header">
        <h1>Carrito de compras</h1>
        <span id="x" class="close">×</span>
      </div>
      <div>
      <?php $subtotal = 0; ?>
      <?php if ($resultado && $resultado->num_rows > 0): ?>
      <?php while($carrito = $resultado->fetch_assoc()): 
      $subtotal += $carrito['precio'] * $carrito['cantidad'];  
      ?>
        <div class="productos productoDiv" data-producto-id="<?php echo $carrito['id']; ?>">
          <img src="/image/remera 1.png" alt="Producto" />
          <div class="info">
            <h2><?php echo $carrito['nombre']; ?> (<?php echo $carrito['talle']; ?>)</h2>
            <div class="cantidad">
              <button class="botonRestar">-</button>
              <span class="cantidadCarrito"><?php echo $carrito['cantidad']; ?></span>
              <button class="botonSumar">+</button>
            </div>
          </div>
          <div class="precio">$<?php echo $carrito['precio'] * $carrito['cantidad']; ?></div>
          <form action="index.php" method="post">
          <input type="hidden" name="id" value="<?php echo $carrito['carrito_id']; ?>" />
            <button class="eliminar basuraIcono" type="submit">🗑</button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No tienes productos en tu carrito.</p> 
    <?php endif; ?>
      </div>
      <hr />
      <div class="subtotal">
        <p>SUBTOTAL (sin envío):</p>
        <p>$<?php echo $subtotal; ?></p>
      </div>
      <a href="/inicioCompra"
        ><button class="comprar">Iniciar compra</button></a
      >
    </div>

    <!-- Notificacion Carrito -->

    <div class="cart-container">
      <div class="item">
        <div class="item-details">
          <img src="shirt-cap.jpg" alt="Combo Sweet Tee X Washed Cap" />
          <div>
            <p class="item-title">#COMBO Sweet Tee X Washed Cap B (M)</p>
            <p class="item-price">1 x $48.800,00</p>
          </div>
        </div>
        <p class="success-message">¡Agregado al carrito con éxito!</p>
      </div>
      <div class="total">
        <p>Total (3 productos):</p>
        <p class="total-price">$104.380,00</p>
      </div>
      <button class="cart-button">VER CARRITO</button>
    </div>

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
          <a class="links" href="/inicioCompra">Inicio compra</a>
          <a class="links" href="#productos">Productos</a>
          <a class="links" href="#nosotros">Nosotros</a>
          <a class="links" href="#proximamente">Próximamente</a>
        </div>
        <div id="carritoIcono" class="cart-icon">
          <a class="links" href="./login.php"><i class="bx bx-user"></i></a>
          <i class="bx bx-cart"></i>
        </div>
      </nav>
    </header>

    <!-- Sección principal -->
    <main>
      <section id="inicio" class="main-section">
        <div class="main-section__hijo">
          <img class="main-section__img" src="/image/logo inicio.png" alt="" />
          <p class="main-section__descripcion">Inconformes, queremos más</p>
          <button class="main-section__boton">Productos</button>
        </div>
      </section>

      <!-- Sección Productos -->
      <section id="productos" class="productos-section">
        <h2>Productos</h2>
        <div class="productos-grid">
          <div class="producto">
          <?php while ($producto = mysqli_fetch_assoc($res)) : ?>
            <a href="productosInfo.php?id=<?php echo $producto['id']; ?>"
              ><div class="producto">
                <img class="remeraImg" src="/image/remera 1.png" alt="Remera" />
                <p><?php echo $producto['nombre']; ?></p>
                <p>Precio: $<?php echo $producto['precio']; ?></p>
                <p><?php echo $producto['descripcion']; ?></p>
              </div></a
            >
            <?php endwhile; ?>              
          </div>
        </div>
      </section>

      <!-- Seccion Proximos Drops -->
      <section id="proximamente" class="drops-section">
        <h2>Proximos Drops</h2>
        <div class="drop-preview">
          <img
            class="drops-section__img"
            src="/image/proximamente.png"
            alt="proximos drops"
          />
          <p class="drops-section__p">Loading Drop...</p>
        </div>
      </section>

      <!-- Sobre Nosotros -->
      <section id="nosotros" class="nosotros-section">
        <h2>Sobre Nosotros</h2>
        <div class="nosotros-section__flex">
          <p class="nosotros-section__p">
            En Coco Verde, vivimos y respiramos el espíritu del streetwear.
            Nacimos con la misión de ofrecer prendas que no solo sigan
            tendencias, sino que expresen actitud, estilo y autenticidad.
            Inspirados en la cultura urbana, nuestras colecciones combinan
            comodidad, calidad y diseños únicos que te acompañan en cada paso de
            tu día. <br />
            Somos más que una tienda de ropa; somos una comunidad apasionada por
            el arte, la música, el skate y todo lo que representa el movimiento
            streetwear.<br />
            Nos esforzamos por ofrecer piezas que te permitan destacar y contar
            tu historia a través de lo que llevas puesto. En Coco Verde, cada
            prenda es una declaración de libertad, creatividad y rebeldía. Únete
            a nuestra familia y lleva contigo el espíritu de la calle, donde sea
            que vayas.
          </p>
          <img src="/image/logo navbar.png" alt="Logo Coco Verde" />
        </div>
      </section>
    </main>

    <!-- Pie de página -->
    <footer>
      <p>&copy; 2024 Coco Verde. Todos los derechos reservados.</p>
    </footer>
  </body>
</html>