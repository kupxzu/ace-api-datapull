<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
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
        'CreatedAt',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'PatientID');
    }

    public function diseases()
    {
        return $this->hasManyThrough(
            Disease::class,
            Consultation::class,
            'PatientID',
            'ConsultationID',
            'PatientID',
            'ConsultationID'
        );
    }
}