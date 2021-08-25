<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with([
            'user' => fn($query) => $query -> withCount([
                'followers as is_followed' => fn($query) 
                => $query -> where('follower_id', auth()->user()->id)])
            ->withCasts(['is_followed' => 'boolean'])
        ])
        ->orderBy('created_at', 'DESC')
        ->get();

        return Inertia::render('Tweets/Index', [
            'tweets' => $tweets
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => ['required', 'max:280'],
            'user_id' => ['exists:users,id']
        ]);

        Tweet::create([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id
        ]);

        return Redirect::Route('tweets.index');
    }

    public function followings()
    {
        $followings = Tweet::with('user')
        ->whereIn('user_id', auth()->user()->followings->pluck('id')->toArray())
        ->orderBy('created_at', 'DESC')
        ->get();

        return Inertia::render('Tweets/Followings', [
            'followings' => $followings
        ]);
    }

    public function profile(User $user)
    {
        $profileUser = $user->loadCount([
            'followings as is_following_you' => 
            fn($q) => $q->where('following_id', auth()->user()->id)
            ->withCasts(['is_following_you' => 'boolean']),
            'followers as is_followed' =>
            fn($q) => $q->where('follower_id', auth()->user()->id)
            ->withCasts(['is_followed' => 'boolean'])
        ]);

        $tweets = $user->tweets;

        return Inertia::render('Tweets/Profile', [
            'profileUser' => $profileUser,
            'tweets' => $tweets
        ]);
    }

    public function follows(User $user)
    {
        Auth::user()->followings()->attach($user->id);
        return Redirect::Route('tweets.index');
    }

    public function unfollows(User $user)
    {
        Auth::user()->followings()->detach($user->id);
        return redirect()->back();
    }
}
