<?php

namespace App\Http\Controllers;
//require_once 'swagger_annotations.php';
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
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
class Controller extends BaseController
{

/**
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server"
     * )
     */


    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
