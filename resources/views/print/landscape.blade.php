<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comanda</title>
    <style>
        .orange {
            background-color: rgba(231, 179, 21, 0.6);
        }
        .grey {
            background-color: rgba(163, 162, 157, 0.6)
        }
        .green {
            background-color: rgba(154, 238, 105, 0.6)
        }
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
            width: 40%;
        }
        #observations {
            width: 60%;
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

        <!-- start of column 2 -->
        <div class="observations">
            {!! $order->observations !!}
        </div>
        <!-- end of column 2 -->
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
                        <td class="orange">{{ $item->article->name }}</td>
                        <td class="orange">{{ $item->refinements_list }}</td>
                        <td class="orange">{{ $item->article->quality->name }}</td>
                        <td class="orange">{{ $item->thickness }}</td>
                        <td class="orange">{{ $item->width }}</td>
                        <td class="orange">{{ $item->length }}</td>
                        <td class="orange">{{ $item->pcs }}</td>
                        <td class="grey">{{ $item->produced_ticom }}</td>
                        <td class="green">{{ $item->volume }}</td>
                        <td class="grey">{{ $item->pcs_height }}</td>
                        <td class="grey">{{ $item->rows }}</td>
                        <td class="orange">{{ $item->label }}</td>
                        @if ($fields != [])
                            @foreach ($fields as $field)
                                <td class="orange">{{ $item[$field] }}</td>
                            @endforeach
                        @endif
                        <td class="orange">{{ $item->pal }}</td>
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