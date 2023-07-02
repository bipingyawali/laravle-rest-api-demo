<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PostRequest;
use App\Http\Resources\Api\Post\PostCollection;
use App\Http\Resources\Api\Post\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends ApiController
{
    public Post $postModel;
    public function __construct(Post $post)
    {
        $this->postModel = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse|PostCollection
     */

    /**
     * @OA\Get (
     *      path="/api/posts",
     *      tags={"Posts"},
     *      summary="Retrieve the collection of posts resources",
     *      operationId="Retrieve the collection of posts resources",
     *      @OA\Parameter(
     *          name="q",
     *          description="query",
     *          in="query",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="limit",
     *          in="query",
     *          @OA\Schema(type="number")
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="page",
     *          in="query",
     *          @OA\Schema(type="number")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *)
     **/
    public function index(Request $request): JsonResponse|PostCollection
    {
        try {
            $limit = $request->query('limit', 10);
            $posts = $this->postModel->select('id','title','publish','created_at');
            if($request->query('q')) {
                $q = $request->query('q');
                $posts = $posts->where('title','like', "%$q%");
            }
            $posts = $posts->paginate($limit);
            return new PostCollection($posts);
        } catch (Exception $exception) {
            return $this->responseError($exception, 'Something went wrong.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return JsonResponse
     */

    /**
     * @OA\Post (
     *      path="/api/posts",
     *      tags={"Posts"},
     *      summary="Create a post resource",
     *      operationId="Create a post resource",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="integer", example=""),
     *              @OA\Property(property="content", type="string", example=""),
     *              @OA\Property(property="publish", type="boolean", example=""),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *)
     **/
    public function store(PostRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->postModel->create($request->validated());
            DB::commit();
            return $this->responseSuccess('Post created successfully.');
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->responseError($exception, 'Something went wrong.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return PostResource
     */

    /**
     * @OA\Get (
     *      path="/api/posts/{id}",
     *      tags={"Posts"},
     *      summary="Retrieve a posts resource",
     *      operationId="Retrieve a posts resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="id, eg; 1",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *)
     **/
    public function show(string $id): PostResource
    {
        $post = $this->postModel->findOrFail($id);
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param string $id
     * @return JsonResponse
     */

    /**
     * @OA\Patch (
     *      path="/api/posts/{id}",
     *      tags={"Posts"},
     *      summary="Update a post resource",
     *      operationId="Update a post resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="id, eg; 1",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example=""),
     *              @OA\Property(property="content", type="string", example=""),
     *              @OA\Property(property="publish", type="boolean", example=""),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *)
     **/
    public function update(PostRequest $request, string $id): JsonResponse
    {
        $post = $this->postModel->findOrFail($id);
        DB::beginTransaction();
        try {
            $post->update($request->validated());
            return $this->responseSuccess('Post updated successfully.');
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->responseError($exception, 'Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */

    /**
     * @OA\Delete (
     *      path="/api/posts/{id}",
     *      tags={"Posts"},
     *      summary="Delete a post resource",
     *      operationId="Delete a post resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="id, eg; 1",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *)
     **/
    public function destroy(string $id): JsonResponse
    {
        $post = $this->postModel->findOrFail($id);
        try {
            $post->delete();
            return $this->responseSuccess('Post deleted successfully.');
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->responseError($exception, 'Something went wrong');
        }
    }
}
