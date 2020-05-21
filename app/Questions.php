<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    //

    protected $appends = ['answered_correctly'];

    protected $fillable = [
        'episode',
        'air_date',
        'round_type',
        'category',
        'value',
        'question',
        'answer',
    ];

    public function getAnsweredCorrectlyAttribute()
    {
        return 0;
    }

    public function sameCategory() {
        
    }
}
