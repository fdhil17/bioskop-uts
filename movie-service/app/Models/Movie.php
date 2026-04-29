<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'genre', 'duration', 'seat_available', 'price'];
}
