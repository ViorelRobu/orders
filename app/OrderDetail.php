<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class OrderDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * Auditable events.
     *
     * @var array
     */
    protected $auditEvents = [
        'created',
        'updated',
        'deleted'
    ];

    /**
     * One detail belongs to one article
     *
     * @return Eloquent
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * One detail belongs to one order
     *
     * @return Eloquent
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
