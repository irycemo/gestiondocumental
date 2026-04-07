<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acuse</title>
</head>

<style>

    @page {
        size: A5 landscape;
    }

    header{
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 80px;
        text-align: center;
    }

    header img{
        height: 80px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }


    body{
        margin-top: 90px;
        margin-bottom: 20px;
        margin-left: auto;
        margin-right: auto;
        counter-reset: page;
        height: 100%;
        background-image: url("storage/img/escudo_fondo.png");
        background-size: contain;
        background-position:center;
        background-repeat: no-repeat;
        font-family: sans-serif;
        font-weight: normal;
        line-height: 1.5;
        text-transform: uppercase
    }

    .container{
        font-size: 10px;
        display: flex;
        align-content: space-around;
    }

    .tabla{
        width: 100%;
        font-size: 10px;
        margin-bottom: 30px;;
        margin-left: auto;
        margin-right: auto;
    }

    .borde{
        display: inline;
        border-top: 1px solid;
    }

    footer{
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        font-size: 10px;
        text-align: right;
        padding-right: 5px;
    }

</style>

<body>

    <header>

        <img src="{{ public_path('storage/img/encabezado.png') }}" alt="encabezado">

    </header>

    <footer>

        <div class="fot">
            <p><strong>Impreso por: </strong>{{ auth()->user()->name }}, el {{ now()->format('d-m-Y H:i:s') }}</p>
        </div>

    </footer>

    <main>

        <div class="container">

            <p style="text-align: center; font-weight: bold; font-size: 11px;">Acuse</p>

            <p><strong>Folio de entrada: </strong> {{ $entrada->folio }}</p>

            <p><strong>Número de oficio: </strong> {{ $entrada->numero_oficio }}</p>

            <p><strong>Asunto: </strong> {{ $entrada->asunto }}</p>

            <p><strong>Fecha de termino: </strong> {{ Carbon\Carbon::parse($entrada->fecha_termino)->format('d/m/Y') }}</p>

            <p><strong>Destinatario: </strong> {{ $entrada->destino->name }}</p>

            <p><strong>Asignado a: </strong></p>

            @foreach ($entrada->asignadoA as $asignado)

                {{ $asignado->name }},

            @endforeach

        </div>

    </main>
</body>
</html>
