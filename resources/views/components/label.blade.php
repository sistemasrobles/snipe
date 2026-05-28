<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label - {{ $component->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }
        .label {
            background: #fff;
            border: 1px solid #999;
            padding: 8px 10px;
            width: 340px;
            display: inline-block;
        }
        .label-row {
            font-size: 11px;
            line-height: 1.5;
            margin-bottom: 2px;
        }
        .label-row span.key {
            color: #d9534f;
            font-weight: bold;
        }
        .label-row span.val {
            color: #2b6cb0;
            font-weight: bold;
        }
        .barcode-img {
            display: block;
            width: 100%;
            height: auto;
            margin-top: 6px;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
            width: 100%;
        }
        .btn {
            padding: 8px 20px;
            background: #337ab7;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            margin: 0 5px;
        }
        .btn-success { background: #5cb85c; }
        @media print {
            body { background: #fff; padding: 0; }
            .actions { display: none; }
            .label { border: 1px solid #000; }
        }
    </style>
</head>
<body>

    <div class="label">
        <div class="label-row">
            <span class="key">S: </span><span class="val">{{ $component->serial ?? '—' }}</span>
        </div>
        <div class="label-row">
            <span class="key">M: </span><span class="val">{{ $component->model_number ?? '—' }}</span>
        </div>
        @if ($barcode_base64)
            <img class="barcode-img" src="data:image/png;base64,{{ $barcode_base64 }}" alt="{{ $barcode_value }}">
        @else
            <p style="color:red;font-size:11px;margin-top:6px;">No se pudo generar el código de barras</p>
        @endif
    </div>

    <div class="actions">
        <button class="btn btn-success" onclick="window.print()">&#128438; Imprimir</button>
        <a class="btn" href="{{ url()->previous() }}">&#8592; Volver</a>
    </div>

</body>
</html>
