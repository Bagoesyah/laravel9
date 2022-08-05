<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\HasTeams as JetstreamHasTeams;

class post1 extends Model
{
    use HasFactory;
    use JetstreamHasTeams;

}
