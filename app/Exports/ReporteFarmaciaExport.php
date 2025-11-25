<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteFarmaciaExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected string $tipoReporte;
    protected array $datos;
    protected array $totales;

    public function __construct(string $tipoReporte, array $datos, array $totales)
    {
        $this->tipoReporte = $tipoReporte;
        $this->datos = $datos;
        $this->totales = $totales;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->datos as $fila) {
            $rows[] = match ($this->tipoReporte) {
                'consumo' => [
                    $fila['codigo'],
                    $fila['nombre'],
                    $fila['categoria'],
                    $fila['cantidad_total'],
                    number_format($fila['costo_total'], 2),
                    $fila['movimientos'],
                ],
                'costos' => [
                    $fila['area'],
                    number_format($fila['total_costo'], 2),
                    $fila['total_unidades'],
                    number_format($fila['costo_promedio_unidad'], 2),
                    $fila['movimientos'],
                ],
                'desperdicios' => [
                    $fila['tipo'],
                    $fila['medicamento'],
                    $fila['lote'],
                    $fila['cantidad'],
                    number_format($fila['valor_perdido'], 2),
                    $fila['fecha'],
                    $fila['motivo'],
                ],
                'controlados' => [
                    $fila['fecha'],
                    $fila['medicamento'],
                    $fila['tipo_operacion'],
                    $fila['cantidad'],
                    $fila['paciente'] ?? '',
                    $fila['numero_receta'] ?? '',
                    $fila['usuario'],
                    $fila['autorizado_por'],
                    $fila['justificacion'] ?? '',
                ],
                'inventario' => [
                    $fila['codigo'],
                    $fila['nombre'],
                    $fila['es_controlado'] ? 'Sí' : 'No',
                    $fila['stock_total'],
                    $fila['stock_minimo'],
                    number_format($fila['valor_inventario'], 2),
                    $fila['lotes'],
                    $fila['proxima_caducidad'],
                    $fila['dias_para_caducar'],
                    $fila['estado_stock'] === 'bajo' ? 'BAJO' : 'Normal',
                ],
                'movimientos' => [
                    $fila['fecha'],
                    $fila['tipo'],
                    $fila['medicamento'],
                    $fila['lote'],
                    $fila['cantidad'],
                    $fila['cantidad_anterior'],
                    $fila['cantidad_nueva'],
                    $fila['area_origen'],
                    $fila['area_destino'],
                    $fila['referencia'] ?? '',
                    $fila['usuario'],
                    $fila['motivo'] ?? '',
                ],
                default => array_values($fila),
            };
        }

        return $rows;
    }

    public function headings(): array
    {
        return match ($this->tipoReporte) {
            'consumo' => ['Código', 'Medicamento', 'Categoría', 'Cantidad', 'Costo Total', 'Movimientos'],
            'costos' => ['Área', 'Costo Total', 'Unidades', 'Costo Promedio', 'Movimientos'],
            'desperdicios' => ['Tipo', 'Medicamento', 'Lote', 'Cantidad', 'Valor Perdido', 'Fecha', 'Motivo'],
            'controlados' => ['Fecha', 'Medicamento', 'Operación', 'Cantidad', 'Paciente', 'No. Receta', 'Usuario', 'Autorizado Por', 'Justificación'],
            'inventario' => ['Código', 'Medicamento', 'Controlado', 'Stock', 'Stock Mín.', 'Valor', 'Lotes', 'Próx. Caducidad', 'Días para Caducar', 'Estado'],
            'movimientos' => ['Fecha', 'Tipo', 'Medicamento', 'Lote', 'Cantidad', 'Cant. Anterior', 'Cant. Nueva', 'Área Origen', 'Área Destino', 'Referencia', 'Usuario', 'Motivo'],
            default => [],
        };
    }

    public function title(): string
    {
        return match ($this->tipoReporte) {
            'consumo' => 'Consumo por Medicamento',
            'costos' => 'Costos por Área',
            'desperdicios' => 'Desperdicios',
            'controlados' => 'Med. Controlados',
            'inventario' => 'Estado Inventario',
            'movimientos' => 'Movimientos',
            default => 'Reporte',
        };
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE5E7EB'],
                ],
            ],
        ];
    }
}
