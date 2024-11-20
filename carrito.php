<?php
require_once 'obtenerDatosProductos.php';
require_once 'config/paypalConfig.php';

$paypalScript = obtenerScriptPayPal();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API PayPal | Carrito</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/tailwind.css">
    <script src="<?php echo $paypalScript; ?>"></script>
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-gray-200 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gray-900/95 backdrop-blur-sm p-4 shadow-lg sticky top-0 z-50 border-b border-gray-800">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
            <a class="text-white text-lg font-bold flex items-center group" href="index.php">
                <i class="fas fa-store mr-2 text-blue-500 group-hover:text-blue-400 transition-colors"></i>
                <span class="group-hover:text-blue-400 transition-colors">API PayPal | Carrito</span>
            </a>
            <a class="flex items-center bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-full transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20 group" 
               href="productos.php">
                <i class="fas fa-arrow-left mr-2 group-hover:transform group-hover:-translate-x-1 transition-transform"></i>
                Volver a la tienda
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-10 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Lista de productos en el carrito -->
            <div class="lg:col-span-2">
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-700/50">
                    <div class="p-4 border-b border-gray-700/50">
                        <h5 class="text-lg font-bold flex items-center">
                            <i class="fas fa-shopping-cart text-blue-500 mr-2"></i>
                            Carrito de Compras
                        </h5>
                    </div>
                    <div class="p-4" id="items-carrito">
                        <!-- Los items del carrito se cargarán aquí dinámicamente -->
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-cart text-6xl text-gray-500/50 mb-4"></i>
                            <h5 class="text-xl font-bold text-gray-400 mb-2">El carrito está vacío</h5>
                            <p class="text-gray-500 mb-6">¡Agrega algunos productos para comenzar!</p>
                            <a href="productos.php" 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-full transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20 group">
                                <i class="fas fa-shopping-bag mr-2 group-hover:scale-110 transition-transform"></i>
                                <span class="font-semibold">Ir a la tienda</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen del carrito -->
            <div>
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-700/50">
                    <div class="p-4 border-b border-gray-700/50">
                        <h5 class="text-lg font-bold flex items-center">
                            <i class="fas fa-receipt text-blue-500 mr-2"></i>
                            Resumen de compra
                        </h5>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Subtotal</span>
                                <span id="subtotal" class="font-medium">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">IVA (16%)</span>
                                <span id="iva" class="font-medium">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Envío</span>
                                <span id="envio" class="font-medium">$0.00</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-700/50 pt-4">
                            <div class="flex justify-between items-center mb-6">
                                <strong class="text-lg">Total</strong>
                                <strong id="total-carrito" class="text-lg text-blue-400">$0.00</strong>
                            </div>
                            <div id="paypal-button-container" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo $paypalScript; ?>"></script>
    <script src="assets/js/carrito.js"></script>
</body>

</html>