<?php
// app/Models/AudioFile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioFile extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'duration',
        'file_path',
        'file_type'
    ];
}