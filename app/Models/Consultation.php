<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $table = 'Consultations';
    protected $primaryKey = 'ConsultationID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'DoctorID',
        'ConsultationDate',
        'Notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID');
    }

    public function disease()
    {
        return $this->hasOne(Disease::class, 'ConsultationID');
    }
}