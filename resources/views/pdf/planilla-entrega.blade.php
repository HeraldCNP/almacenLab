<!DOCTYPE html>
<html>
<head>
    <title>Planilla de Entrega</title>
    <style>
        @page { margin: 160px 25px 60px 25px; } /* Increased top margin for bigger header */
        body { font-family: sans-serif; font-size: 12px; }
        header { position: fixed; top: -140px; left: 0px; right: 0px; height: 120px; text-align: center; } /* Adjusted header position */
        footer { position: fixed; bottom: -40px; left: 0px; right: 0px; height: 30px; border-top: 1px solid #ddd; padding-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { margin: 10px 0 5px 0; color: #333; font-size: 18px; } /* Added margin to h1 */
        .signature-box { margin-top: 50px; text-align: center; float: right; width: 200px; }
        .signature-line { border-top: 1px solid #000; margin-top: 40px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/banner.jpg') }}" alt="Banner" style="width: 100%; height: auto; margin-bottom: 5px;">
        <h1>Planilla de Entrega de Materiales</h1>
        <p style="margin: 2px 0; font-size: 10px;">Del {{ $fechaInicio }} al {{ $fechaFin }}</p>
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
                    <th>Fecha</th>
                    <th>Material</th>
                    <th>Cantidad</th>
                    <th>Entregado Por</th>
                    <th>Recibido Por</th>
                    <th style="width: 100px;">Firma Receptor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    <tr>
                        <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $mov->lote->material->nombre_material }}</td>
                        <td>{{ $mov->cantidad }}</td>
                        <td>{{ $mov->user->name }}</td>
                        <td>{{ $mov->recibidoPor->name ?? 'N/A' }}</td>
                        <td></td> <!-- Empty for signature -->
                    </tr>
                @endforeach
            </tbody>
        </table>

    </main>

</body>
</html>
