<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'Doctors';
    protected $primaryKey = 'DoctorID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'FirstName',
        'LastName',
        'Specialization',
        'ContactNumber',
        'Email',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'DoctorID');
    }
}