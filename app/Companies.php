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
    ];

    public function price()
    {
        return $this->hasOne('App\Prices', 'company_id', 'id');
    }
}
