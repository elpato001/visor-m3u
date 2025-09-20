<?php
$url_generada = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servidor = rtrim($_POST['servidor'], '/');
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    if (!empty($servidor) && !empty($usuario) && !empty($contrasena)) {
        $url_generada = sprintf(
            "%s/get.php?username=%s&password=%s&type=m3u_plus&output=ts",
            $servidor, urlencode($usuario), urlencode($contrasena)
        );
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de URL M3U</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <nav>
        <ul>
            <li><a href="index.html">Visor Principal</a></li>
            <li><a href="generador.php" class="active">Generador de URL</a></li>
        </ul>
        <button id="theme-toggle" title="Cambiar tema">
            <span class="icon-moon">🌙</span>
            <span class="icon-sun">☀️</span>
        </button>
    </nav>
    
    <h1>Generador de URL M3U</h1>
    <p>Introduce los datos de tu servicio para crear el enlace de tu lista.</p>

    <form action="" method="post" style="text-align:left;">
        <div>
            <label for="servidor">URL del Servidor (ej: http://dominio.com:8080):</label>
            <input type="text" id="servidor" name="servidor" required>
        </div>
        <div>
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        <input type="submit" value="Generar URL" class="button button-primary full-width">
    </form>

    <?php if ($url_generada): ?>
        <div class="resultado">
            <h3>✅ ¡Lista generada con éxito!</h3>
            <p>Puedes descargar el archivo o abrirlo directamente en el visor.</p>
            <div class="button-group">
                <a href="#" id="downloadButton" class="button button-success">Descargar (.m3u)</a>
                <a href="#" id="openInViewerButton" class="button button-warning">Abrir en Visor</a>
            </div>
        </div>

        <script>
            const m3uUrl = '<?php echo $url_generada; ?>';

            // Lógica para el botón de descarga
            const downloadButton = document.getElementById('downloadButton');
            const m3uContent = "#EXTM3U\n" + m3uUrl;
            const blob = new Blob([m3uContent], { type: 'application/vnd.apple.mpegurl' });
            downloadButton.href = URL.createObjectURL(blob);
            downloadButton.download = 'lista_iptv.m3u';
            
            // Lógica para el botón de abrir en visor
            const openInViewerButton = document.getElementById('openInViewerButton');
            const viewerUrl = 'index.html?url=' + encodeURIComponent(m3uUrl);
            openInViewerButton.href = viewerUrl;
        </script>
    <?php endif; ?>
</div>

<script>
    // Script de modo oscuro (es el mismo para todas las páginas)
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;
    const applyTheme = (theme) => { body.classList.toggle('dark-mode', theme === 'dark'); };
    themeToggle.addEventListener('click', () => {
        const newTheme = body.classList.contains('dark-mode') ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });
    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(savedTheme);
    });
</script>

</body>
</html>