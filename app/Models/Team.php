<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['team', 'desk_id'];
    protected $primaryKey = 'team_id';

}
