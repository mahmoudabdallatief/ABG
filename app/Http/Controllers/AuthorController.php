<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use App\Models\Friend; 
use App\Notifications\FriendRequestNotification;
class AuthorController extends Controller
{
    public function profile(){
       
            $userId =Auth::user()->id;
        
            $posts = Post::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $user = User::findOrFail($userId);
        $friends = Friend::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })->get();
        // Get accepted friends
        $acceptedFriendships = $friends->where('status', 'accepted');
        $acceptedIds = $acceptedFriendships->pluck('user_id')
            ->merge($acceptedFriendships->pluck('friend_id'))
            ->unique()
            ->all();
        $acceptedIds = array_diff($acceptedIds, [$userId]);

        // Get users who are friends
        $friendsList = User::whereIn('id', $acceptedIds)->get();
        return view('profile',compact('user','friendsList','posts'));
    }

public function profile_user($id){
    $userId =$id;
        
    $posts = Post::where('user_id', $userId)
    ->orderBy('created_at', 'desc')
    ->get();
    
        $user = User::findOrFail($userId);
        $friends = Friend::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })->get();
        // Get accepted friends
        $acceptedFriendships = $friends->where('status', 'accepted');
        $acceptedIds = $acceptedFriendships->pluck('user_id')
            ->merge($acceptedFriendships->pluck('friend_id'))
            ->unique()
            ->all();
        $acceptedIds = array_diff($acceptedIds, [$userId]);

        // Get users who are friends
        $friendsList = User::whereIn('id', $acceptedIds)->get();
        return view('profile',compact('user','friendsList','posts'));
    }


    public function edit_picture(Request $request) {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('profile')->withErrors($validator)->withInput();
        }
    
        $user = User::findOrFail(Auth::user()->id);
    
        // Check if the user already has a picture and delete the old one
        $imagePath = public_path('images') . '/' . $user->picture;
        if (file_exists($imagePath) && $user->picture) {
            unlink($imagePath);
        }
    
        // Process the new image
        $image = $request->file('picture');
        $new_img = md5(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_img);
    
        // Update the user's picture in the database
        $user->update([
            'picture' => $new_img,
        ]);
    
        return back();
    }
   
    public function sendRequest(Request $request)
    {
        $userId = Auth::user()->id;
        $id =$request->user_id;
        // Check if a friendship already exists
        $existingFriendship = Friend::where(function($query) use ($userId, $id) {
            $query->where('user_id', $userId)->where('friend_id', $id);
        })->orWhere(function($query) use ($userId, $id) {
            $query->where('user_id', $id)->where('friend_id', $userId);
        })->first();

        if (!$existingFriendship) {
            Friend::create([
                'user_id' => $userId,
                'friend_id' => $id,
                'status' => 'pending'
            ]);
        }
        $user = User::findOrFail($id);
            
        Notification::send($user, new FriendRequestNotification($userId));
        return back();
    }

    public function acceptRequest(Request $request)
{
    $userId = Auth::user()->id;
    $id = $request->input('user_id'); // Make sure the 'user_id' is coming from the request

    // Update the friend request status to accepted
    $updated = Friend::where('user_id', $id)
                     ->where('friend_id', $userId)
                     ->where('status', 'pending')
                     ->update(['status' => 'accepted']);

    if ($updated) {
        return redirect()->route('profile')->with('status', 'Friend request accepted.');
    } else {
        return redirect()->route('profile')->with('error', 'Friend request not found or already accepted.');
    }
}


    public function rejectRequest(Request $request)
    {
        $userId = Auth::user()->id;
        $id =$request->user_id;
        // Delete the friend request
        Friend::where(function($query) use ($userId, $id) {
            $query->where('user_id', $id)->where('friend_id', $userId);
        })->delete();

        return back();
    }

    public function searchFriends(Request $request)
{
    $userId = $request->input('user');
    $searchTerm = $request->input('search_query');

    // Get all friends for the current user
    $friends = Friend::where(function($query) use ($userId) {
        $query->where('user_id', $userId)
              ->orWhere('friend_id', $userId);
    })->where('status', 'accepted')->get();

    // Collect friend IDs
    $friendIds = $friends->pluck('user_id')->merge($friends->pluck('friend_id'))->unique()->all();
    // Exclude the current user's ID
    $friendIds = array_diff($friendIds, [$userId]);

    // Search friends among the collected IDs
    $filteredFriends = User::whereIn('id', $friendIds)
                           ->where('name', 'LIKE', "%{$searchTerm}%")
                           ->get();

    return response()->json($filteredFriends);
}

}
