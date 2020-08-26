<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = [];

    public function quality()
    {
        return $this->belongsTo(Quality::class);
    }
}
