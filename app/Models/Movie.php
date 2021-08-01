<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'seen',
        'rating',
        'movie_group_id',
        'added_by',
    ];

    public function isUserInGroup($userId, $groupId)
    {
        return DB::table('movie_group_members')
            ->where([
                ['user_id', $userId],
                ['movie_group_id', $groupId]
            ])
            ->exists();
    }
}
