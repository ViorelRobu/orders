<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comanda</title>
    <style>
        p {
            margin: 1px;
        }
        table, td, th {
            border: 1px solid black;
        }
        .headings {
            width: 100%;
            height: 180px;
        }
        #number {
            margin-top: 10px;
            font-weight: bold;
            font-size: 250%;
        }
        .headings div {
            display: inline-block;
        }
        #order_details {
            width: 100%;
        }
        #observations {
            width: 100%;
            font-size: 90%;
        }
        .details_table {
            border-collapse: collapse;
            font-size: 75%;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        .details_table td {
            padding: 3px;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="headings">
        <!-- start of column 1 -->
        <div id="order_details">
            <div id="number">{{ $order->order }}</div>
            <table class="basic_details">
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

    </div>

    <hr>

    <!-- start of the details table -->
    <div class="details">
        <table class="details_table">
            <thead>
                <tr>
                    <th></th>
                    <th>Articol</th>
                    <th>Finisaje</th>
                    <th>Cal</th>
                    <th>Gros</th>
                    <th>Lat</th>
                    <th>Lung</th>
                    <th>Buc</th>
                    <th>Prod</th>
                    <th>Volum</th>
                    <th>Buc/H</th>
                    <th>Randuri</th>
                    <th>Eticheta</th>
                    @if ($fields != [])
                        @foreach ($fields as $field)
                            <th>{{ $field }}</th>
                        @endforeach
                    @endif
                    <th>Palet</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $item)
                    <tr>
                        <td>{{ $item->index }}</td>
                        <td>{{ $item->article->name }}</td>
                        <td>{{ $item->refinements_list }}</td>
                        <td>{{ $item->article->quality->name }}</td>
                        <td>{{ $item->thickness }}</td>
                        <td>{{ $item->width }}</td>
                        <td>{{ $item->length }}</td>
                        <td>{{ $item->pcs }}</td>
                        <td>{{ $item->preoduced_ticom }}</td>
                        <td>{{ $item->volume }}</td>
                        <td>{{ $item->pcs_height }}</td>
                        <td>{{ $item->rows }}</td>
                        <td>{{ $item->label }}</td>
                        @if ($fields != [])
                            @foreach ($fields as $field)
                                <td>{{ $item[$field] }}</td>
                            @endforeach
                        @endif
                        <td>{{ $item->pal }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- end of the details table -->

    <hr>

    <div class="total">
        TOTAL COMANDA: <span style="color: red">{{ $total }}</span> m&sup3;
    </div>

    <hr>

    <!-- start of column 2 -->
    <div class="observations">
        {!! $order->observations !!}
    </div>
    <!-- end of column 2 -->

</body>
</html>
