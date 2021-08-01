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

    public function movieGroupId($movieId)
    {
        return Movie::select('movie_group_id')
            ->where('id', '=', $movieId)
            ->first()
            ->movie_group_id ?? 0;
    }

    public function isUserInGroup($userId, $groupId)
    {
        return DB::table('movie_group_members')
            ->where([
                ['user_id', $userId],
                ['movie_group_id', $groupId]
            ])
            ->exists();
    }

    public function isMovieInUsersGroup($userId, $movieId)
    {
        $movieGroupId = $this->movieGroupId($movieId);
        if ($movieGroupId == 0) {
            return false;
        }
        return $this->isUserInGroup($userId, $movieGroupId);
    }
}
