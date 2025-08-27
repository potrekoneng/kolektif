<!DOCTYPE html>
<html>
<head>
    <title>Live Image Viewer</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Gambar Terbaru</h1>
    <img id="latest-image" src="" width="500" alt="Gambar terbaru">

    <script>
        async function fetchLatestImage() {
            try {
                const response = await fetch('/lastimagecrop');
                const data = await response.json();
                if (data.url) {
                    document.getElementById('latest-image').src = data.url + '?t=' + new Date().getTime(); // Cache-busting
                }
            } catch (err) {
                console.error('Gagal mengambil gambar:', err);
            }
        }

        fetchLatestImage();
        setInterval(fetchLatestImage, 2000); // tiap 2 detik
    </script>
</body>
</html>