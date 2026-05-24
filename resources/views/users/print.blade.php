<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @if ((isset($users) && count($users) === 1))
        <title>{{ trans('general.assigned_to', ['name' => $users[0]->display_name]) }} - {{ date('Y-m-d H:i', time()) }}</title>
    @else
        <title>{{ trans('admin/users/general.print_assigned') }} - {{ date('Y-m-d H:i', time()) }}</title>
    @endisset

    <link rel="shortcut icon" type="image/ico" href="{{ ($snipeSettings) && ($snipeSettings->favicon!='') ?  Storage::disk('public')->url(e($snipeSettings->favicon)) : config('app.url').'/favicon.ico' }}">
    <link rel="stylesheet" href="{{ url(mix('css/dist/bootstrap-table.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('css/dist/all.css')) }}">

    <script nonce="{{ csrf_token() }}">
        window.snipeit = { settings: { "per_page": 50 } };
    </script>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
        }

        /* Header con logo */
        .doc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px 14px 40px;
            border-bottom: 3px solid #1a6e7a;
        }

        .doc-header img {
            height: 70px;
            width: auto;
        }

        .doc-header-info {
            text-align: right;
            font-size: 10px;
            color: #555;
            line-height: 1.5;
        }

        .content {
            padding: 24px 40px 40px 40px;
        }

        @media print {
            .doc-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            table.inventory th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 18px 0 20px 0;
            letter-spacing: 0.5px;
        }

        .doc-body {
            text-align: justify;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        /* Assets table */
        table.inventory {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 0;
            font-size: 11px;
        }

        table.inventory th {
            background-color: #1a6e7a;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ccc;
        }

        table.inventory td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        table.inventory tbody tr:nth-child(even) {
            background-color: #f4f9f9;
        }

        .section-title {
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 4px;
        }

        .conditions {
            text-align: justify;
            line-height: 1.7;
            margin-bottom: 8px;
        }

        .conditions-list {
            padding-left: 20px;
            margin: 6px 0 10px 0;
        }

        .conditions-list li {
            text-align: justify;
            line-height: 1.7;
            margin-bottom: 6px;
        }

        /* Signature area */
        .signatures {
            margin-top: 40px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .sig-block {
            flex: 1;
            min-width: 200px;
        }

        .sig-line {
            border-bottom: 1px solid #333;
            margin-bottom: 4px;
            height: 40px;
        }

        .sig-label {
            font-size: 11px;
            color: #444;
        }

        .sig-fecha {
            margin-top: 10px;
            font-size: 12px;
        }

        @media print {
            .hidden-print { display: none !important; }
            .page { page-break-after: always; }
        }
    </style>
</head>
<body>

@php $count = 0; @endphp

@foreach ($users as $show_user)
@php $count++; @endphp

<div class="page">

    {{-- Header con logo --}}
    <div class="doc-header">
        <img src="{{ asset('images/letterhead/zooma-logo.png') }}" alt="Logo">
        <div class="doc-header-info">
            Generado: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="content">

        {{-- Título --}}
        <div class="doc-title">
            Autorización de descuento por entrega de equipos tecnológicos
        </div>

        {{-- Párrafo principal --}}
        <p class="doc-body">
            Yo, <strong>{{ $show_user->display_name }}</strong>
            @if ($show_user->employee_num != '')
                , con DNI <strong>{{ $show_user->employee_num }}</strong>
            @endif
            @if ($show_user->company)
                , empleado(a) de <strong>{{ $show_user->company->name }}</strong> identificada con RUC <strong>{{ $show_user->company->ruc }}</strong>
            @endif
            , autorizo a la empresa a realizar un descuento en mi remuneración en caso de pérdida,
            daño irreparable o no devolución de los equipos tecnológicos que se me entrega el día de hoy,
            <strong>{{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}</strong>, consistente en:
        </p>

        {{-- Tabla de activos --}}
        @if ($show_user->assets->count() > 0)
            @php $counter = 1; @endphp
            <table class="inventory">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Asset Tag</th>
                        <th>Categoría</th>
                        <th>Modelo</th>
                        <th>Serie</th>
                        <th>Costo</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($show_user->assets as $asset)
                    @php
                        if (($asset->model->category) && ($asset->model->category->getEula())) $eulas[] = $asset->model->category->getEula();
                    @endphp
                    <tr>
                        <td>{{ $counter }}</td>
                        <td>{{ $asset->asset_tag }}</td>
                        <td>{{ (($asset->model) && ($asset->model->category)) ? $asset->model->category->name : '' }}</td>
                        <td>{{ ($asset->model) ? $asset->model->name : '' }}</td>
                        <td>{{ $asset->serial }}</td>
                        <td>{{ $asset->purchase_cost ? 'S/ '.number_format($asset->purchase_cost, 2) : '' }}</td>
                    </tr>
                    @php $counter++; @endphp
                @endforeach
                </tbody>
            </table>
        @endif

        {{-- Condiciones --}}
        <p class="section-title">Condiciones:</p>
        <ul class="conditions-list">
            <li>
                Los equipos tecnológicos entregados son propiedad de
                @if ($show_user->company)
                    <strong>{{ $show_user->company->name }}</strong>
                @else
                    la empresa
                @endif
                y su uso es obligatorio durante el desempeño de mis funciones.
                Me comprometo a cuidar y mantener en buen estado los equipos recibidos; al momento de la devolución no estarán dañados.
            </li>
            <li>
                En caso de pérdida, daño irreparable o no devolución de los equipos al término de mi relación laboral,
                autorizo a la empresa a descontar de mi liquidación o remuneración el valor total de los equipos
                indicados anteriormente en un solo pago.
            </li>
        </ul>

        {{-- Aceptación --}}
        <p class="section-title">Aceptación:</p>
        <p class="conditions">
            He leído y comprendo los términos y condiciones de esta autorización y acepto el descuento
            en caso de pérdida, daño irreparable o no devolución de los equipos.
        </p>

        {{-- Firmas --}}
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma del colaborador</div>
                <div class="sig-fecha">Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma del responsable de la entrega</div>
                <br>
                <div class="sig-line"></div>
                <div class="sig-label">Responsable de la entrega</div>
            </div>
        </div>

    </div>{{-- /content --}}

</div>{{-- /page --}}

@endforeach

{{-- Javascript --}}
<script src="{{ url(mix('js/dist/all.js')) }}" nonce="{{ csrf_token() }}"></script>
<script src="{{ url(mix('js/dist/bootstrap-table.js')) }}"></script>
<script src="{{ url(mix('js/dist/bootstrap-table-locale-all.min.js')) }}"></script>
<script src="{{ url(mix('js/dist/bootstrap-table-en-US.min.js')) }}"></script>

</body>
</html>
