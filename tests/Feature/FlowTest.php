<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\ForumPost;
use App\Models\Order;
use App\Models\User;
use App\Models\Vote;
use App\Models\VotePoll;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(DatabaseSeeder::class);
    }

    private function member(): User
    {
        return User::where('username', 'monsterluv_id')->first();
    }

    public function test_user_can_register_as_member(): void
    {
        $this->post('/register', [
            'name' => 'Test Fan', 'username' => 'test_fan', 'email' => 'test@fan.test',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'test@fan.test']);
        $this->assertSame('member', User::where('email', 'test@fan.test')->first()->role);
    }

    public function test_register_cannot_escalate_role(): void
    {
        $this->post('/register', [
            'name' => 'Hacker', 'username' => 'hacker', 'email' => 'h@x.test',
            'password' => 'password123', 'password_confirmation' => 'password123',
            'role' => 'admin', 'is_active' => 1,
        ]);

        $this->assertSame('member', User::where('email', 'h@x.test')->first()->role);
    }

    public function test_purchase_and_simulated_payment_flow(): void
    {
        $user = $this->member();
        $album = Album::first();
        $stockBefore = $album->stock;
        $this->actingAs($user);

        $this->post('/cart', ['album_id' => $album->id, 'quantity' => 2])->assertRedirect();
        $this->assertDatabaseHas('carts', ['user_id' => $user->id, 'purchasable_id' => $album->id, 'quantity' => 2]);

        $this->post('/checkout', [
            'recipient_name' => 'Test', 'address' => 'Jl. Test 1', 'phone' => '0812', 'courier' => 'JNE',
        ])->assertRedirect();

        $order = Order::where('user_id', $user->id)->where('status', 'pending')->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertSame(20000, (int) $order->shipping_cost);                 // ongkir server-side JNE
        $this->assertSame($album->price * 2 + 20000, (int) $order->total);
        $this->assertSame($stockBefore - 2, (int) $album->fresh()->stock);     // stok berkurang
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id, 'purchasable_id' => $album->id]); // cart kosong

        $this->post("/checkout/{$order->id}/pay", ['payment_method' => 'ewallet'])->assertRedirect();
        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertDatabaseHas('payments', ['order_id' => $order->id, 'status' => 'paid', 'method' => 'ewallet']);
    }

    public function test_shipping_cost_is_not_trusted_from_client(): void
    {
        $user = $this->member();
        $album = Album::first();
        $this->actingAs($user);
        $this->post('/cart', ['album_id' => $album->id, 'quantity' => 1]);

        // Penyerang mengirim shipping_cost=0 — harus diabaikan.
        $this->post('/checkout', [
            'recipient_name' => 'X', 'address' => 'Y', 'phone' => '0', 'courier' => 'JNE', 'shipping_cost' => 0,
        ]);

        $order = Order::where('user_id', $user->id)->latest('id')->first();
        $this->assertSame(20000, (int) $order->shipping_cost);
    }

    public function test_cannot_pay_another_users_order(): void
    {
        $user = $this->member();
        $album = Album::first();
        $this->actingAs($user);
        $this->post('/cart', ['album_id' => $album->id, 'quantity' => 1]);
        $this->post('/checkout', ['recipient_name' => 'X', 'address' => 'Y', 'phone' => '0', 'courier' => 'JNE']);
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->latest('id')->first();

        $this->actingAs(User::where('role', 'staff')->first());
        $this->post("/checkout/{$order->id}/pay", ['payment_method' => 'qris'])->assertForbidden();
    }

    public function test_voting_one_vote_per_poll(): void
    {
        $user = $this->member();
        $poll = VotePoll::first();
        $opt = $poll->options()->first();
        $before = $opt->vote_count;
        $this->actingAs($user);

        $this->post("/voting/{$poll->id}/vote", ['option_id' => $opt->id])->assertRedirect();
        $this->assertSame($before + 1, (int) $opt->fresh()->vote_count);

        // Vote kedua tidak boleh menambah suara.
        $this->post("/voting/{$poll->id}/vote", ['option_id' => $opt->id]);
        $this->assertSame(1, Vote::where('vote_poll_id', $poll->id)->where('user_id', $user->id)->count());
    }

    public function test_forum_post_comment_and_like_toggle(): void
    {
        $user = $this->member();
        $this->actingAs($user);

        $this->post('/forum', ['title' => 'Halo MONBEBE', 'content' => 'Isi post pertama'])->assertRedirect();
        $post = ForumPost::where('title', 'Halo MONBEBE')->first();
        $this->assertNotNull($post);

        $this->post("/forum/{$post->id}/comments", ['content' => 'Komentar keren'])->assertRedirect();
        $this->assertDatabaseHas('comments', ['content' => 'Komentar keren']);

        // Balasan berjenjang (reply dengan parent_id)
        $parent = \App\Models\Comment::where('content', 'Komentar keren')->first();
        $this->post("/forum/{$post->id}/comments", ['content' => 'Balasan!', 'parent_id' => $parent->id])->assertRedirect();
        $this->assertDatabaseHas('comments', ['content' => 'Balasan!', 'parent_id' => $parent->id]);

        $this->post('/likes/toggle', ['type' => 'forum_post', 'id' => $post->id]);
        $this->assertSame(1, $post->likes()->count());
        $this->post('/likes/toggle', ['type' => 'forum_post', 'id' => $post->id]);
        $this->assertSame(0, $post->likes()->count());
    }

    public function test_member_can_review_album(): void
    {
        $user = $this->member();
        $album = Album::first();
        $this->actingAs($user);

        $this->post("/store/{$album->slug}/review", ['rating' => 5, 'review' => 'Album terbaik!'])->assertRedirect();
        $this->assertDatabaseHas('product_reviews', ['reviewable_id' => $album->id, 'user_id' => $user->id, 'rating' => 5]);
    }

    public function test_admin_can_crud_album(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $this->post('/admin/albums', [
            'name' => 'Album Baru', 'price' => 150000, 'stock' => 10,
            'description' => 'Deskripsi', 'tracklist_text' => "Lagu 1\nLagu 2",
        ])->assertRedirect();

        $this->assertDatabaseHas('albums', ['name' => 'Album Baru', 'price' => 150000]);
        $album = Album::where('name', 'Album Baru')->first();
        $this->assertSame(['Lagu 1', 'Lagu 2'], $album->tracklist);        // textarea → array
        $this->assertDatabaseHas('activity_logs', ['action' => 'album.create']); // audit log

        $this->delete("/admin/albums/{$album->id}")->assertRedirect();
        $this->assertDatabaseMissing('albums', ['id' => $album->id]);
    }
}
