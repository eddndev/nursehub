<?php

namespace App\Services;

use App\Models\Certificacion;
use Illuminate\Support\Facades\View;

class CertificacionPDFService
{
    /**
     * Genera el PDF de una certificación
     *
     * @param Certificacion $certificacion
     * @return mixed PDF content or path
     */
    public function generarPDF(Certificacion $certificacion)
    {
        // Cargar las relaciones necesarias
        $certificacion->load([
            'inscripcion.enfermero.user',
            'inscripcion.enfermero.area',
            'inscripcion.actividad.area',
            'emitidoPor'
        ]);

        // Generar el HTML del certificado
        $html = $this->generarHTML($certificacion);

        // TODO: Cuando se instale dompdf, descomentar:
        // $pdf = \PDF::loadHTML($html);
        // $pdf->setPaper('A4', 'landscape');
        // return $pdf;

        // Por ahora, retornar el HTML (para pruebas)
        return $html;
    }

    /**
     * Descarga el PDF de una certificación
     *
     * @param Certificacion $certificacion
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function descargarPDF(Certificacion $certificacion, $filename = null)
    {
        $filename = $filename ?? "certificado-{$certificacion->numero_certificado}.pdf";

        $html = $this->generarPDF($certificacion);

        // TODO: Cuando se instale dompdf:
        // return $pdf->download($filename);

        // Por ahora, retornar HTML
        return response($html, 200)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Visualiza el PDF en el navegador
     *
     * @param Certificacion $certificacion
     * @return \Illuminate\Http\Response
     */
    public function visualizarPDF(Certificacion $certificacion)
    {
        $html = $this->generarPDF($certificacion);

        // TODO: Cuando se instale dompdf:
        // return $pdf->stream("certificado-{$certificacion->numero_certificado}.pdf");

        // Por ahora, retornar HTML
        return response($html, 200)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Genera el HTML del certificado
     *
     * @param Certificacion $certificacion
     * @return string
     */
    protected function generarHTML(Certificacion $certificacion)
    {
        $enfermero = $certificacion->inscripcion->enfermero;
        $actividad = $certificacion->inscripcion->actividad;

        $data = [
            'certificacion' => $certificacion,
            'enfermero' => $enfermero,
            'actividad' => $actividad,
        ];

        return View::make('pdfs.certificacion', $data)->render();
    }

    /**
     * Genera PDFs en lote para múltiples certificaciones
     *
     * @param \Illuminate\Support\Collection $certificaciones
     * @return array Array de PDFs generados
     */
    public function generarLote($certificaciones)
    {
        $pdfs = [];

        foreach ($certificaciones as $certificacion) {
            $pdfs[] = [
                'certificacion_id' => $certificacion->id,
                'numero_certificado' => $certificacion->numero_certificado,
                'pdf' => $this->generarPDF($certificacion),
            ];
        }

        return $pdfs;
    }

    /**
     * Regenera el PDF de una certificación existente
     *
     * @param Certificacion $certificacion
     * @return mixed
     */
    public function regenerarPDF(Certificacion $certificacion)
    {
        // Actualizar la fecha de emisión si es necesario
        $certificacion->update([
            'emitido_at' => now(),
        ]);

        return $this->generarPDF($certificacion);
    }
}
