<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $certificacion->numero_certificado }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 297mm;
            height: 210mm;
            padding: 20mm;
            position: relative;
        }

        .certificate-container {
            background: white;
            width: 100%;
            height: 100%;
            position: relative;
            padding: 40px 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 15px solid transparent;
            border-image: linear-gradient(135deg, #667eea, #764ba2, #f093fb, #f5576c) 1;
        }

        .decorative-corner {
            position: absolute;
            width: 100px;
            height: 100px;
            border-style: solid;
        }

        .corner-tl {
            top: 20px;
            left: 20px;
            border-width: 3px 0 0 3px;
            border-color: #667eea;
        }

        .corner-tr {
            top: 20px;
            right: 20px;
            border-width: 3px 3px 0 0;
            border-color: #764ba2;
        }

        .corner-bl {
            bottom: 20px;
            left: 20px;
            border-width: 0 0 3px 3px;
            border-color: #f5576c;
        }

        .corner-br {
            bottom: 20px;
            right: 20px;
            border-width: 0 3px 3px 0;
            border-color: #f093fb;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .institution-name {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .institution-subtitle {
            font-size: 16px;
            color: #666;
            font-style: italic;
        }

        .certificate-title {
            text-align: center;
            margin: 30px 0;
        }

        .certificate-title h1 {
            font-size: 48px;
            font-weight: bold;
            color: #764ba2;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 10px;
        }

        .certificate-title .subtitle {
            font-size: 20px;
            color: #555;
            font-style: italic;
        }

        .recipient-section {
            text-align: center;
            margin: 40px 0;
        }

        .recipient-section .label {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }

        .recipient-section .name {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #667eea;
            display: inline-block;
            padding: 10px 40px;
            margin: 10px 0;
        }

        .content-section {
            text-align: center;
            margin: 30px 0;
            line-height: 1.8;
        }

        .content-section p {
            font-size: 16px;
            color: #444;
            margin-bottom: 10px;
        }

        .activity-name {
            font-size: 24px;
            font-weight: bold;
            color: #764ba2;
            margin: 20px 0;
            padding: 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 10px;
        }

        .details-grid {
            display: table;
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }

        .details-row {
            display: table-row;
        }

        .detail-item {
            display: table-cell;
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }

        .footer-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .signatures {
            display: table;
            width: 100%;
            margin-top: 30px;
        }

        .signature {
            display: table-cell;
            text-align: center;
            padding: 0 20px;
        }

        .signature-line {
            border-top: 2px solid #333;
            width: 200px;
            margin: 0 auto 10px;
        }

        .signature-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .signature-title {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }

        .certificate-number {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 8px;
        }

        .certificate-number .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .certificate-number .number {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 2px;
        }

        .hash-section {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .hash-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .hash-value {
            font-family: 'Courier New', monospace;
            font-size: 8px;
            color: #333;
            word-break: break-all;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(102, 126, 234, 0.05);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        {{-- Decorative corners --}}
        <div class="decorative-corner corner-tl"></div>
        <div class="decorative-corner corner-tr"></div>
        <div class="decorative-corner corner-bl"></div>
        <div class="decorative-corner corner-br"></div>

        {{-- Watermark --}}
        <div class="watermark">CERTIFICADO</div>

        {{-- Header --}}
        <div class="header">
            <div class="institution-name">NurseHub Hospital</div>
            <div class="institution-subtitle">Sistema de Gestión de Enfermería</div>
        </div>

        {{-- Certificate Title --}}
        <div class="certificate-title">
            <h1>Certificado</h1>
            <div class="subtitle">de Capacitación Profesional</div>
        </div>

        {{-- Recipient --}}
        <div class="recipient-section">
            <div class="label">Otorgado a:</div>
            <div class="name">{{ strtoupper($enfermero->user->name) }}</div>
        </div>

        {{-- Content --}}
        <div class="content-section">
            <p>Por haber completado satisfactoriamente el programa de capacitación:</p>
            <div class="activity-name">{{ $actividad->titulo }}</div>
            <p>{{ $actividad->tipo->getLabel() }}</p>
        </div>

        {{-- Details Grid --}}
        <div class="details-grid">
            <div class="details-row">
                <div class="detail-item">
                    <span class="detail-label">Horas Certificadas</span>
                    <span class="detail-value">{{ $certificacion->horas_certificadas }}h</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Asistencia</span>
                    <span class="detail-value">{{ number_format($certificacion->porcentaje_asistencia, 1) }}%</span>
                </div>
                @if($certificacion->calificacion_obtenida)
                <div class="detail-item">
                    <span class="detail-label">Calificación</span>
                    <span class="detail-value">{{ $certificacion->calificacion_obtenida }}</span>
                </div>
                @endif
                <div class="detail-item">
                    <span class="detail-label">Área</span>
                    <span class="detail-value">{{ $enfermero->area->nombre }}</span>
                </div>
            </div>
            <div class="details-row">
                <div class="detail-item">
                    <span class="detail-label">Fecha de Emisión</span>
                    <span class="detail-value">{{ $certificacion->fecha_emision->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item" style="width: 33.33%">
                    <span class="detail-label">Período de Vigencia</span>
                    <span class="detail-value">
                        {{ $certificacion->fecha_vigencia_inicio->format('d/m/Y') }}
                        @if($certificacion->fecha_vigencia_fin)
                            - {{ $certificacion->fecha_vigencia_fin->format('d/m/Y') }}
                        @else
                            - Indefinido
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Duración del Programa</span>
                    <span class="detail-value">{{ $actividad->fecha_inicio->format('d/m/Y') }} - {{ $actividad->fecha_fin->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        @if($certificacion->competencias_desarrolladas)
        <div class="content-section">
            <p><strong>Competencias Desarrolladas:</strong></p>
            <p style="font-size: 14px;">{{ $certificacion->competencias_desarrolladas }}</p>
        </div>
        @endif

        {{-- Signatures --}}
        <div class="footer-section">
            <div class="signatures">
                @if($certificacion->emitidoPor)
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $certificacion->emitidoPor->name }}</div>
                    <div class="signature-title">Coordinador de Capacitación</div>
                </div>
                @endif
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">Dirección de Enfermería</div>
                    <div class="signature-title">NurseHub Hospital</div>
                </div>
            </div>
        </div>

        {{-- Certificate Number --}}
        <div class="certificate-number">
            <div class="label">Número de Certificado</div>
            <div class="number">{{ $certificacion->numero_certificado }}</div>
        </div>

        {{-- Hash Verification --}}
        <div class="hash-section">
            <div class="hash-label">Hash de Verificación (SHA-256)</div>
            <div class="hash-value">{{ $certificacion->hash_verificacion }}</div>
        </div>
    </div>
</body>
</html>
