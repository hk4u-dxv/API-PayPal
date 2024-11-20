<?php
require_once 'obtenerDatosProductos.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API PayPal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/styles/tailwind.css">
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-gray-200 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gray-900/95 backdrop-blur-sm p-4 shadow-lg sticky top-0 z-50 border-b border-gray-800">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-4">
            <a class="text-white text-lg font-bold flex items-center group" href="index.php">
                <i class="fas fa-store mr-2 text-blue-500 group-hover:text-blue-400 transition-colors"></i>
                <span class="group-hover:text-blue-400 transition-colors">API PayPal</span>
            </a>
            
            <a href="carrito.php" class="relative bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-full transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                <i class="fas fa-shopping-cart"></i>
                <span class="absolute -top-2 -right-2 bg-red-500 text-xs w-6 h-6 rounded-full flex items-center justify-center font-bold shadow-lg" id="contador-carrito">
                    0
                </span>
            </a>
        </div>
    </nav>

    <main class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-10 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center space-y-8">
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white/90 to-gray-500/90">
                    Tienda en línea con PayPal
                </h1>
                
                <div class="bg-gray-800/50 backdrop-blur-sm shadow-xl text-gray-200 mx-auto p-8 rounded-2xl border border-gray-700/50 hover:border-blue-500/30 transition-colors">
                    <div class="max-w-2xl mx-auto space-y-6">
                        <div class="inline-block p-3 bg-blue-500/10 rounded-full mb-4">
                            <i class="fas fa-shield-alt text-3xl text-blue-400"></i>
                        </div>
                        
                        <h4 class="text-blue-400 text-2xl font-bold mb-4">Sobre este sistema</h4>
                        
                        <h5 class="text-gray-300 leading-relaxed mb-4">
                            Este es un sistema de prueba para la integración de PayPal en una tienda en línea. 
                            Puedes probar el proceso de compra completo usando el entorno de sandbox de PayPal.
                        </h5>

                        <div class="bg-blue-500/10 rounded-xl p-4 mb-8">
                            <h5 class="text-sm text-gray-400">
                                <i class="fas fa-info-circle mr-2"></i>
                                Los productos mostrados son proporcionados por 
                                <a href="https://fakestoreapi.com" target="_blank" class="text-blue-400 hover:text-blue-300 underline">
                                    Fake Store API
                                </a>, 
                                una API de prueba que simula una tienda en línea real.
                            </h5>
                        </div>
                        
                        <a href="productos.php" 
                           class="inline-flex items-center bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-full transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20 group">
                            <i class="fas fa-shopping-bag mr-2 group-hover:scale-110 transition-transform"></i>
                            <span class="font-semibold">Ver Productos</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const contador = carrito.reduce((total, item) => total + item.cantidad, 0);
            document.getElementById('contador-carrito').textContent = contador;
        });
    </script>
</body>

</html>