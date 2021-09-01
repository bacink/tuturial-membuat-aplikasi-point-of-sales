<?php

namespace App\Models;
class Member extends BaseModel
{

    protected $table = 'member';
    protected $primaryKey = 'id_member';
    protected $guarded = ['created_at','updated_at'];
}
