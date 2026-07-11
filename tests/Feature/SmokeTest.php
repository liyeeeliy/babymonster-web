<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Cart;
use App\Models\ForumPost;
use App\Models\Member;
use App\Models\News;
use App\Models\Order;
use App\Models\User;
use App\Models\VotePoll;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();          // stub @vite agar fokus ke logika Blade
        $this->seed(DatabaseSeeder::class);
    }

    private function member(): User
    {
        return User::where('username', 'monsterluv_id')->first();
    }

    public function test_public_pages_render(): void
    {
        $album = Album::first();
        $news = News::where('status', 'published')->first();
        $member = Member::first();
        $post = ForumPost::first();

        collect([
            '/', '/store', "/store/{$album->slug}",
            '/members', "/members/{$member->slug}",
            '/news', "/news/{$news->slug}",
            '/forum', "/forum/{$post->id}",
            '/search', '/search?q=drip',
            '/login', '/register', '/forgot-password',
        ])->each(fn ($url) => $this->get($url)->assertOk());
    }

    public function test_member_pages_render(): void
    {
        $user = $this->member();
        $this->actingAs($user);

        $poll = VotePoll::first();
        $order = Order::where('user_id', $user->id)->where('status', '!=', 'pending')->first();
        $pending = Order::where('user_id', $user->id)->where('status', 'pending')->first();

        Cart::create([
            'user_id' => $user->id,
            'purchasable_type' => Album::first()->getMorphClass(),
            'purchasable_id' => Album::first()->id,
            'quantity' => 1,
        ]);

        collect(array_filter([
            '/cart', '/checkout', '/orders', '/profile',
            '/voting', "/voting/{$poll->id}/results", '/forum-create',
            $order ? "/orders/{$order->id}" : null,
            $order ? "/orders/{$order->id}/invoice" : null,
            $pending ? "/checkout/{$pending->id}/pay" : null,
        ]))->each(fn ($url) => $this->get($url)->assertOk());
    }

    public function test_admin_pages_render(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $album = Album::first();
        $news = News::first();
        $member = Member::first();
        $order = Order::first();

        collect([
            '/admin/dashboard',
            '/admin/albums', '/admin/albums/create', "/admin/albums/{$album->id}/edit",
            '/admin/news', '/admin/news/create', "/admin/news/{$news->id}/edit",
            '/admin/members', '/admin/members/create', "/admin/members/{$member->id}/edit",
            '/admin/voting', '/admin/voting/create',
            '/admin/orders', "/admin/orders/{$order->id}",
            '/admin/users', '/admin/activity-log',
        ])->each(fn ($url) => $this->get($url)->assertOk());
    }

    public function test_member_cannot_access_admin(): void
    {
        $this->actingAs($this->member());
        $this->get('/admin/dashboard')->assertForbidden();
        $this->get('/admin/users')->assertForbidden();
    }

    public function test_guest_redirected_from_member_area(): void
    {
        $this->get('/cart')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
    }
}
