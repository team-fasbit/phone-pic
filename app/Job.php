<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
     public function images(){
    	return $this->hasMany('App\JobImage','job_id')->select(["job_id","caption","image_name"]);
    }

    public function ownerinfo(){
    	return $this->belongsTo('App\User','user_id')->select(["id","email"]);
    }
}
