<?php
header('Content-Type: application/json');

function getTitleFromName($url) {
    try {
        $path = parse_url($url, PHP_URL_PATH);
        $fileName = basename($path);
        return urldecode(pathinfo($fileName, PATHINFO_FILENAME));
    } catch (Exception $e) {
        return 'Video sin título';
    }
}

if (!isset($_POST['m3u_content'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió el contenido de la lista M3U.']);
    exit;
}

$m3uContent = $_POST['m3u_content'];
$lines = explode("\n", $m3uContent);

$allCategories = [];
$contentByCategory = [];
$currentCategory = 'Sin Categoría';
$currentTitle = 'Video sin título';

foreach ($lines as $line) {
    $trimmedLine = trim($line);

    if (strpos($trimmedLine, '#EXTINF') === 0) {
        $currentTitle = 'Video sin título';
        $currentCategory = 'Sin Categoría';

        if (preg_match('/group-title="([^"]+)"/', $trimmedLine, $matches)) {
            $currentCategory = $matches[1];
        }

        if (preg_match('/tvg-name="([^"]+)"/', $trimmedLine, $matches)) {
            $currentTitle = $matches[1];
        } elseif (preg_match('/,(.*)$/', $trimmedLine, $matches) && trim($matches[1]) !== '') {
            $currentTitle = trim($matches[1]);
        }
        
        if (!isset($allCategories[$currentCategory])) {
            $allCategories[$currentCategory] = ['count' => 0];
        }
    } elseif (strpos($trimmedLine, 'http') === 0) {
        $url = $trimmedLine;
        if ($currentTitle === 'Video sin título') {
            $currentTitle = getTitleFromName($url);
        }
        
        if (!isset($contentByCategory[$currentCategory])) {
            $contentByCategory[$currentCategory] = [];
        }

        $isSeries = preg_match('/^(.*?)[\s._(\[]*[SsTt](\d+)[._-]*[EeXx](\d+)/i', $currentTitle, $seriesMatches);

        if ($isSeries) {
            $seriesName = trim($seriesMatches[1]);
            $seasonNumber = (int)$seriesMatches[2];
            $episodeNumber = (int)$seriesMatches[3];

            if (!isset($contentByCategory[$currentCategory][$seriesName])) {
                $contentByCategory[$currentCategory][$seriesName] = [
                    'type' => 'series',
                    'title' => $seriesName,
                    'category' => $currentCategory,
                    'episodes' => []
                ];
                $allCategories[$currentCategory]['count']++;
            }
            
            $contentByCategory[$currentCategory][$seriesName]['episodes'][] = [
                'season' => $seasonNumber,
                'episode' => $episodeNumber,
                'title' => $currentTitle,
                'url' => $url
            ];

        } else {
            $contentByCategory[$currentCategory][$currentTitle] = [
                'type' => 'movie',
                'title' => $currentTitle,
                'category' => $currentCategory,
                'url' => $url
            ];
            $allCategories[$currentCategory]['count']++;
        }
    }
}

// Lógica de respuesta
// CORREGIDO: Nueva opción para obtener todo el contenido de una vez
if (isset($_POST['get_all_content'])) {
    $allItems = [];
    foreach ($contentByCategory as $category => $items) {
        $allItems = array_merge($allItems, array_values($items));
    }
    echo json_encode(['status' => 'success', 'categories' => $allCategories, 'allItems' => $allItems]);

} elseif (isset($_POST['category'])) {
    $category = $_POST['category'];

    if (isset($contentByCategory[$category])) {
        if (isset($_POST['series_name'])) {
            $seriesName = $_POST['series_name'];
            if (isset($contentByCategory[$category][$seriesName]) && $contentByCategory[$category][$seriesName]['type'] === 'series') {
                $episodes = $contentByCategory[$category][$seriesName]['episodes'];
                
                usort($episodes, function($a, $b) {
                    if ($a['season'] == $b['season']) {
                        return $a['episode'] <=> $b['episode'];
                    }
                    return $a['season'] <=> $b['season'];
                });
                
                echo json_encode(['status' => 'success', 'episodes' => $episodes]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Serie no encontrada.']);
            }
        } else {
            // Esta ruta ya casi no se usa, pero la dejamos por si acaso.
            echo json_encode(['status' => 'success', 'content' => array_values($contentByCategory[$category]), 'allCategories' => $allCategories]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Categoría no encontrada.']);
    }
} else {
    // La petición inicial solo para validar el M3U ya no es necesaria con el nuevo flujo.
    echo json_encode(['status' => 'error', 'message' => 'Petición no válida.']);
}
?>