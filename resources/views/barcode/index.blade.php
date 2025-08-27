<!DOCTYPE html>
<html>

<head>
    <title>Barcode Example</title>
</head>

<body>
    <h1>Barcode Example</h1>
    <img src="{{ asset('storage/' . $barcodePath) }}" alt="Barcode">
    <img src="data:image/png;base64,{{ $barcodePath }}" alt="Barcode">
</body>

</html>