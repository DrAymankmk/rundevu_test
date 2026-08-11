<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AppType extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'name_en', 'name_ar', 'status','type', 'created_at'];

    public function shifts() {
        return $this->hasMany(Shift::class,'account_type')->where('clinic_id',Auth::user()->id)->orderBy('id','desc');
    }

    function accounts()
    {
        return $this->hasMany(Clinic::class,'app_type');
    }

    // App\Models\AppType.php

    public function doctors()
    {
        return $this->hasMany(Clinic::class, 'app_type')->where('parent_id', Auth::user()->id)->orderBy('id','desc');
    }


}
