<?php

namespace Tests\Feature;

use App\User;
use App\Snippet;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SnippetTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();

        $this->token = JWTAuth::attempt([
            'username' => $this->user->username,
            'password' => 'password'
        ]);
    }

    /** @test */
    public function user_can_create_snippet()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/snippets', [
            'title' => 'Test Snippet',
            'code' => '<?php echo "Hello"; ?>',
            'language' => 'php'
        ]);

        $response->assertStatus(201)->assertJsonStructure(['id', 'title', 'code', 'language']);
    }

    /** @test */
    public function can_filter_snippets_by_language()
    {
        factory(Snippet::class)->create(['language' => 'php']);
        factory(Snippet::class)->create(['language' => 'javascript']);

        $response = $this->getJson('/api/snippets?language=php');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['language' => 'php']
                ]
            ]);
    }

    /** @test */
    public function user_can_comment_on_snippet()
    {
        $snippet = factory(Snippet::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/snippets/{$snippet->id}/comments", [
            'comment' => 'Test comment'
        ]);

        $response->assertStatus(201)->assertJsonStructure(['message', 'comment']);
    }

    /** @test */
    public function user_can_like_snippet()
    {
        $snippet = factory(Snippet::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/snippets/{$snippet->id}/like");

        $response->assertStatus(201)->assertJson(['message' => 'Snippet liked successfully']);
    }

    /** @test */
    public function user_cannot_like_snippet_twice()
    {
        $snippet = factory(Snippet::class)->create();

        // First like
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/snippets/{$snippet->id}/like");

        // Second like
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/snippets/{$snippet->id}/like");

        $response->assertStatus(400)->assertJson(['message' => 'You have already liked this snippet.']);
    }
}
