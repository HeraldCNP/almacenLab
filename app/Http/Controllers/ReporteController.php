<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Movimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function stock()
    {
        $materiales = Material::with(['categoria', 'unidadMedida'])
            ->orderBy('nombre_material')
            ->get();

        $pdf = Pdf::loadView('pdf.stock', compact('materiales'));
        return $pdf->stream('reporte-stock.pdf');
    }

    public function movimientos()
    {
        $movimientos = Movimiento::with(['lote.material', 'user'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('pdf.movimientos', compact('movimientos'));
        return $pdf->stream('reporte-movimientos.pdf');
    }

    public function planillaEntrega(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $movimientos = Movimiento::with(['lote.material', 'user', 'recibidoPor'])
            ->where('tipo', 'salida')
            ->whereDate('created_at', '>=', $request->fecha_inicio)
            ->whereDate('created_at', '<=', $request->fecha_fin)
            ->latest()
            ->get();

        $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio)->format('d/m/Y');
        $fechaFin = \Carbon\Carbon::parse($request->fecha_fin)->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.planilla-entrega', compact('movimientos', 'fechaInicio', 'fechaFin'));
        return $pdf->stream('planilla-entrega.pdf');
    }
}
