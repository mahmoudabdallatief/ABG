<?php

namespace App\Http\Controllers\Api;
require_once 'swagger_annotations.php';
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Models\Friend;
use App\Models\User;
use OpenApi\Annotations as OA;

use App\Notifications\FriendRequestNotification;


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
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Registers a new user and returns a success message",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation", "picture"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(property="picture", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $new_img = md5(uniqid()) . '.' . $request->picture->getClientOriginalExtension();
        $request->picture->move(public_path('images'), $new_img);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'picture' => $new_img
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login user",
     *     description="Logs in a user and returns an access token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="1|abcdef..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout user",
     *     description="Logs out the authenticated user",
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"User"},
     *     summary="Get user profile",
     *     description="Returns the authenticated user's profile, friends, and posts",
     *     @OA\Response(
     *         response=200,
     *         description="User profile data",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="friendsList", type="array", @OA\Items(ref="#/components/schemas/User")),
     *             @OA\Property(property="posts", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function profile()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = Auth::user()->id;
        $posts = Post::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $user = User::findOrFail($userId);

        $friends = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)->orWhere('friend_id', $userId);
        })->get();

        $acceptedFriendships = $friends->where('status', 'accepted');
        $acceptedIds = $acceptedFriendships->pluck('user_id')->merge($acceptedFriendships->pluck('friend_id'))->unique()->all();
        $acceptedIds = array_diff($acceptedIds, [$userId]);
        $friendsList = User::whereIn('id', $acceptedIds)->get();

        return response()->json([
            'user' => $user,
            'friendsList' => $friendsList,
            'posts' => $posts
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/profile_user/{id}",
     *     tags={"User"},
     *     summary="Get user profile by ID",
     *     description="Returns a specific user's profile, friends, and posts",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User profile data",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="friendsList", type="array", @OA\Items(ref="#/components/schemas/User")),
     *             @OA\Property(property="posts", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function profile_user($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $id;
        $posts = Post::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $user = User::findOrFail($userId);

        $friends = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)->orWhere('friend_id', $userId);
        })->get();

        $acceptedFriendships = $friends->where('status', 'accepted');
        $acceptedIds = $acceptedFriendships->pluck('user_id')->merge($acceptedFriendships->pluck('friend_id'))->unique()->all();
        $acceptedIds = array_diff($acceptedIds, [$userId]);
        $friendsList = User::whereIn('id', $acceptedIds)->get();

        return response()->json([
            'user' => $user,
            'friendsList' => $friendsList,
            'posts' => $posts
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/edit_picture",
     *     tags={"User"},
     *     summary="Edit user picture",
     *     description="Updates the authenticated user's profile picture",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="picture", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Picture updated successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function edit_picture(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::findOrFail(Auth::user()->id);
        $imagePath = public_path('images') . '/' . $user->picture;
        if (file_exists($imagePath) && $user->picture) {
            unlink($imagePath);
        }

        $image = $request->file('picture');
        $new_img = md5(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_img);

        $user->update(['picture' => $new_img]);

        return response()->json(['message' => 'Picture updated successfully'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/sendRequest",
     *     tags={"Friends"},
     *     summary="Send friend request",
     *     description="Sends a friend request to another user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request sent successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function sendRequest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = Auth::user()->id;
        $id = $request->input('user_id');

        $existingFriendship = Friend::where(function ($query) use ($userId, $id) {
            $query->where('user_id', $userId)->where('friend_id', $id);
        })->orWhere(function ($query) use ($userId, $id) {
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

        return response()->json(['message' => 'Friend request sent successfully'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/acceptRequest",
     *     tags={"Friends"},
     *     summary="Accept friend request",
     *     description="Accepts a pending friend request",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request accepted",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Friend request not found or already accepted"
     *     )
     * )
     */
    public function acceptRequest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = Auth::user()->id;
        $id = $request->input('user_id');

        $updated = Friend::where('user_id', $id)
                         ->where('friend_id', $userId)
                         ->where('status', 'pending')
                         ->update(['status' => 'accepted']);

        if ($updated) {
            return response()->json(['message' => 'Friend request accepted'], 200);
        } else {
            return response()->json(['error' => 'Friend request not found or already accepted'], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/rejectRequest",
     *     tags={"Friends"},
     *     summary="Reject friend request",
     *     description="Rejects a pending friend request",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request rejected",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function rejectRequest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = Auth::user()->id;
        $id = $request->input('user_id');

        Friend::where(function ($query) use ($userId, $id) {
            $query->where('user_id', $id)->where('friend_id', $userId);
        })->delete();

        return response()->json(['message' => 'Friend request rejected'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/searchFriends",
     *     tags={"Friends"},
     *     summary="Search friends",
     *     description="Searches the authenticated user's friends by name",
     *     @OA\Parameter(
     *         name="user",
     *         in="query",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search_query",
     *         in="query",
     *         description="Search query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friends search results",
     *         @OA\JsonContent(
     *             @OA\Property(property="friends", type="array", @OA\Items(ref="#/components/schemas/User"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function searchFriends(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $request->input('user');
        $searchTerm = $request->input('search_query');

        $friends = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)->orWhere('friend_id', $userId);
        })->where('status', 'accepted')->get();

        $friendIds = $friends->pluck('user_id')->merge($friends->pluck('friend_id'))->unique()->all();
        $friendIds = array_diff($friendIds, [$userId]);

        $filteredFriends = User::whereIn('id', $friendIds)
                               ->where('name', 'LIKE', "%{$searchTerm}%")
                               ->get();

        return response()->json(['friends' => $filteredFriends], 200);
    }
}
