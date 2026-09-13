# Tienda de Zapatillas

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php" alt="PHP 8+" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql" alt="MySQL" />
  <img src="https://img.shields.io/badge/Status-Project%20Demo-28a745?style=for-the-badge" alt="Status" />
</p>

## Descripción

Tienda online de calzado desarrollada en PHP con una estructura MVC sencilla. Permite a los usuarios explorar productos, filtrar por categoría, ver detalles, gestionar un carrito de compra, completar el checkout y consultar el historial de pedidos.

La aplicación también incluye un panel administrativo para gestionar usuarios, productos y pedidos.

## Objetivo

Crear una experiencia de compra completa para usuarios que quieran navegar por catálogo, consultar detalles del producto, comprar con un flujo claro y gestionar su cuenta de forma sencilla.

## Funcionalidades

- Catálogo de productos por categoría
- Vista detallada del producto
- Carrito de compra con actualización de cantidades
- Proceso de checkout
- Historial de pedidos
- Perfil del usuario
- Sistema de autenticación y roles
- Panel administrativo para gestión de productos y usuarios

## Tecnologías

- PHP 8+
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- MVC básico en PHP
- Sesiones y control de acceso

## Estructura del proyecto

```text
/
├── api/                    # Servicios AJAX
├── componentes/            # Header, footer y bloques reutilizables
├── configuracion/          # Configuración y conexión a la BD
├── controladores/          # Lógica por módulo
├── modelo/                 # Entidades y acceso a datos
├── recursos/               # CSS, JS, imágenes e iconos
├── vistas/                 # Vistas del frontend y admin
├── index.php               # Entrada principal
├── router.php              # Enrutado del sitio
├── routeradmin.php         # Enrutado del panel admin
├── middleware.php          # Validación de sesión y permisos
├── README.md               # Documentación
└── .sql                    # Script de base de datos (si aplica)
```

## Requisitos previos

- PHP 8 o superior
- Servidor local (Apache, XAMPP, WAMP o MAMP)
- MySQL o MariaDB
- Navegador moderno

## Instalación

1. Clona o descarga este repositorio.
2. Colócalo dentro de la carpeta pública de tu servidor.
3. Descarga el archivo rannkor.sql y crea la base de datos MySQL.
4. Ajusta los datos de conexión en:
   - `configuracion/config.php`
   - `configuracion/conexion_bd.php`
5. Inicia el proyecto y accede en:
   - `http://localhost/tiendazapatillas`

## Configuración de la base de datos

Modifica estos valores con tus credenciales locales:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_password');
define('DB_NAME', 'tu_base_datos');
define('DB_PORT', 3306);
```

## Datos de acceso

| Rol           | Email             | Contraseña |
| ------------- | ----------------- | ---------- |
| Cliente       | cliente1@gmail.com | cliente123# |
| Administrador | admin@gmail.com   | admin123  |

## Roles de usuario

### Usuario estándar

- Ver catálogo
- Añadir productos al carrito
- Realizar compras
- Consultar historial de pedidos
- Modificar datos personales

### Administrador

- Acceder al dashboard
- Gestionar usuarios
- Crear, editar y borrar productos
- Gestionar tallas y stock
- Consultar pedidos

## Flujo principal

1. El usuario entra al catálogo.
2. Explora productos y categorías.
3. Consulta detalles y selecciona talla.
4. Añade artículos al carrito.
5. Revisa pedido y continúa al checkout.
6. Completa envío y pago.
7. Se genera el pedido y se guarda en la base de datos.
8. Puede consultar el historial y el detalle del pedido.

## Seguridad

La aplicación incluye controles básicos como:

- Validación de sesión
- Middleware para rutas restringidas
- Verificación de roles
- Redirección en accesos no autorizados

## Mejoras futuras

- Integración de pasarela de pago real
- Dashboard administrativo más completo
- Filtros avanzados por precio y talla
- Sistema de reseñas
- Mejoras de diseño responsive
- Tests automatizados

## Autoría

Proyecto desarrollado dentro del marco educativo FP Daw.
