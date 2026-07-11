<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(DatabaseSeeder::class);
    }

    private function admin(): User
    {
        return User::where('username', 'admin')->first();
    }

    private function staff(): User
    {
        return User::where('username', 'staff')->first();
    }

    private function member(): User
    {
        return User::where('username', 'monsterluv_id')->first();
    }

    /** Seeder harus benar-benar menghasilkan admin & staff, bukan semuanya 'member'. */
    public function test_seeder_creates_real_admin_and_staff(): void
    {
        $this->assertSame('admin', $this->admin()->role);
        $this->assertSame('staff', $this->staff()->role);
        $this->assertSame('member', $this->member()->role);
    }

    public function test_admin_cannot_change_own_role(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->patch(route('admin.users.role', $admin), ['role' => 'member'])
            ->assertSessionHas('error');

        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_admin_cannot_deactivate_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle', $admin))
            ->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->is_active);
    }

    /** Kontrol positif: pengaman tidak boleh ikut memblokir aksi yang sah. */
    public function test_admin_can_change_another_users_role(): void
    {
        $member = $this->member();

        $this->actingAs($this->admin())
            ->patch(route('admin.users.role', $member), ['role' => 'staff'])
            ->assertSessionHas('success');

        $this->assertSame('staff', $member->fresh()->role);
    }

    public function test_admin_can_deactivate_another_user(): void
    {
        $member = $this->member();
        $this->assertTrue($member->is_active);

        $this->actingAs($this->admin())
            ->patch(route('admin.users.toggle', $member))
            ->assertSessionHas('success');

        $this->assertFalse($member->fresh()->is_active);
    }

    /** Akun nonaktif harus ditolak saat login. */
    public function test_deactivated_user_cannot_login(): void
    {
        $member = $this->member();
        $member->forceFill(['is_active' => false])->save();

        $this->post('/login', [
            'email' => $member->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /** Staff tidak boleh menyentuh manajemen pengguna (bukan sekadar menu disembunyikan). */
    public function test_staff_cannot_access_user_management(): void
    {
        $this->actingAs($this->staff())
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_staff_cannot_change_roles(): void
    {
        $member = $this->member();

        $this->actingAs($this->staff())
            ->patch(route('admin.users.role', $member), ['role' => 'admin'])
            ->assertForbidden();

        $this->assertSame('member', $member->fresh()->role);
    }
}
