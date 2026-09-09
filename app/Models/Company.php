<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['agency_id', 'ruc', 'business_name', 'environment', 'engine_type'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
