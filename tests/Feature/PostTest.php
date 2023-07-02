<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test retrieving all posts.
     *
     * @return void
     */
    public function testGetAllPost()
    {
        $this->json('GET', 'api/posts', ['Accept' => 'application/json'])->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'publish',
                        'created_at'
                    ]
                ],
                'message',
                'status',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active'
                        ]
                    ],
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    /**
     * Test creating a new post.
     *
     * @return void
     */
    public function testCreatePost()
    {
        $postData = Post::factory()->make();
        $this->postJson('api/posts', $postData->toArray(), ['Accept' => 'application/json'])->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'status'
            ]);
        $this->assertDatabaseHas('posts',[
            "title" => $postData->title,
            "content" => $postData->content,
            "publish" => $postData->publish
        ]);
    }

    /**
     * Test updating a specific post.
     *
     * @return void
     */
    public function testUpdatePost()
    {
        $post = Post::factory()->create();

        $postData = [
            "title" => "",
            "content" => "",
            "publish" => false
        ];
        $this->json('PATCH', 'api/posts/'.$post->id, $postData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'status'
            ]);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            "title" => "This is test post title.",
            "content" => "This is test post content.",
            "publish" => false
        ]);
    }

    /**
     * Test retrieving a specific post.
     *
     * @return void
     */
    public function testGetPost()
    {
        $post = Post::factory()->create();
        $this->json('get', 'api/posts/'.$post->id, ['Accept' => 'application/json'])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'publish' => $post->publish
                ],
                'message' => 'Post fetched successfully.',
                'status' => 200
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'publish'
                ],
                'message',
                'status'
            ]);
    }

    /**
     * Test deleting a specific post
     *
     * @return void
     */
    public function testDeletePost()
    {
        $post = Post::factory()->create();
        $this->json('DELETE', 'api/posts/'.$post->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'status'
            ]);
    }
}
