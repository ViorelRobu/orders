<?php

namespace App\Http\Controllers;

use App\Article;
use App\Customer;
use App\Order;
use App\OrderDetail;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class OrdersController extends Controller
{

    /**
     * Load the view for the orders
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $customers = Customer::all();

        return view('orders.index', ['customers' => $customers]);
    }

    /**
     * Fetch all the active orders
     *
     * @return mixed
     * @throws Exception
     */
    public function fetch()
    {
        $keys = [
            '`orders`.`id` as `id`',
            '`customers`.`name` as `name`',
            '`orders`.`customer_order` as `order`',
            '`orders`.`au` as `au`',
            '`orders`.`destination` as `destination`',
            '`orders`.`production` as `production`',
            '`orders`.`loading` as `loading`',
            '`orders`.`month` as `month`',

        ];
        $orders = Order::join('customers', 'customers.id', '=', 'orders.customer_id')->selectRaw(implode(', ', $keys))->get();

        return DataTables::of($orders)
            ->addColumn('view', function($orders) {
                return view('orders.partials.view', ['order' => $orders]);
            })
            ->make(true);
    }

    /**
     * Insert the order into the database
     *
     * @param Order $order
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function create(Order $order, Request $request)
    {
        $order->customer_id = $request->customer_id;
        $order->customer_order = $request->customer_order;
        $order->au = $request->au;
        $order->destination = $request->destination;
        $order->production = $request->production;
        $order->loading = $request->loading;
        $order->month = $request->month;
        $order->save();

        return redirect('/orders/' . $order->id);
    }

    /**
     * Show the data to the user
     *
     * @param Order $order
     * @param Request $request
     * @return Application|Factory|View
     */
    public function show(Order $order, Request $request)
    {
        if($request->ajax()) {
            $details = OrderDetail::where('order_id', $order->id)->get();
            $details->map(function ($item, $index) {
                $article = Article::find($item->article_id);
                $item->article_id = $article->article;
            });
            return DataTables::of($details)
                ->addIndexColumn()
                ->addColumn('actions', function() {
                    return 'Editeaza / Sterge';
                })
                ->make(true);
        }

        $customer = Customer::find($order->customer_id);
        $articles = Article::all();

        return view('orders.show', ['order' => $order, 'customer' => $customer, 'articles' => $articles]);
    }

    /**
     * Insert order details into the database
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addDetails(Request $request)
    {
        for ($i = 1; $i <= $request->pachete; $i++) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $request->order_id;
            $orderDetail->article_id = $request->article_id;
            $orderDetail->finisaje = $request->finisaje;
            $orderDetail->grosime = $request->grosime;
            $orderDetail->latime = $request->latime;
            $orderDetail->lungime = $request->lungime;
            $orderDetail->bucati = $request->bucati;
            $orderDetail->volum = ($request->grosime * $request->latime * $request->lungime * $request->bucati)/1000000000;
            $orderDetail->eticheta = $request->eticheta;
            $orderDetail->stick_panou = $request->stick_panou;
            $orderDetail->ean_pal = $request->ean_pal;
            $orderDetail->ean_picior = $request->ean_picior;
            $orderDetail->paletizare = $request->paletizare;
            $orderDetail->save();
        }

        return back();
    }
}
