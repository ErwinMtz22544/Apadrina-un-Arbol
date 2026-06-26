<?php
header("Content-Type: application/json");

$lat = floatval($_POST['lat'] ?? 0);
$lon = floatval($_POST['lon'] ?? 0);

if (!$lat || !$lon) {
    echo json_encode(["error" => "Faltan coordenadas"]);
    exit;
}

/* 🌦️ CLIMA */
$url = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&current_weather=true";

$response = @file_get_contents($url);

if (!$response) {
    echo json_encode(["error" => "No se pudo obtener clima"]);
    exit;
}

$data = json_decode($response, true);

$current = $data["current_weather"] ?? null;

if (!$current) {
    echo json_encode(["error" => "Sin datos"]);
    exit;
}

$temperatura = $current["temperature"];
$code = $current["weathercode"];
$isDay = $current["is_day"] ?? 1;

/* 🌤️ ICONOS PRO */
function icono($code, $isDay){

    // 🌙 NOCHE
    if ($isDay == 0) {
        switch($code){
            case 0: return "ri-moon-clear-fill";
            case 1:
            case 2:
            case 3: return "ri-cloudy-fill";
            case 45:
            case 48: return "ri-mist-fill";
            case 51:
            case 53:
            case 55: return "ri-drizzle-fill";
            case 61:
            case 63:
            case 65: return "ri-rainy-fill";
            case 71:
            case 73:
            case 75: return "ri-snowy-fill";
            case 95: return "ri-thunderstorms-fill";
            default: return "ri-moon-fill";
        }
    }

    // ☀️ DÍA
    switch($code){
        case 0: return "ri-sun-fill";
        case 1:
        case 2:
        case 3: return "ri-cloudy-fill";
        case 45:
        case 48: return "ri-mist-fill";
        case 51:
        case 53:
        case 55: return "ri-drizzle-fill";
        case 61:
        case 63:
        case 65: return "ri-rainy-fill";
        case 71:
        case 73:
        case 75: return "ri-snowy-fill";
        case 95: return "ri-thunderstorms-fill";
        default: return "ri-cloud-fill";
    }
}

echo json_encode([
    "temperatura" => $temperatura,
    "icono" => icono($code, $isDay)
]);