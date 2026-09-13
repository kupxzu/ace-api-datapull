<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors'; // Aligned with database schema
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
        'CreatedAt',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'DoctorID');
    }
}