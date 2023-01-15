<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    protected $fillable = [
        "open",
        "high",
        "low",
        "close",
        "volume",
        "company_id",
    ];

    public function price()
    {
        return $this->belongsTo('App\Company');
    }
}
