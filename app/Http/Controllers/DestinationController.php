<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Fetch all the destinations of a certain supplier
     *
     * @param Customer $customer
     * @param Destination $destination
     * @return JsonResponse
     */
    public function fetch(Customer $customer, Destination $destination)
    {
        $data = $destination->where('customer_id', $customer->id)->get();

        $data->map(function ($item, $index) {
            $country = Country::find($item->country_id);
            $item->country_id = $country->name;
        });

        return response()->json([
            'result' => 'success',
            'data' => $data
        ]);
    }

    /**
     * If a destination doesn't exist create a new one and return its id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function findOrNew(Request $request)
    {
        $destination = Destination::firstOrCreate([
            'customer_id' => $request->customer_id,
            'address' => $request->address,
            'country_id' => $request->country_id
        ]);

        return response()->json([
            'result' => 'success',
            'data' => $destination->id
        ], 201);
    }

    /**
     * Search throught the list of destinations for the customer
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $destinations = Destination::where('customer_id', $request->customer_id)->where('country_id', $request->country_id)->pluck('address');

        return response()->json([
            'result' => 'success',
            'count' => $destinations->count(),
            'data' => $destinations
        ]);
    }
}
