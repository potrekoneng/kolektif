<!DOCTYPE html>
<html>

<head>
    <title>QR Code Example</title>
</head>

<body>
    <h1>QR Code Example</h1>
    {!!DNS1D::getBarcodeHTML('4445645656', 'C128');!!}
    <br>
    {!!DNS1D::getBarcodeHTML('4445645656', 'C128A');!!}
    <br>
    {!!DNS1D::getBarcodeHTML('4445645656', 'C128B');!!}
    <br>
    {!!DNS1D::getBarcodeHTML('4445645656', 'C128C');!!}
    <br>
    {!!DNS2D::getBarcodeHTML('4445645656', 'QRCODE');!!}
</body>

</html>