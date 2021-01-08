<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * One order details belongs to one article
     *
     * @return Eloquent
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * One order belongs to a destination
     *
     * @return Eloquent
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * One order has many details
     *
     * @return Eloquent
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
