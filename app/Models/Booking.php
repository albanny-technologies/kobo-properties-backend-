<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
