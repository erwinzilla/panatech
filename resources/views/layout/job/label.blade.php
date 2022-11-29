<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Generate QC Label</title>
    @include('favicon')
    <style>
        body {
            font-family: 'Arial';
        }
        .container {
            display: flex;
            margin-bottom: 1rem;
        }
        .row {
            display: flex;
            min-width: 85mm;
            max-width: 85mm;
            min-height: 25mm;
            max-height: 25mm;
            border: 1px dashed red;
            margin-right: 1rem;
        }
        .col {
            padding: 2mm;
        }
        .col-desc {
            font-size: 6pt;
            border: 2px solid #000;
            width: 100%;
        }
    </style>
{{--    <link rel="stylesheet" href="{{ url('assets/css/app.min.css') }}">--}}
</head>
<body>
{{--<button onClick="window.print()" style="margin-bottom: 1rem;">Print this page »</button>--}}
@foreach($data as $row)
    @if($row->have_part)
        @php
            $parts = \App\Models\JobPart::where('job', $row->id)->get();

            foreach ($parts as $part) {
                $text = 'CABANG : '.ucwords(Auth::user()->branch_services->name).PHP_EOL.
                    'MODEL : '.$row->model.PHP_EOL.
                    'NO SERI : '.$row->serial.PHP_EOL.
                    'NAMA PART : '.$part->name.PHP_EOL.
                    'CODE PART : '.$part->sku.PHP_EOL.
                    'KERUSAKAN : '.$row->service_info.PHP_EOL.
                    'NOTA SERVICE : '.$row->name.PHP_EOL
                    ;
        @endphp
        <div class="container">
            <div class="row">
                <div class="col">
                    {!! QrCode::size(80)->generate($text); !!}
                </div>
                <div class="col-desc">
                    <table class="table">
                        <tr>
                            <td>Service Center</td>
                            <td>:</td>
                            <td>{{ ucwords(Auth::user()->branch_services->name) }}</td>
                        </tr>
                        <tr>
                            <td>No. Nota Svc</td>
                            <td>:</td>
                            <td>{{ $row->name }}</td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td>:</td>
                            <td>{{ $row->model }}</td>
                        </tr>
                        <tr>
                            <td>No. Seri</td>
                            <td>:</td>
                            <td>{{ $row->serial }}</td>
                        </tr>
                        <tr>
                            <td>Nama Part</td>
                            <td>:</td>
                            <td>{{ $part->name }}</td>
                        </tr>
                        <tr>
                            <td>Kode Part</td>
                            <td>:</td>
                            <td>{{ $part->sku }}</td>
                        </tr>
                        <tr>
                            <td>Kerusakan</td>
                            <td>:</td>
                            <td>{{ $row->service_info }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    {!! QrCode::size(80)->generate($text); !!}
                </div>
                <div class="col-desc">
                    <table class="table">
                        <tr>
                            <td>Service Center</td>
                            <td>:</td>
                            <td>{{ ucwords(Auth::user()->branch_services->name) }}</td>
                        </tr>
                        <tr>
                            <td>No. Nota Svc</td>
                            <td>:</td>
                            <td>{{ $row->name }}</td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td>:</td>
                            <td>{{ $row->model }}</td>
                        </tr>
                        <tr>
                            <td>No. Seri</td>
                            <td>:</td>
                            <td>{{ $row->serial }}</td>
                        </tr>
                        <tr>
                            <td>Nama Part</td>
                            <td>:</td>
                            <td>{{ $part->name }}</td>
                        </tr>
                        <tr>
                            <td>Kode Part</td>
                            <td>:</td>
                            <td>{{ $part->sku }}</td>
                        </tr>
                        <tr>
                            <td>Kerusakan</td>
                            <td>:</td>
                            <td>{{ $row->service_info }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @php
            }
        @endphp
    @endif
@endforeach

</body>
</html>