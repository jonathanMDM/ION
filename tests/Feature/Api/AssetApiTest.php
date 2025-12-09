<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Company;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AssetApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $company;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create();
        $this->user = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'admin',
        ]);

        // Generate API token manually for testing
        $token = 'test-token-1234567890';
        $this->user->api_token = Hash::make($token);
        $this->user->save();
        $this->token = $token;
    }

    public function test_can_list_assets_with_token()
    {
        // Create some assets
        Asset::factory()->count(3)->create([
            'company_id' => $this->company->id,
            'category_id' => Category::factory()->create(['company_id' => $this->company->id])->id,
            'location_id' => Location::factory()->create(['company_id' => $this->company->id])->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/assets');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'code', 'status', 'category', 'location'
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_cannot_access_assets_without_token()
    {
        $response = $this->getJson('/api/v1/assets');

        $response->assertStatus(401);
    }

    public function test_cannot_access_other_company_assets()
    {
        // Create another company and asset
        $otherCompany = Company::factory()->create();
        $otherAsset = Asset::factory()->create([
            'company_id' => $otherCompany->id,
            'category_id' => Category::factory()->create(['company_id' => $otherCompany->id])->id,
            'location_id' => Location::factory()->create(['company_id' => $otherCompany->id])->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/assets');

        $response->assertStatus(200);
        
        // Should not see the other asset
        $data = $response->json('data');
        $this->assertFalse(collect($data)->contains('id', $otherAsset->id));
    }

    public function test_admin_can_create_asset()
    {
        $category = Category::factory()->create(['company_id' => $this->company->id]);
        $subcategory = \App\Models\Subcategory::factory()->create(['category_id' => $category->id]);
        $location = Location::factory()->create(['company_id' => $this->company->id]);

        $assetData = [
            'name' => 'New Test Asset',
            'status' => 'available',
            'condition' => 'excellent',
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'location_id' => $location->id,
            'value' => 1000,
            'purchase_price' => 1200,
            'purchase_date' => '2023-01-01',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/assets', $assetData);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'New Test Asset']);

        $this->assertDatabaseHas('assets', [
            'name' => 'New Test Asset',
            'company_id' => $this->company->id
        ]);
    }
}
