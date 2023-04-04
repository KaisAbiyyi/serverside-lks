<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Medical::class);
    }
    public function officer()
    {
        return $this->belongsTo(Medical::class);
    }
    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }
}
