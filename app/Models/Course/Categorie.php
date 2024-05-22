<?php

namespace App\Models\Course;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "name",
        "imagen",
        "categorie_id",
        "state",
    ];
    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["created_at"] = Carbon::now();
    }
    public function setUpdatedAtAttrribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"] = Carbon::now();
    }
    public function children()
    {
        return $this->hasMany(Categorie::class, "categorie_id");
    }
    public function father()
    {
        return $this->belongsTo(Categorie::class, "categorie_id");
    }
    function scopeFilterAdvance($query, $search, $state)
    {
        if ($search) {
            $query->where("name", "like", "%" . $search . "%");
        }
        if ($state) {
            $query->where("state", $state);
        }
        return $query;
    }
}
