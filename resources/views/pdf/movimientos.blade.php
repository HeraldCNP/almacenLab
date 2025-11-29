<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Movimientos</title>
    <style>
        @page { margin: 100px 25px; }
        body { font-family: sans-serif; font-size: 12px; }
        header { position: fixed; top: -60px; left: 0px; right: 0px; height: 80px; text-align: center; }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 40px; border-top: 1px solid #ddd; padding-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { margin: 0; color: #333; font-size: 18px; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 10px; color: white; }
        .entrada { background-color: #16a34a; }
        .salida { background-color: #dc2626; }
        .ajuste { background-color: #ca8a04; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/banner.jpg') }}" alt="Banner" style="height: 40px; width: auto; margin-bottom: 5px;">
        <h1>Movimientos del Mes ({{ now()->format('F Y') }})</h1>
        <p style="margin: 2px 0; font-size: 10px;">Generado el: {{ now()->format('d/m/Y H:i') }}</p>
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
                    <th>Tipo</th>
                    <th>Material</th>
                    <th>Cantidad</th>
                    <th>Usuario</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    <tr>
                        <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge {{ strtolower($mov->tipo) }}">
                                {{ $mov->tipo }}
                            </span>
                        </td>
                        <td>{{ $mov->lote->material->nombre_material }}</td>
                        <td>{{ $mov->cantidad }}</td>
                        <td>{{ $mov->user->name }}</td>
                        <td>{{ $mov->motivo ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

</body>
</html>
