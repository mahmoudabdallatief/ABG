


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
openapi: 3.0.0
paths:
  /api/register:
    post:
      tags:
        - Auth
      summary: 'Register a new user'
      description: 'Registers a new user and returns a success message'
      operationId: 8e4e3cb7b46126f4696379d3c7eeb4ad
      requestBody:
        required: true
        content:
          application/json:
            schema:
              required:
                - name
                - email
                - password
                - password_confirmation
                - picture
              properties:
                name:
                  type: string
                  example: 'John Doe'
                email:
                  type: string
                  format: email
                  example: johndoe@example.com
                password:
                  type: string
                  format: password
                  example: password123
                password_confirmation:
                  type: string
                  format: password
                  example: password123
                picture:
                  type: string
                  format: binary
              type: object
      responses:
        '201':
          description: 'User registered successfully'
        '422':
          description: 'Validation errors'
  /api/login:
    post:
      tags:
        - Auth
      summary: 'Login user'
      description: 'Logs in a user and returns an access token'
      operationId: 222b2625838e3c57e017987f73598fda
      requestBody:
        required: true
        content:
          application/json:
            schema:
              required:
                - email
                - password
              properties:
                email:
                  type: string
                  format: email
                  example: johndoe@example.com
                password:
                  type: string
                  format: password
                  example: password123
              type: object
      responses:
        '200':
          description: 'Login successful'
          content:
            application/json:
              schema:
                properties:
                  access_token: { type: string, example: 1|abcdef... }
                  token_type: { type: string, example: Bearer }
                type: object
        '401':
          description: Unauthorized
        '422':
          description: 'Validation errors'
  /api/logout:
    post:
      tags:
        - Auth
      summary: 'Logout user'
      description: 'Logs out the authenticated user'
      operationId: ad65cbbd4e9f201619eae184a5961a98
      responses:
        '200':
          description: 'Successfully logged out'
        '401':
          description: Unauthorized
  /api/profile:
    get:
      tags:
        - User
      summary: 'Get user profile'
      description: 'Returns the authenticated user''s profile, friends, and posts'
      operationId: 94a5f4c5f5e5755cf43698cf9bc49e9d
      responses:
        '200':
          description: 'User profile data'
          content:
            application/json:
              schema:
                properties:
                  user: { $ref: '#/components/schemas/User' }
                  friendsList: { type: array, items: { $ref: '#/components/schemas/User' } }
                  posts: { type: array, items: { $ref: '#/components/schemas/Post' } }
                type: object
        '401':
          description: Unauthorized
  '/api/profile_user/{id}':
    get:
      tags:
        - User
      summary: 'Get user profile by ID'
      description: 'Returns a specific user''s profile, friends, and posts'
      operationId: 2302c087d77cb737a27681b08c04e497
      parameters:
        -
          name: id
          in: path
          description: 'ID of the user'
          required: true
          schema:
            type: integer
      responses:
        '200':
          description: 'User profile data'
          content:
            application/json:
              schema:
                properties:
                  user: { $ref: '#/components/schemas/User' }
                  friendsList: { type: array, items: { $ref: '#/components/schemas/User' } }
                  posts: { type: array, items: { $ref: '#/components/schemas/Post' } }
                type: object
        '401':
          description: Unauthorized
  /api/edit_picture:
    post:
      tags:
        - User
      summary: 'Edit user picture'
      description: 'Updates the authenticated user''s profile picture'
      operationId: 9d6e9f0e84ff1de2c58e1690b9a5a042
      requestBody:
        required: true
        content:
          application/json:
            schema:
              properties:
                picture:
                  type: string
                  format: binary
              type: object
      responses:
        '200':
          description: 'Picture updated successfully'
        '401':
          description: Unauthorized
        '422':
          description: 'Validation errors'
  /api/sendRequest:
    post:
      tags:
        - Friends
      summary: 'Send friend request'
      description: 'Sends a friend request to another user'
      operationId: ae1ec093e8d7f3ca417bb10146cd4862
      requestBody:
        required: true
        content:
          application/json:
            schema:
              properties:
                user_id:
                  type: integer
                  example: 2
              type: object
      responses:
        '200':
          description: 'Friend request sent successfully'
        '401':
          description: Unauthorized
  /api/acceptRequest:
    post:
      tags:
        - Friends
      summary: 'Accept friend request'
      description: 'Accepts a pending friend request'
      operationId: c93a7f0549fa7f49d61e2d149e00858c
      requestBody:
        required: true
        content:
          application/json:
            schema:
              properties:
                user_id:
                  type: integer
                  example: 2
              type: object
      responses:
        '200':
          description: 'Friend request accepted'
        '401':
          description: Unauthorized
        '404':
          description: 'Friend request not found or already accepted'
  /api/rejectRequest:
    post:
      tags:
        - Friends
      summary: 'Reject friend request'
      description: 'Rejects a pending friend request'
      operationId: 633f21604e5355fca481519d3ad6bbba
      requestBody:
        required: true
        content:
          application/json:
            schema:
              properties:
                user_id:
                  type: integer
                  example: 2
              type: object
      responses:
        '200':
          description: 'Friend request rejected'
        '401':
          description: Unauthorized
  /api/searchFriends:
    get:
      tags:
        - Friends
      summary: 'Search friends'
      description: 'Searches the authenticated user''s friends by name'
      operationId: 5bd3f2a21f3424b3317a145aed71f029
      parameters:
        -
          name: user
          in: query
          description: 'User ID'
          required: true
          schema:
            type: integer
        -
          name: search_query
          in: query
          description: 'Search query'
          required: true
          schema:
            type: string
      responses:
        '200':
          description: 'Friends search results'
          content:
            application/json:
              schema:
                properties:
                  friends: { type: array, items: { $ref: '#/components/schemas/User' } }
                type: object
        '401':
          description: Unauthorized
  /api/home:
    get:
      tags:
        - Posts
      summary: 'Get list of posts'
      description: 'Returns a list of posts'
      operationId: d65ef76cbb32ae304c5a24c525b95adb
      responses:
        '200':
          description: 'successful operation'
          content:
            application/json:
              schema:
                type: array
                items:
                  $ref: '#/components/schemas/Post'
  /api/addpost:
    post:
      tags:
        - Posts
      summary: 'Add a new post'
      description: 'Creates a new post'
      operationId: 98b4c4c58604208fd6b8213e9188716a
      requestBody:
        required: true
        content:
          application/json:
            schema:
              required:
                - content
                - image
              properties:
                content:
                  type: string
                  example: 'This is a new post'
                image:
                  type: string
                  format: binary
              type: object
      responses:
        '201':
          description: 'Post created successfully'
        '422':
          description: 'Validation error'
  '/api/updatepost/{id}':
    put:
      tags:
        - Posts
      summary: 'Update a post'
      description: 'Updates an existing post'
      operationId: 961161887008588f85a02d868c15d015
      parameters:
        -
          name: id
          in: path
          description: 'ID of the post to update'
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              required:
                - content
              properties:
                content:
                  type: string
                  example: 'Updated post content'
                image:
                  type: string
                  format: binary
              type: object
      responses:
        '200':
          description: 'Post updated successfully'
        '422':
          description: 'Validation error'
  '/api/deletepost/{id}':
    delete:
      tags:
        - Posts
      summary: 'Delete a post'
      description: 'Deletes a post'
      operationId: 435c2884b5abec5484083e3c73586029
      parameters:
        -
          name: id
          in: path
          description: 'ID of the post to delete'
          required: true
          schema:
            type: integer
      responses:
        '200':
          description: 'Post deleted successfully'
  '/api/singlepost/{id}':
    get:
      tags:
        - Posts
      summary: 'Get a single post'
      description: 'Returns a single post'
      operationId: 1ee74a8bfddccc95192f7e37077403fa
      parameters:
        -
          name: id
          in: path
          description: 'ID of the post to retrieve'
          required: true
          schema:
            type: integer
      responses:
        '200':
          description: 'successful operation'
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/Post'
  /api/addlike:
    post:
      tags:
        - Likes
      summary: 'Add a like'
      description: 'Adds a like to a post'
      operationId: 3e2edf056932faab02dc7d4014099f03
      requestBody:
        required: true
        content:
          application/json:
            schema:
              required:
                - post_id
              properties:
                post_id:
                  type: integer
                  example: 1
              type: object
      responses:
        '200':
          description: 'Like added successfully'
  /api/addcomment:
    post:
      tags:
        - Comments
      summary: 'Add a comment'
      description: 'Adds a comment to a post'
      operationId: d683acf71859bc5e9a626477329eb68f
      requestBody:
        required: true
        content:
          application/json:
            schema:
              required:
                - content
                - post_id
              properties:
                content:
                  type: string
                  example: 'This is a comment'
                post_id:
                  type: integer
                  example: 1
              type: object
      responses:
        '201':
          description: 'Comment added successfully'
        '422':
          description: 'Validation error'
  '/api/updatecomment/{id}':
    put:
      tags:
        - Comments
      summary: 'Update a comment'
      description: 'Updates an existing comment'
      operationId: f0c6357ce6bee7b7eb2da8657630d8e5
      parameters:
        -
          name: id
          in: path
          description: 'ID of the comment to update'
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              required:
                - content
              properties:
                content:
                  type: string
                  example: 'Updated comment content'
              type: object
      responses:
        '200':
          description: 'Comment updated successfully'
        '422':
          description: 'Validation error'
  '/api/deletecomment/{id}':
    delete:
      tags:
        - Comments
      summary: 'Delete a comment'
      description: 'Deletes a comment'
      operationId: 4f2055a4f7a79441c6c7e70760f2f6a3
      parameters:
        -
          name: id
          in: path
          description: 'ID of the comment to delete'
          required: true
          schema:
            type: integer
      responses:
        '200':
          description: 'Comment deleted successfully'
components:
  schemas:
    Friend:
      title: Friend
      description: 'Friend object'
      properties:
        id:
          type: integer
        user_id:
          type: integer
        friend_id:
          type: integer
        status:
          type: string
        created_at:
          type: string
          format: date-time
      type: object
