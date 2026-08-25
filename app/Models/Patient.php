<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'Patients';
    protected $primaryKey = 'PatientID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'FirstName',
        'LastName',
        'Gender',
        'BirthDate',
        'ContactNumber',
        'Address',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'PatientID');
    }

    // Convenience: get all diseases for this patient through consultations
    public function diseases()
    {
        return $this->hasManyThrough(
            Disease::class,
            Consultation::class,
            'PatientID',       // FK on consultations table
            'ConsultationID',  // FK on diseases table
            'PatientID',       // local key on patients table
            'ConsultationID'   // local key on consultations table
        );
    }
}