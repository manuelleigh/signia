<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['agency_id', 'company_id', 'document_type', 'serie', 'number', 'xml_hash', 'status', 'ticket', 'cdr_path', 'xml_path'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
