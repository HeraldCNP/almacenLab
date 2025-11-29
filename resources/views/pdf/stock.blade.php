<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Stock</title>
    <style>
        @page { margin: 100px 25px; }
        body { font-family: sans-serif; font-size: 12px; }
        header { position: fixed; top: -60px; left: 0px; right: 0px; height: 80px; text-align: center; }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 40px; border-top: 1px solid #ddd; padding-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { margin: 0; color: #333; font-size: 18px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/banner.jpg') }}" alt="Banner" style="height: 40px; width: auto; margin-bottom: 5px;">
        <h1>Reporte de Stock Actual</h1>
        <p style="margin: 2px 0; font-size: 10px;">Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    </header>

    <footer>
        <table style="width: 100%; border: none; margin-top: 0;">
            <tr>
                <td style="border: none; text-align: left; font-size: 10px;">
                    Generado por: {{ auth()->user()->name }}
                </td>
                <td style="border: none; text-align: right; font-size: 10px;">
                    Página <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </footer>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Material</th>
                    <th>Categoría</th>
                    <th>Stock Actual</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materiales as $material)
                    <tr>
                        <td>{{ $material->codigo ?? '-' }}</td>
                        <td>{{ $material->nombre_material }}</td>
                        <td>{{ $material->categoria->nombre_categoria }}</td>
                        <td>{{ $material->stock_actual }}</td>
                        <td>{{ $material->unidadMedida->abreviatura }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

</body>
</html>
