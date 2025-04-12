<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
