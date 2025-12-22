<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Stock</title>
    <style>
        @page { margin: 160px 25px 60px 25px; }
        body { font-family: sans-serif; font-size: 12px; }
        header { position: fixed; top: -140px; left: 0px; right: 0px; height: 120px; text-align: center; }
        footer { position: fixed; bottom: -40px; left: 0px; right: 0px; height: 30px; border-top: 1px solid #ddd; padding-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { margin: 10px 0 5px 0; color: #333; font-size: 18px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/banner.jpg') }}" alt="Banner" style="width: 100%; height: auto; margin-bottom: 5px;">
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
                    <th>Unidad</th>
                    <th>Categoría</th>
                    <th style="text-align: right;">Stock Actual</th>
                    <th style="text-align: right;">Valor Total (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($materiales as $material)
                    @php
                        $totalMaterial = $material->lotes->sum(function($lote) {
                            return $lote->cantidad_disponible * $lote->precio_compra;
                        });
                        $grandTotal += $totalMaterial;
                    @endphp
                    <tr>
                        <td>{{ $material->codigo ?? '-' }}</td>
                        <td>{{ $material->nombre_material }}</td>
                        <td>{{ $material->unidadMedida->abreviatura }}</td>
                        <td>{{ $material->categoria->nombre_categoria }}</td>
                        <td style="text-align: right;">{{ $material->stock_actual }}</td>
                        <td style="text-align: right;">{{ Number::currency($totalMaterial, in: 'BOB', locale: 'es_BO') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL GENERAL:</td>
                    <td style="text-align: right; font-weight: bold;">{{ Number::currency($grandTotal, in: 'BOB', locale: 'es_BO') }}</td>
                </tr>
            </tbody>
        </table>
    </main>

</body>
</html>
