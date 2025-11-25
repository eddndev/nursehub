<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            opacity: 0.9;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
        .meta-info span {
            font-size: 10px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #4F46E5;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .stats-grid {
            display: table;
            width: 100%;
        }
        .stat-row {
            display: table-row;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-green {
            background-color: #DEF7EC;
            color: #03543F;
        }
        .badge-red {
            background-color: #FDE8E8;
            color: #9B1C1C;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 20px;
            background-color: #f3f4f6;
            font-size: 9px;
            color: #666;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>NurseHub - Sistema de Gestion de Enfermeria</p>
    </div>

    <div class="meta-info">
        <span>
            <strong>Periodo:</strong>
            @if($fechaInicio && $fechaFin)
                {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
            @elseif($fechaInicio)
                Desde {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}
            @elseif($fechaFin)
                Hasta {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
            @else
                Todo el periodo
            @endif
        </span>
        <span>
            <strong>Generado:</strong> {{ $fechaGeneracion->format('d/m/Y H:i') }}
        </span>
    </div>

    @if($tipo === 'general')
        <div class="section">
            <h2 class="section-title">Resumen Ejecutivo</h2>

            <div class="stats-grid">
                <div class="stat-row">
                    <div class="stat-box">
                        <div class="stat-value">{{ $datos['totalActividades'] }}</div>
                        <div class="stat-label">Total Actividades</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">{{ $datos['actividadesCompletadas'] }}</div>
                        <div class="stat-label">Completadas</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">{{ $datos['actividadesEnCurso'] }}</div>
                        <div class="stat-label">En Curso</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">{{ $datos['tasaAprobacion'] }}%</div>
                        <div class="stat-label">Tasa Aprobacion</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Detalle de Metricas</h2>

            <table>
                <thead>
                    <tr>
                        <th style="width: 70%">Metrica</th>
                        <th style="width: 30%" class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total de Inscripciones</td>
                        <td class="text-right">{{ $datos['totalInscripciones'] }}</td>
                    </tr>
                    <tr>
                        <td>Inscripciones Aprobadas</td>
                        <td class="text-right">{{ $datos['inscripcionesAprobadas'] }}</td>
                    </tr>
                    <tr>
                        <td>Inscripciones Pendientes</td>
                        <td class="text-right">{{ $datos['inscripcionesPendientes'] }}</td>
                    </tr>
                    <tr>
                        <td>Certificaciones Generadas</td>
                        <td class="text-right">{{ $datos['totalCertificaciones'] }}</td>
                    </tr>
                    <tr>
                        <td>Certificaciones Vigentes</td>
                        <td class="text-right">{{ $datos['certificacionesVigentes'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Horas Certificadas</strong></td>
                        <td class="text-right"><strong>{{ $datos['totalHoras'] }} horas</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @if($tipo === 'por_area')
        <div class="section">
            <h2 class="section-title">Capacitacion por Area</h2>

            <table>
                <thead>
                    <tr>
                        <th>Area</th>
                        <th class="text-center">Actividades</th>
                        <th class="text-center">Enfermeros</th>
                        <th class="text-center">Inscripciones</th>
                        <th class="text-center">Aprobadas</th>
                        <th class="text-center">Certificaciones</th>
                        <th class="text-right">Horas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $row)
                        <tr>
                            <td>{{ $row['area'] }}</td>
                            <td class="text-center">{{ $row['totalActividades'] }}</td>
                            <td class="text-center">{{ $row['enfermerosCapacitados'] }}</td>
                            <td class="text-center">{{ $row['inscripciones'] }}</td>
                            <td class="text-center">{{ $row['aprobadas'] }}</td>
                            <td class="text-center">{{ $row['certificaciones'] }}</td>
                            <td class="text-right">{{ $row['horasCertificadas'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($tipo === 'certificaciones')
        <div class="section">
            <h2 class="section-title">Listado de Certificaciones</h2>

            <table>
                <thead>
                    <tr>
                        <th>No. Certificado</th>
                        <th>Enfermero</th>
                        <th>Actividad</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Horas</th>
                        <th class="text-center">Calif.</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $cert)
                        <tr>
                            <td style="font-family: monospace; font-size: 9px;">{{ $cert['numeroCertificado'] }}</td>
                            <td>{{ Str::limit($cert['enfermero'], 20) }}</td>
                            <td>{{ Str::limit($cert['actividad'], 25) }}</td>
                            <td class="text-center">{{ $cert['fechaEmision'] }}</td>
                            <td class="text-center">{{ $cert['horas'] }}</td>
                            <td class="text-center">{{ $cert['calificacion'] }}</td>
                            <td class="text-center">
                                <span class="badge {{ $cert['vigente'] ? 'badge-green' : 'badge-red' }}">
                                    {{ $cert['vigente'] ? 'Vigente' : 'Vencido' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="font-size: 10px; color: #666; margin-top: 10px;">
                Total de certificaciones: {{ count($datos) }}
            </p>
        </div>
    @endif

    <div class="footer">
        NurseHub - Reporte generado automaticamente | {{ $fechaGeneracion->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
