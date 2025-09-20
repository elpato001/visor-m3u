<?php
// Desactiva el límite de tiempo de ejecución de PHP.
set_time_limit(0);

// --- Validación Inicial ---
if (!isset($_GET['url'])) {
    http_response_code(400);
    die("Error: No se ha especificado una URL para descargar.");
}

$fileUrl = urldecode($_GET['url']);

if (!filter_var($fileUrl, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die("Error: URL inválida.");
}

// --- Preparación del Nombre del Archivo ---
$fileName = 'video.mp4'; // Nombre por defecto
if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
    $fileNameFromParam = preg_replace('/[^a-zA-Z0-9\s\.\-_()]/', '', urldecode($_GET['nombre']));
    $extension = pathinfo(basename(parse_url($fileUrl, PHP_URL_PATH)), PATHINFO_EXTENSION);
    if ($extension) {
        $fileName = $fileNameFromParam . '.' . $extension;
    } else {
        $fileName = $fileNameFromParam;
    }
} else {
    $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));
}

// --- Configuración de cURL ---
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fileUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// --- Configuración de los Encabezados HTTP para la Descarga ---
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

// --- LÓGICA MEJORADA PARA MANEJAR HEAD Y GET ---

// Si la petición es de tipo HEAD (solo para obtener cabeceras),
// JDownloader solo quiere la información. No enviamos el cuerpo del archivo.
if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
    // Es importante obtener el tamaño del archivo si es posible
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    if ($contentLength > 0) {
        header('Content-Length: ' . $contentLength);
    }
    curl_close($ch);
    exit; // Salimos después de enviar las cabeceras.
}

// Si la petición es GET (la descarga real), procedemos a enviar el archivo.
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {
    echo $data;
    flush();
    return strlen($data);
});

// Ejecuta la sesión de cURL para la descarga.
curl_exec($ch);
curl_close($ch);
exit;