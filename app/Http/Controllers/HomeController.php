<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend; 
use App\Models\Post; 
use App\Models\Comment; 
use App\Models\Like; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\CommentNotification;
use App\Notifications\LikeNotification;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    

     public function index()
     {
         // Get the current user's ID
         $userId = Auth::id();
     
         // Get all friend relationships for the current user
         $friends = Friend::where(function($query) use ($userId) {
             $query->where('user_id', $userId)
                   ->orWhere('friend_id', $userId);
         })->get();
     
         // Collect unique friend IDs
         $friendIds = $friends->pluck('user_id')
                              ->merge($friends->pluck('friend_id'))
                              ->unique()
                              ->all();
     
         // Include the current user's ID in the list of IDs
         $friendIds[] = $userId;
     
         // Retrieve posts where the user ID matches the author's ID or one of their friends' IDs and status is accepted
         $posts = Post::whereIn('user_id', function($query) use ($userId) {
                 $query->select('user_id')
                       ->from('friends')
                       ->where(function($subQuery) use ($userId) {
                           $subQuery->where('user_id', $userId)
                                    ->orWhere('friend_id', $userId);
                       })
                       ->where('status', 'accepted');
             })
             ->orWhereIn('user_id', function($query) use ($userId) {
                 $query->select('friend_id')
                       ->from('friends')
                       ->where(function($subQuery) use ($userId) {
                           $subQuery->where('user_id', $userId)
                                    ->orWhere('friend_id', $userId);
                       })
                       ->where('status', 'accepted');
             })
             ->orWhere('user_id', $userId)
             ->orderBy('created_at', 'desc')
             ->get();
     
         // Get recommended friends (excluding the current user's friends and the user themselves)
         $recommendeds = User::whereNotIn('id', $friendIds)
                             ->where('id', '<>', $userId)
                             ->get();
     
         // Get pending friend requests received by the current user
         $receivedFriendRequests = Friend::where('friend_id', $userId)
                                         ->where('status', 'pending')
                                         ->pluck('user_id');
         
         $friendRequests = User::whereIn('id', $receivedFriendRequests)->get();
     
         return view('home', compact('posts', 'recommendeds', 'friendRequests'));
     }
     

    
    public function addpost(Request $request){
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $image = $request->file('image');
        $new_img = md5(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_img);
    
        
    
        
        Post::create([
            'user_id'=> Auth::user()->id,
            'content'=> $request->content,
            'image'  => $new_img
        ]);
        
        return back();
    }
    public function editpost(Request $request){
        $id= $request->id;
return back()->with('id',$id);
    }

    public function updatepost(Request $request ,$id){
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->with('id',$id)->withErrors($validator)->withInput();
        }
        
        if($request->file('image')!=NULL){

            $post = Post::findOrFail($id);
    
            // Check if the user already has a picture and delete the old one
            $imagePath = public_path('images') . '/' . $post->image;
            if (file_exists($imagePath) && $post->image) {
                unlink($imagePath);
            }
            $image = $request->file('image');
            $new_img = md5(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_img);
            Post::where('id',$id)->update([
            
                'image'  => $new_img
            ]);
        }

       Post::where('id',$id)->update([
            
                'content'  => $request->content
            ]);
    
        
    
        
        
            return back();
    }
    public function deletepost($id){
        $post = Post::findOrFail($id);
    
            // Check if the user already has a picture and delete the old one
            $imagePath = public_path('images') . '/' . $post->image;
            if (file_exists($imagePath) && $post->image) {
                unlink($imagePath);
            }
            $post->delete();
            return back();
    }
    public function singlepost($id){

        $post = Post::findOrFail($id);

        $likes = Like::where('post_id',$id)->orderBy('id','desc')->get();
        $comments = Comment::where('post_id',$id)->orderBy('id','desc')->get();

        return view('single',compact('post','likes','comments'));

    }
    public function addlike(Request $request)
{
    // Check if the like already exists
    $check = Like::where('user_id', Auth::user()->id)
                 ->where('post_id', $request->post_id)
                 ->first();

    if (!$check) {
        // Create a new like
        Like::create([
            'user_id' => Auth::user()->id,
            'post_id' => $request->post_id
        ]);

        // Find the post and the user who made the post
        $post = Post::findOrFail($request->post_id);
        $user = User::findOrFail($post->user_id);

        // Send the notification
        Notification::send($user, new LikeNotification($post->id));
    }

    return back();
}

    public function addcomment(Request $request){
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
            
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       
        Comment::create([
            'user_id'=> Auth::user()->id,
            'content'=> $request->content,
            'post_id' =>$request->post_id,
        ]);

        $post = Post::findOrFail($request->post_id);
        $user = User::findOrFail($post->user_id);

        // Send the notification
        Notification::send($user, new CommentNotification($post->id));
        
        return back();
    }
    public function editcomment(Request $request){
        $id= $request->id;
return back()->with('id',$id);
    }

    public function updatecomment(Request $request ,$id){
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
           
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->with('id',$id)->withErrors($validator)->withInput();
        }
        
        

       Comment::where('id',$id)->update([
            
                'content'  => $request->content
            ]);
    
        
    
        
        
            return back();
    }
    public function deletecomment($id){
        $comment = Comment::findOrFail($id);
    
            
            $comment->delete();
            return back();
    }
}
