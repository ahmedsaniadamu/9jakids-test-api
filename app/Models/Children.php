<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id', 'name', 'age_range','gender', 'code'	
    ];
    
    protected $hidden = [ 'updated_at' , 'created_at' ];

    public function user(){
        return $this -> belongsTo(User::class,'parent_id');
    }
}
