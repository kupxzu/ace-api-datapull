<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $table = 'diseases';
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

    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'ConsultationID');
    }
}