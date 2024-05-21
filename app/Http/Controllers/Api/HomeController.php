<?php

namespace App\Http\Controllers\Api;
require_once 'swagger_annotations.php';
use App\Http\Controllers\Controller;
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
use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="0.1",
 *         title="Social Media Project",
 *         description="Social Media Project",
 *         termsOfService="http://example.com/terms/",
 *         @OA\Contact(
 *             email="hodamedocrv@gmail.com"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     )
 * )
 */




/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="picture", type="string"),
 *     ...
 * )
 */

/**
 * @OA\Schema(
 *     schema="Post",
 *     title="Post",
 *     description="Post object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="image", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     ...
 * )
 */

/**
 * @OA\Schema(
 *     schema="Like",
 *     title="Like",
 *     description="Like object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="post_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     ...
 * )
 */

/**
 * @OA\Schema(
 *     schema="Comment",
 *     title="Comment",
 *     description="Comment object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="post_id", type="integer"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     ...
 * )
 */

/**
 * @OA\Schema(
 *     schema="Friend",
 *     title="Friend",
 *     description="Friend object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="friend_id", type="integer"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */



class HomeController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/api/home",
     *     tags={"Posts"},
     *     summary="Get list of posts",
     *     description="Returns a list of posts",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        $userId = Auth::id();
        $friends = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
        })->get();

        $friendIds = $friends->pluck('user_id')
                             ->merge($friends->pluck('friend_id'))
                             ->unique()
                             ->all();

        $friendIds[] = $userId;

        $posts = Post::whereIn('user_id', function ($query) use ($userId) {
            $query->select('user_id')
                  ->from('friends')
                  ->where(function ($subQuery) use ($userId) {
                      $subQuery->where('user_id', $userId)
                               ->orWhere('friend_id', $userId);
                  })
                  ->where('status', 'accepted');
        })
        ->orWhereIn('user_id', function ($query) use ($userId) {
            $query->select('friend_id')
                  ->from('friends')
                  ->where(function ($subQuery) use ($userId) {
                      $subQuery->where('user_id', $userId)
                               ->orWhere('friend_id', $userId);
                  })
                  ->where('status', 'accepted');
        })
        ->orWhere('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

        $recommendeds = User::whereNotIn('id', $friendIds)
                             ->where('id', '<>', $userId)
                             ->get();

        $receivedFriendRequests = Friend::where('friend_id', $userId)
                                         ->where('status', 'pending')
                                         ->pluck('user_id');

        $friendRequests = User::whereIn('id', $receivedFriendRequests)->get();

        return response()->json([
            'posts' => $posts,
            'recommendeds' => $recommendeds,
            'friendRequests' => $friendRequests
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/addpost",
     *     tags={"Posts"},
     *     summary="Add a new post",
     *     description="Creates a new post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content", "image"},
     *             @OA\Property(property="content", type="string", example="This is a new post"),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     */
    public function addpost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $image = $request->file('image');
        $new_img = md5(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_img);

        Post::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content,
            'image'  => $new_img
        ]);

        return response()->json(['message' => 'Post created successfully'], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/updatepost/{id}",
     *     tags={"Posts"},
     *     summary="Update a post",
     *     description="Updates an existing post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Updated post content"),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     */
    public function updatepost(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
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

        return response()->json(['message' => 'Post updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/deletepost/{id}",
     *     tags={"Posts"},
     *     summary="Delete a post",
     *     description="Deletes a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *     )
     * )
     */
    public function deletepost($id)
    {
        $post = Post::findOrFail($id);

        $imagePath = public_path('images') . '/' . $post->image;
        if (file_exists($imagePath) && $post->image) {
            unlink($imagePath);
        }
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/singlepost/{id}",
     *     tags={"Posts"},
     *     summary="Get a single post",
     *     description="Returns a single post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     )
     * )
     */
    public function singlepost($id)
    {
        $post = Post::findOrFail($id);
        $likes = Like::where('post_id', $id)->orderBy('id', 'desc')->get();
        $comments = Comment::where('post_id', $id)->orderBy('id', 'desc')->get();

        return response()->json(['post' => $post, 'likes' => $likes, 'comments' => $comments], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/addlike",
     *     tags={"Likes"},
     *     summary="Add a like",
     *     description="Adds a like to a post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"post_id"},
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Like added successfully",
     *     )
     * )
     */
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
    
        return response()->json(['message' => 'Like added successfully'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/addcomment",
     *     tags={"Comments"},
     *     summary="Add a comment",
     *     description="Adds a comment to a post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content", "post_id"},
     *             @OA\Property(property="content", type="string", example="This is a comment"),
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment added successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     */
    public function addcomment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        Comment::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content,
            'post_id' => $request->post_id,
        ]);

        $post = Post::findOrFail($request->post_id);
        $user = User::findOrFail($post->user_id);

        Notification::send($user, new CommentNotification($post->id));

        return response()->json(['message' => 'Comment added successfully'], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/updatecomment/{id}",
     *     tags={"Comments"},
     *     summary="Update a comment",
     *     description="Updates an existing comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Updated comment content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     */
    public function updatecomment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        Comment::where('id', $id)->update([
            'content' => $request->content
        ]);

        return response()->json(['message' => 'Comment updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/deletecomment/{id}",
     *     tags={"Comments"},
     *     summary="Delete a comment",
     *     description="Deletes a comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *     )
     * )
     */
    public function deletecomment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
