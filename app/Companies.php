<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
	protected $fillable = [
        "ticker",
        "name",
        "logo",
        "description",
        "email",
        "sector",
        "phone",
        "address",
        "website",
        "watching",
    ];

    public function price()
    {
        return $this->hasMany('App\Prices', 'company_id', 'id');
    }
}
