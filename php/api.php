<?php
define('API_URL', 'http://localhost:3000/api/visitas');

function llamarApi($metodo, $url, $datos = null)
{
    $opciones = [
        'http' => [
            'method'        => $metodo,
            'header'        => "Content-Type: application/json\r\n",
            'ignore_errors' => true
        ]
    ];
    if ($datos !== null) {
        $opciones['http']['content'] = json_encode($datos);
    }
    $contexto  = stream_context_create($opciones);
    $respuesta = @file_get_contents($url, false, $contexto);
    if ($respuesta === false) {
        return null;
    }
    return json_decode($respuesta, true);
}