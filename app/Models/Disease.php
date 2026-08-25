<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $table = 'Diseases';
    protected $primaryKey = 'DiseaseID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ConsultationID',
        'NameDisease',
        'Date',
    ];

    protected $casts = [
        'Date' => 'date:Y-m-d',
    ];

    // A disease record belongs to one consultation
    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'ConsultationID');
    }
}