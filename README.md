# Tienda de Zapatillas

## Descripción

Esta aplicación es una tienda online de calzado desarrollada en PHP con una arquitectura MVC simple. Permite a los usuarios navegar por productos, filtrarlos por categoría, ver detalles de cada artículo, gestionar un carrito de compra, completar un proceso de checkout y consultar el historial de pedidos.

La plataforma también incluye un panel de administración para gestionar usuarios y productos, con operaciones de listado, inserción, actualización y eliminación.

## Objetivo

Crear una experiencia de compra completa para clientes que quieran explorar zapatillas por categoría, consultar información de cada producto, comprar con un proceso seguro y gestionar su cuenta desde una interfaz sencilla.

## Funcionalidades principales

### Frontend y experiencia de usuario

- Catálogo de productos por categoría: hombre, mujer y otros tipos disponibles.
- Páginas de inicio con novedades destacadas.
- Detalle del producto con información, imagen y tallas disponibles.
- Carrito de compra con actualización de cantidades y cálculo de subtotal.
- Proceso de compra con formulario de envío y selección de método de pago.
- Historial de pedidos del usuario.
- Perfil de usuario con datos personales y dirección.

### Autenticación y autorización

- Registro e inicio de sesión de usuarios.
- Protección de rutas según sesión activa.
- Control de acceso para usuarios normales y administradores.
- Expiración automática de sesión por inactividad.

### Administración

- Dashboard administrativo.
- Gestión de usuarios.
- Gestión de productos y tallas.
- Consulta de pedidos y administración de stock y catálogo.

## Tecnologías utilizadas

- PHP 8+
- MySQL / MariaDB
- MVC básico en PHP
- HTML5, CSS3, JavaScript
- Sesiones de usuario
- AJAX / Fetch para operaciones dinámicas del carrito y panel administrativo

## Estructura del proyecto

```text
/
├── api/                    # Endpoints AJAX o servicios
├── componentes/            # Header, footer y datos reutilizables
├── configuracion/          # Configuración general de la app y conexión a BD
├── controladores/          # Lógica de negocio según cada módulo
├── modelo/                 # Modelos para usuarios, productos, carrito, pedidos y admin
├── recursos/               # CSS, JS, imágenes e iconos
├── vistas/                 # Vistas HTML/PHP por sección
├── index.php               # Entrada principal de la app
├── router.php              # Enrutamiento del sitio web
├── routeradmin.php         # Enrutamiento del panel administrativo
├── middleware.php          # Validación de sesión y permisos
├── README.md               # Documentación del proyecto
└── .sql                    # Script de base de datos (si se añade en el futuro)
```

## Requisitos previos

- PHP 8 o superior
- Servidor web local (Apache o XAMPP/WAMP/MAMP)
- MySQL o MariaDB
- Navegador web moderno

## Instalación

1. Clona o descarga este repositorio.
2. Colócalo en la carpeta pública de tu servidor local.
3. Crea la base de datos MySQL con el esquema correspondiente.
4. Ajusta los datos de conexión en el archivo de configuración:
   - configuracion/config.php
   - configuracion/conexion_bd.php
5. Inicia el proyecto desde tu servidor y accede a:
   - http://localhost/tiendazapatillas

## Configuración de la base de datos

El proyecto usa credenciales configuradas en la aplicación para conectarse a la base de datos. Debes modificar los valores de:

- host
- usuario
- contraseña
- nombre de la base de datos
- puerto

Ejemplo:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_password');
define('DB_NAME', 'tu_base_datos');
define('DB_PORT', 3306);
```

## Roles de usuario

### Usuario estándar

- Ver catálogo
- Agregar productos al carrito
- Realizar compra
- Ver historial de pedidos
- Actualizar datos personales y dirección

### Administrador

- Acceder al dashboard
- Ver y gestionar usuarios
- Crear, editar y eliminar productos
- Gestionar tallas y stock asociado

## Flujo principal de la aplicación

1. El usuario entra a la landing page o catálogo.
2. Explora productos o entra a la categoría deseada.
3. Consulta el detalle del producto y selecciona talla.
4. Agrega productos al carrito.
5. Revisa el carrito y procede al checkout.
6. Completa la información de envío y pago.
7. Se genera un pedido y se almacena en la base de datos.
8. El usuario puede consultar su historial y detalles del pedido.

## Módulos principales

### Home

- Página de inicio con novedades y productos destacados.

### Productos

- Listado por categoría.
- Vista detallada del producto.

### Carrito

- Agregar productos.
- Ajustar cantidades.
- Eliminar artículos.
- Vaciar carrito.
- Calcular subtotal.

### Pedidos

- Checkout.
- Generación de pedido.
- Ver pedido individual.
- Historial de compras.

### Usuario

- Perfil.
- Visualización de datos personales.
- Gestión básica de información del cliente.

### Panel Administración

- Dashboard.
- CRUD de usuarios y productos.
- Administración de tallas por producto.

## Seguridad

La aplicación implementa controles básicos de seguridad, como:

- Validación de sesión para zonas restringidas.
- Middleware para redirección en caso de acceso no autorizado.
- Verificación de rol para usuarios administradores.
- Manejo de errores y excepciones para evitar fallos visibles.

## Posibles mejoras futuras

- Integración de pasarela de pago real.
- Panel administrativo más completo con gráficos y estadísticas.
- Filtros avanzados por marca, precio y talla.
- Sistema de valoraciones y comentarios de productos.
- Mejoras en diseño responsivo y experiencia móvil.
- Implementación de tests automatizados.

## Autoría

Proyecto desarrollado dentro de marco educativo FP Daw.
