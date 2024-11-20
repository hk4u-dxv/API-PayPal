// Variables globales
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

// Función para actualizar la interfaz del carrito
function actualizarCarritoUI() {
	const itemsCarrito = document.getElementById("items-carrito");
	const subtotalElement = document.getElementById("subtotal");
	const ivaElement = document.getElementById("iva");
	const envioElement = document.getElementById("envio");
	const totalElement = document.getElementById("total-carrito");

	if (!carrito || carrito.length === 0) {
		itemsCarrito.innerHTML = `
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
		`;

		subtotalElement.textContent = "$0.00";
		ivaElement.textContent = "$0.00";
		envioElement.textContent = "$0.00";
		totalElement.textContent = "$0.00";
		return;
	}

	// Mostrar productos
	let html = "";
	let subtotal = 0;

	carrito.forEach((item) => {
		const subtotalItem = parseFloat(item.precio) * item.cantidad;
		subtotal += subtotalItem;

		html += `
			<div class="mb-4 p-4 bg-gray-800/30 rounded-lg border border-gray-700/50 hover:border-blue-500/30 transition-colors">
				<div class="flex items-center gap-4">
					<div class="w-20 h-20 bg-white/5 rounded-lg p-2">
						<img src="${item.imagen}" 
							 class="w-full h-full object-contain" 
							 alt="${item.titulo}">
					</div>
					<div class="flex-1">
						<h6 class="font-semibold text-white mb-1">${item.titulo}</h6>
						<div class="flex items-center text-sm text-gray-400">
							<span class="mr-2">Precio: $${item.precio}</span>
							${item.envioGratis ? 
								`<span class="text-green-400 text-xs">
									<i class="fas fa-truck mr-1"></i>Envío gratis
								</span>` : 
								''}
						</div>
					</div>
					<div class="flex items-center gap-2">
						<button class="bg-gray-700 hover:bg-gray-600 text-white w-8 h-8 rounded-full transition-colors" 
								onclick="cambiarCantidad(${item.id}, ${item.cantidad - 1})">
							<i class="fas fa-minus"></i>
						</button>
						<input type="text" 
							   class="w-12 text-center bg-gray-800 text-white border border-gray-700 rounded-lg" 
							   value="${item.cantidad}" 
							   readonly>
						<button class="bg-gray-700 hover:bg-gray-600 text-white w-8 h-8 rounded-full transition-colors" 
								onclick="cambiarCantidad(${item.id}, ${item.cantidad + 1})">
							<i class="fas fa-plus"></i>
						</button>
						<button class="bg-red-600/80 hover:bg-red-500 text-white w-8 h-8 rounded-full ml-2 transition-colors" 
								onclick="eliminarDelCarrito(${item.id})">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>
				</div>
			</div>
		`;
	});

	itemsCarrito.innerHTML = html;

	// Calcular totales
	const iva = subtotal * 0.16;
	const envio = subtotal > 1000 ? 0 : 150;
	const total = subtotal + iva + envio;

	// Actualizar resumen
	subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
	ivaElement.textContent = `$${iva.toFixed(2)}`;
	envioElement.textContent = `$${envio.toFixed(2)}`;
	totalElement.textContent = `$${total.toFixed(2)}`;
}

// Funciones del carrito
function cambiarCantidad(id, nuevaCantidad) {
	if (nuevaCantidad < 1) {
		eliminarDelCarrito(id);
		return;
	}

	const item = carrito.find((item) => item.id === id);
	if (item) {
		item.cantidad = nuevaCantidad;
		localStorage.setItem("carrito", JSON.stringify(carrito));
		actualizarCarritoUI();
	}
}

function eliminarDelCarrito(id) {
	carrito = carrito.filter((item) => item.id !== id);
	localStorage.setItem("carrito", JSON.stringify(carrito));
	actualizarCarritoUI();
}

// Inicializar PayPal
function inicializarPayPal() {
	if (typeof paypal !== "undefined") {
		paypal
			.Buttons({
				createOrder: function (data, actions) {
					const subtotal = carrito.reduce(
						(total, item) => total + parseFloat(item.precio) * item.cantidad,
						0
					);
					const iva = subtotal * 0.16;
					const envio = subtotal > 1000 ? 0 : 150;
					const total = subtotal + iva + envio;

					return actions.order.create({
						purchase_units: [
							{
								amount: {
									value: total.toFixed(2),
									currency_code: "MXN",
								},
							},
						],
					});
				},
				onApprove: function (data, actions) {
					return actions.order.capture().then(function (details) {
						localStorage.removeItem("carrito");
						carrito = [];
						alert(
							"¡Compra completada! Gracias por tu compra, " +
								details.payer.name.given_name
						);
						actualizarCarritoUI();
					});
				},
				onError: function (err) {
					console.error("Error en el pago:", err);
					alert("Ocurrió un error durante el proceso de pago");
				},
			})
			.render("#paypal-button-container");
	} else {
		console.error("PayPal script no está cargado correctamente");
	}
}

// Inicializar cuando el documento esté listo
document.addEventListener("DOMContentLoaded", () => {
	console.log("Página cargada"); // Debug
	actualizarCarritoUI();
	inicializarPayPal();
});
