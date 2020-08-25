<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comanda</title>
    <style>
        #number {
            font-weight: bold;
            font-size: 250%;
            margin-bottom: 15px;
            margin-left: 60px;
            text-align: left;
        }
        .column-1 {
            position: absolute;
            left: 0px;
            width: 450px;
        }
        .column-2 {
            margin-left: 460px;
        }
        .label {
            width: 120px;
            text-align: center;
        }
        .text {
            width: 300px;
        }

        .column-2 p {
            margin: 2px;

        }
    </style>
</head>
<body>
    <!-- start of column 1 -->
    <div class="column-1">
        <div id="number">{{ $order->order }}</div>
        <table>
            <tr>
                <td class="label"><i>Client:</i></td>
                <td>{{ strtoupper($order->customer->name) }}</td>
            </tr>
            <tr>
                <td class="label"><i>FIBU:</i></td>
                <td>{{ strtoupper($order->customer->fibu) }}</td>
            </tr>
            <tr>
                <td class="label"><i>Auftrag:</i></td>
                <td>{{ strtoupper($order->auftrag) }}</td>
            </tr>
            <tr>
                <td class="label"><i>Comanda client:</i></td>
                <td>{{ strtoupper($order->customer_order) }}</td>
            </tr>
            <tr>
                <td class="label"><i>Destinatie:</i></td>
                <td>{{ $order->destination->address . ', ' . $order->destination->country->name }}</td>
            </tr>
        </table>
    </div>
    <!-- end of column 1 -->

    <!-- start of column 1 -->
    <div class="column-2">
        {!! $order->observations !!}
    </div>
    <!-- end of column 2 -->

    <hr>
</body>
</html>
