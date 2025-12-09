<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_belongs_to_company()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);

        $this->assertInstanceOf(Company::class, $user->company);
        $this->assertEquals($company->id, $user->company->id);
    }

    public function test_user_has_role()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isSuperAdmin());
    }

    public function test_user_is_active_by_default()
    {
        $user = User::factory()->create();

        $this->assertTrue($user->is_active);
    }

    public function test_company_scope_applies_to_user_queries()
    {
        // Create two companies
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();

        // Create users in each company
        $user1 = User::factory()->create(['company_id' => $company1->id]);
        $user2 = User::factory()->create(['company_id' => $company2->id]);

        // Authenticate as user1
        $this->actingAs($user1);

        // Query users
        $users = User::all();

        // Should only see users from company1
        $this->assertTrue($users->contains($user1));
        $this->assertFalse($users->contains($user2));
    }
}
