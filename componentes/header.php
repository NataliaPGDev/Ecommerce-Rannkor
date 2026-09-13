<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>rannkor</title>

    <!-- estilos CSS usuario--->
    <link rel="stylesheet" href="recursos/css/normalize.css">
    <link rel="stylesheet" href="recursos/css/usuario/usuario.css" />
    <link rel="stylesheet" href="recursos/css/usuario/vistas/home.css">
    <link rel="stylesheet" href="recursos/css/usuario/vistas/formularios.css">
    <link rel="stylesheet" href="recursos/css/usuario/vistas/cuenta.css">
    <link rel="stylesheet" href="recursos/css/usuario/vistas/productos.css">
    <link rel="stylesheet" href="recursos/css/usuario/vistas/detalleProducto.css">
    <link rel="stylesheet" href="recursos/css/usuario/vistas/carrito.css">
    <link rel="stylesheet" href="recursos/css/usuario/vistas/checkout.css">
    <link rel="stylesheet" href="recursos/css/usuario/vistas/pedido.css">

    <!-- estilos CSS administrador--->
    <link rel="stylesheet" href="recursos/css/admin/administrador.css">
    <link rel="stylesheet" href="recursos/css/admin/vistas/dashboard.css">


    <!---libreria Ioicons--->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</head>

<body>
    <header>
        <div class="nav__contenedor">
            <!-- Logo -->
            <nav class="nav__logo">
                <a href="index.php?pag=home">
                    <span>RannKör</span>
                </a>
            </nav>

            <!-- Menú principal -->
            <nav class="nav__menu">
                <ul>
                    <li><a href="index.php?controller=productos&action=listar&categoria=mujer">MUJER</a></li>
                    <li><a href="index.php?controller=productos&action=listar&categoria=hombre">HOMBRE</a></li>
                    <li><a href="#">NOSOTROS</a></li>
                </ul>
            </nav>

            <!-- Controles usuario -->
            <nav class="nav__controles-usuarios">
                <ul>
                    <!-- Ícono de usuario -->
                    <li>
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <a href="index.php?controller=usuario&action=perfil">
                                <ion-icon name="person-circle-outline"></ion-icon>
                            </a>
                        <?php else: ?>
                            <a href="index.php?controller=auth&action=login">
                                <ion-icon name="person-outline"></ion-icon>
                            </a>
                        <?php endif; ?>
                    </li>

                    <!-- Ícono carrito -->
                    <li>
                        <a href="index.php?controller=carrito&action=ver">
                            <ion-icon name="bag-outline"></ion-icon>
                        </a>
                    </li>

                    <!-- Botón de administrador (solo si es admin) -->
                    <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 1): ?>
                        <li>
                            <button class="btn-admin" onclick="location.href='index.php?controller=admin&action=dashboard'">
                                Administrador
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </header>
    <main>