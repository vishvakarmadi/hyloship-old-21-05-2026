<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
   //
    
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
