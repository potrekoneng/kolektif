<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListQueue extends Model
{
    protected $fillable = ['nomer', 'urutan', 'agency_id', 'id_card_id', 'student_card_id', 'user_id'];
}
