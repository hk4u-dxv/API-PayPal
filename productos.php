<?php
require_once 'obtenerDatosProductos.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API PayPal | Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/tailwind.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-gray-200 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gray-900/95 backdrop-blur-sm p-4 shadow-lg sticky top-0 z-50 border-b border-gray-800">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
            <a class="text-white text-lg font-bold flex items-center group" href="index.php">
                <i class="fas fa-store mr-2 text-blue-500 group-hover:text-blue-400 transition-colors"></i>
                <span class="group-hover:text-blue-400 transition-colors">API PayPal | Productos</span>
            </a>
            
            <div class="flex items-center gap-4">
                <div class="flex">
                    <input type="text" 
                           class="w-64 bg-gray-800 text-white rounded-l-lg p-2 border border-gray-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none" 
                           placeholder="Buscar productos..." 
                           id="searchInput">
                    <button class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-r-lg transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <a href="carrito.php" class="relative bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-full transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-xs w-6 h-6 rounded-full flex items-center justify-center font-bold shadow-lg" id="contador-carrito">
                        0
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-10 px-4">
        <!-- Grid de productos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($productos as $producto): ?>
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-gray-700/50 hover:border-blue-500/30 transition-all duration-300 group">
                <div class="relative">
                    <img src="<?php echo $producto['imagen']; ?>" 
                         class="w-full h-64 object-contain p-4 bg-white/5" 
                         alt="<?php echo $producto['titulo']; ?>">
                    <div class="absolute top-2 right-2 flex gap-2">
                        <?php if($producto['envioGratis']): ?>
                            <span class="bg-green-600/90 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">
                                <i class="fas fa-truck mr-1"></i> Envío gratis
                            </span>
                        <?php endif; ?>
                        <?php if($producto['descuento']): ?>
                            <span class="bg-red-500/90 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">
                                -<?php echo $producto['descuento']['porcentaje']; ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="absolute bottom-2 left-2 bg-blue-600/90 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="<?php echo $producto['categoria']['icono']; ?> mr-1"></i>
                        <?php echo $producto['categoria']['nombre']; ?>
                    </span>
                </div>
                <div class="p-4">
                    <h6 class="text-lg font-bold text-white truncate group-hover:text-blue-400 transition-colors">
                        <?php echo $producto['titulo']; ?>
                    </h6>
                    <p class="text-sm text-gray-400 mb-3 line-clamp-2">
                        <?php echo $producto['descripcion']; ?>
                    </p>
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <h6 class="text-2xl font-bold text-white">$<?php echo $producto['precio']; ?></h6>
                            <?php if($producto['descuento']): ?>
                                <span class="text-sm text-gray-400 line-through">
                                    $<?php echo number_format($producto['precio'] * (1 + $producto['descuento']['porcentaje']/100), 2); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <button class="bg-blue-600 hover:bg-blue-500 text-white p-3 rounded-full transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20 group" 
                                onclick='agregarAlCarrito(<?php echo json_encode($producto); ?>)'>
                            <i class="fas fa-cart-plus group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-yellow-400 text-sm">
                            <?php foreach($producto['rating']['estrellas'] as $estrella): ?>
                                <i class="<?php echo $estrella; ?>"></i>
                            <?php endforeach; ?>
                            <span class="text-gray-400 ml-1">(<?php echo $producto['rating']['conteo']; ?>)</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Manejo del carrito
        function agregarAlCarrito(producto) {
            try {
                let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                const itemExistente = carrito.find(item => item.id === producto.id);
                
                if (itemExistente) {
                    itemExistente.cantidad++;
                    Swal.fire({
                        title: '¡Producto actualizado!',
                        text: `Se agregó otra unidad de ${producto.titulo}`,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    carrito.push({...producto, cantidad: 1});
                    Swal.fire({
                        title: '¡Producto agregado!',
                        text: `${producto.titulo} fue agregado al carrito`,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                localStorage.setItem('carrito', JSON.stringify(carrito));
                actualizarContadorCarrito();
            } catch (error) {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo agregar el producto al carrito',
                    icon: 'error',
                    timer: 1500
                });
            }
        }

        function actualizarContadorCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const contador = carrito.reduce((total, item) => total + item.cantidad, 0);
            document.getElementById('contador-carrito').textContent = contador;
        }

        // Filtrado de productos
        document.getElementById('searchInput').addEventListener('input', filtrarProductos);

        function filtrarProductos() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const productos = document.querySelectorAll('.card-product');

            productos.forEach(producto => {
                const titulo = producto.querySelector('.card-title').textContent.toLowerCase();
                
                const coincideTexto = titulo.includes(searchTerm);
                
                producto.closest('.col').style.display = 
                    coincideTexto ? '' : 'none';
            });
        }

        // Inicializar contador al cargar la página
        document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
    </script>
</body>
</html> 