<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class OrdersController extends Controller
{

    /**
     * Load the view for the orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('orders.index');
    }

    public function fetch()
    {
        $keys = [
            '`orders`.`id` as `id`',
            '`customers`.`name` as `name`',
            '`orders`.`au` as `au`',
            '`orders`.`destination` as `destination`',
            '`orders`.`production` as `production`',
            '`orders`.`loading` as `loading`',
            '`orders`.`month` as `month`',

        ];
        $orders = Order::join('customers', 'customers.id', '=', 'orders.customer_id')->selectRaw(implode(', ', $keys))->get();

        return DataTables::of($orders)->make(true);
    }


}
