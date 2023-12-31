<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function testThatAUserHasToBeLoggedIn(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function testThatLoggedInUserCanReachHome(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    public function testThatUserCanSeeHisQuizzes(): void
    {
        User::factory(2)->has(Quiz::factory(1))->create();
        $user = User::first();
        $response = $this->actingAs($user)->get('/');
        $response->assertSee('#1');
    }
}