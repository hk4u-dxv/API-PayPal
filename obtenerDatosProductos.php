<?php

class ProductoAPI {
    private $baseUrl = "https://fakestoreapi.com";
    private $timeout = 10;
    private $categorias = [
        "men's clothing" => [
            "nombre" => "Ropa para Hombre",
            "icono" => "fas fa-tshirt"
        ],
        "women's clothing" => [
            "nombre" => "Ropa para Mujer",
            "icono" => "fas fa-female"
        ],
        "jewelery" => [
            "nombre" => "Joyería",
            "icono" => "fas fa-gem"
        ],
        "electronics" => [
            "nombre" => "Electrónica",
            "icono" => "fas fa-laptop"
        ]
    ];

    private function realizarPeticion($endpoint) {
        try {
            $ch = curl_init($this->baseUrl . $endpoint);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json']
            ]);

            $respuesta = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                throw new Exception('Error en la petición cURL: ' . curl_error($ch));
            }

            curl_close($ch);

            if ($httpCode !== 200) {
                throw new Exception('Error en la API: Código HTTP ' . $httpCode);
            }

            return json_decode($respuesta, true);
        } catch (Exception $e) {
            error_log("Error en la API: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerProductos() {
        $productos = $this->realizarPeticion('/products');
        
        if (!$productos) {
            return [
                'error' => true,
                'mensaje' => 'No se pudieron obtener los productos'
            ];
        }

        return array_map(function($producto) {
            return $this->formatearProducto($producto);
        }, $productos);
    }

    public function obtenerCategorias() {
        return $this->realizarPeticion('/products/categories');
    }

    private function formatearProducto($producto) {
        $categoria = strtolower($producto['category']);
        $infoCategoría = $this->categorias[$categoria] ?? [
            'nombre' => ucfirst($categoria),
            'icono' => 'fas fa-tag'
        ];

        return [
            'id' => $producto['id'],
            'titulo' => $producto['title'],
            'precio' => number_format($producto['price'], 2),
            'descripcion' => $producto['description'],
            'categoria' => [
                'nombre' => $infoCategoría['nombre'],
                'icono' => $infoCategoría['icono']
            ],
            'imagen' => $producto['image'],
            'rating' => [
                'calificacion' => number_format($producto['rating']['rate'], 1),
                'conteo' => $producto['rating']['count'],
                'estrellas' => $this->generarEstrellas($producto['rating']['rate'])
            ],
            'descuento' => $this->generarDescuentoAleatorio(),
            'envioGratis' => $producto['price'] > 50
        ];
    }

    private function generarEstrellas($calificacion) {
        $estrellas = [];
        $calificacionRedondeada = floor($calificacion);
        $tieneMedia = ($calificacion - $calificacionRedondeada) >= 0.5;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $calificacionRedondeada) {
                $estrellas[] = 'fas fa-star'; // estrella completa
            } elseif ($i == $calificacionRedondeada + 1 && $tieneMedia) {
                $estrellas[] = 'fas fa-star-half-alt'; // media estrella
            } else {
                $estrellas[] = 'far fa-star'; // estrella vacía
            }
        }

        return $estrellas;
    }

    private function generarDescuentoAleatorio() {
        $probabilidad = rand(1, 100);
        if ($probabilidad <= 30) { // 30% de probabilidad de tener descuento
            return [
                'porcentaje' => rand(10, 50),
                'etiqueta' => 'Oferta'
            ];
        }
        return null;
    }
}

// Inicializar la clase y obtener los productos
try {
    $api = new ProductoAPI();
    $productos = $api->obtenerProductos();

    if (isset($productos['error'])) {
        throw new Exception($productos['mensaje']);
    }
} catch (Exception $e) {
    error_log("Error al obtener productos: " . $e->getMessage());
    $productos = [];
} 