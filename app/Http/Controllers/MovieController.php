<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupMoviesRequest;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserInfo(Request $request)
    {
        $userInfo = Movie::select([
            'mg.id as group_id',
            'mg.name as group_name',
        ])
            ->from('movie_groups AS mg')
            ->leftJoin('movie_group_members AS mgm', 'mgm.movie_group_id', '=', 'mg.id')
            ->where('mgm.user_id', '=', $request->user()->id)
            ->first();

        if (!$userInfo) {
            return response(['message' => 'No User found with that ID'], 500);
        }

        $userInfo->toArray();
        $userInfo['user_name'] = $request->user()->name;

        return response($userInfo);
    }

    /**
     * get the movies belonging to the movie group
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $groupId
     * @return \Illuminate\Http\Response
     */
    public function getGroupMovies(GroupMoviesRequest $request, int $groupId)
    {
        $movie = new Movie;
        if (!$movie->isUserInGroup($request->user()->id, $groupId)) {
            return response(['message' => 'User is not in group'], Response::HTTP_UNAUTHORIZED);
        };

        $input = $request->validated();

        $groupMovies = $movie
            ->select('*')
            ->isInGroup($groupId)
            ->orderBy(isset($input['sortBy']) ? $input['sortBy'] : 'id');

        if (isset($input['isSeen'])) {
            $groupMovies->isSeen((bool) $input['isSeen']);
        };
        
        return $groupMovies->simplePaginate(isset($input['perPage']) ? $input['perPage'] : 10);
    }

    /**
     * Add a new movie to the given user's group
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $groupId)
    {
        $movie = new Movie;
        if (!$movie->isUserInGroup($request->user()->id, $groupId)) {
            return response(['message' => 'User is not in group'], Response::HTTP_UNAUTHORIZED);
        };

        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $id = $movie->create([
            'title' => ucfirst($request->title),
            'movie_group_id' => $groupId,
            'seen' => false,
            'added_by' => $request->user()->id,
        ])->id;

        return response(['status' => 'Success', 'message' => 'Movie created successfully', 'id' => $id], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markMovieAsSeen(Request $request)
    {
        $input = $request->validate([
            'movieId' => 'required|exists:movies,id'
        ]);
        $movie = new Movie;
        if (!$movie->isMovieInUsersGroup($request->user()->id, $input['movieId'])) {
            return response(['message' => 'Movie is not in the user\'s group'], Response::HTTP_UNAUTHORIZED);
        }

        Movie::find($input['movieId'])
            ->update(['seen' => true]);

        return response(['status' => 'success', 'message' => 'Movie updated successfully'], Response::HTTP_CREATED);
    }
}
