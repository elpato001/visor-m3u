# Visor de Listas M3U 📺

![Licencia MIT](https://img.shields.io/badge/Licencia-MIT-blue.svg)

Un visor web moderno y ligero para cargar, visualizar, buscar y gestionar tus listas de reproducción M3U (IPTV). La aplicación está construida con PHP y JavaScript puro (Vanilla JS), enfocada en la simplicidad y el rendimiento.

> **Nota:** Este proyecto está diseñado para usarse con listas M3U personales y con fines educativos.


---

## ✨ Características Principales

* **Carga Flexible:** Carga listas M3U desde un archivo local o directamente desde una URL.
* **Organización Automática:** Agrupa el contenido por categorías (`group-title`) para una navegación sencilla.
* **Detección Inteligente de Series:** Identifica automáticamente series, temporadas y episodios, y los agrupa en una vista dedicada.
* **Reproductor Integrado:** Reproduce contenido de video directamente en el navegador a través de un modal.
* **Búsqueda Rápida:** Filtra y busca películas o series en toda la lista al instante.
* **Sistema de Favoritos:** Guarda tus canales o películas favoritas para un acceso rápido (utiliza `localStorage`).
* **Modo Claro y Oscuro:** Tema visual que se adapta a las preferencias del sistema o se puede cambiar manualmente.
* **Generador de URL:** Una utilidad para crear fácilmente la URL de tu lista a partir de los datos de tu proveedor.
* **Opciones de Descarga:**
    * Descarga directa del contenido.
    * Integración con **JDownloader** para enviar enlaces al gestor de descargas.

---

## 🛠️ Tecnologías Utilizadas

* **Frontend:** HTML5, CSS3, JavaScript (Vanilla JS)
* **Backend:** PHP
* **Dependencias:** Ninguna (no requiere frameworks ni librerías externas).

---

## 🚀 Puesta en Marcha

Para ejecutar este proyecto en tu entorno local, necesitarás un servidor web que soporte PHP.

### Requisitos

* Un servidor web local (se recomiendan [XAMPP](https://www.apachefriends.org/es/index.html), WAMP o MAMP).
* PHP 7.4 o superior.
* La extensión `cURL` de PHP debe estar habilitada (generalmente lo está por defecto en XAMPP).

### Pasos de Instalación

1.  **Descargar el proyecto:**
    * Ve a la página principal de este repositorio en GitHub.
    * Haz clic en el botón verde **<> Code**.
    * En el menú que aparece, selecciona **Download ZIP**.
    * Descomprime el archivo `.zip` en la ubicación que prefieras de tu ordenador.

2.  **Mover los archivos:**
    * Copia la carpeta descomprimida (que contiene todos los archivos del proyecto) en el directorio raíz de tu servidor web (por ejemplo, `C:/xampp/htdocs/visor-m3u`).

3.  **Acceder a la aplicación:**
    * Inicia tu servidor Apache.
    * Abre tu navegador y ve a `http://localhost/visor-m3u/` (o la carpeta donde hayas puesto los archivos).

---

## 📖 Uso de la Aplicación

1.  **Cargar una lista:**
    * **Opción A (Recomendada):** Ve a la pestaña "Generador de URL", introduce los datos de tu servicio y haz clic en "Abrir en Visor".
    * **Opción B:** En la página principal, selecciona un archivo `.m3u` o `.m3u8` de tu ordenador y haz clic en "Cargar Lista".

2.  **Navegar:**
    * Una vez cargada la lista, verás todas las categorías disponibles.
    * Haz clic en una categoría para ver su contenido.
    * Usa la barra de búsqueda para encontrar algo específico.

3.  **Interactuar con el contenido:**
    * **Reproducir:** Abre el reproductor de video.
    * **Descargar:** Inicia la descarga directa del archivo.
    * **Enviar a JD:** Envía el enlace a tu JDownloader local (debe estar en ejecución).
    * **Ver Episodios:** Para las series, te llevará a una vista detallada por temporadas.
    * **Favorito (☆/★):** Añade o quita un elemento de tu lista de favoritos.

---

## ⚖️ Licencia

Este proyecto está distribuido bajo la Licencia MIT. Consulta el archivo `LICENSE` para más detalles.

---


¡Siéntete libre de contribuir al proyecto!
