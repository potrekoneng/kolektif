<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Last Image</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            height: 100vh;
        }

        #myImage {
            height: 100vh;
            width: auto;
            max-width: 100vw;
            object-fit: contain;
            display: block;
        }
    </style>
</head>

<body>
    <img id="myImage" src="/latest-image" alt="Dynamic Image" />

    <script>
        const img = document.getElementById('myImage');
        let lastModified = null;

        async function updateImageIfChanged() {
            try {
                const res = await fetch('/latest-image-meta');
                const data = await res.json();

                if (!lastModified || data.last_modified !== lastModified) {
                    img.src = data.url + '?t=' + Date.now();
                    lastModified = data.last_modified;
                }
            } catch (err) {
                console.error("Gagal ambil metadata gambar:", err);
            }
        }

        setInterval(updateImageIfChanged, 2000);
    </script>
</body>

</html>