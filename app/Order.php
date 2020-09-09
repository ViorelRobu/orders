<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
