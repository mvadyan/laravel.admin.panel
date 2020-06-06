<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'value',
        'attr_group_id',
    ];

    public function filter()
    {
        return $this->belongsTo('App\Models\Admin\AttributeGroup', 'attr_group_id' ,'id');
    }
}
