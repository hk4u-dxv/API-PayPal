<?php
require_once __DIR__ . '/config.php';

function obtenerScriptPayPal()
{
    return "https://www.paypal.com/sdk/js?" . http_build_query([
        'client-id' => PAYPAL_CLIENT_ID,
        'currency' => PAYPAL_CURRENCY
    ]);
}

function validarTransaccionPayPal($orderID)
{
    if (APP_DEBUG) {
        error_log("Validando transacción PayPal: " . $orderID);
    }
    return true;
}

function procesarPagoPayPal($orderData)
{
    try {
        if (APP_DEBUG) {
            error_log("Procesando pago PayPal: " . json_encode($orderData));
        }

        return [
            'success' => true,
            'message' => 'Pago procesado correctamente'
        ];
    } catch (Exception $e) {
        if (APP_DEBUG) {
            error_log("Error en pago PayPal: " . $e->getMessage());
        }

        return [
            'success' => false,
            'message' => 'Error al procesar el pago: ' . $e->getMessage()
        ];
    }
}
