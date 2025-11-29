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
}
