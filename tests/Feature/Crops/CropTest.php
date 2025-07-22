<?php

namespace Tests\Feature\Crops;

use App\Models\Crop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CropTest extends TestCase
{
    use RefreshDatabase;

    protected User $farmer;

    public function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Farmer']);
        $this->farmer = User::factory()->create();
        $this->farmer->assignRole('Farmer');
    }

    /**
     * Summary of test_farmer_can_create_crop
     * @return void
     */
    public function test_farmer_can_create_crop()
    {
        $response = $this->actingAs($this->farmer)->postJson('/api/crops/create', [
            'name' => 'Test Crop',
            'farmer_id' => $this->farmer->id,
            'description' => 'This is a valid crop description that is long enough.',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Successfully add new Crop']);
        $this->assertDatabaseHas('crops', ['name' => 'test crop']);
    }


    /**
     * Summary of test_farmer_can_view_all_crops
     * @return void
     */
    public function test_farmer_can_view_all_crops()
    {
        Crop::factory()->count(3)->create(['farmer_id' => $this->farmer->id]);

        $response = $this->actingAs($this->farmer)->getJson('/api/crops/get-all');

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'SuccessFully Get All Crops']);
    }

    /**
     * Summary of test_farmer_can_view_specific_crop
     * @return void
     */
    public function test_farmer_can_view_specific_crop()
    {
        $crop = Crop::factory()->create(['farmer_id' => $this->farmer->id]);

        $response = $this->actingAs($this->farmer)->getJson("/api/crops/{$crop->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Successfully Get Crop']);
    }

    /**
     * Summary of test_farmer_can_update_crop
     * @return void
     */
    public function test_farmer_can_update_crop()
    {
        $crop = Crop::factory()->create(['farmer_id' => $this->farmer->id]);

        $response = $this->actingAs($this->farmer)->postJson("/api/crops/update/{$crop->id}", [
            'name' => 'Updated Crop',
            'description' => 'Updated description that is also long enough to pass validation.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('crops', ['name' => 'updated crop']);
    }

    /**
     * Summary of test_farmer_can_delete_crop
     * @return void
     */
    public function test_farmer_can_delete_crop()
    {
        $crop = Crop::factory()->create(['farmer_id' => $this->farmer->id]);

        $response = $this->actingAs($this->farmer)->deleteJson("/api/crops/{$crop->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('crops', ['id' => $crop->id]);
    }

    /**
     * Summary of test_super_admin_can_create_crop
     * @return void
     */
    public function test_super_admin_can_create_crop()
    {
        $admin = User::factory()->create();
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        $admin->assignRole('SuperAdmin');
        $response = $this->actingAs($admin)->postJson('/api/crops/create', [
            'name' => 'Admin Crop',
            'description' => 'This description is long enough and valid for admin crop.',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Successfully add new Crop']);
        $this->assertDatabaseHas('crops', ['name' => 'admin crop']);
    }




    /**
     * Summary of test_super_admin_can_update_any_crop
     * @return void
     */
    public function test_super_admin_can_update_any_crop()
    {
        $admin = User::factory()->create();
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        $admin->assignRole('SuperAdmin');

        $crop = Crop::factory()->create([
            'farmer_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($admin)->postJson("/api/crops/update/{$crop->id}", [
            'name' => 'admin updated crop',
            'description' => 'This is a valid updated description by admin.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('crops', ['name' => 'admin updated crop']);
    }


    /**
     * Summary of test_super_admin_can_delete_any_crop
     * @return void
     */
    public function test_super_admin_can_delete_any_crop()
    {
        $admin = User::factory()->create();
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        $admin->assignRole('SuperAdmin');

        $crop = Crop::factory()->create([
            "farmer_id" => $this->farmer->id,
        ]);

        $response = $this->actingAs($admin)->deleteJson("/api/crops/{$crop->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('crops', ['id' => $crop->id]);
    }


    /**
     * Summary of test_super_admin_can_view_any_crop
     * @return void
     */
    public function test_super_admin_can_view_any_crop()
    {
        $admin = User::factory()->create();
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        $admin->assignRole('SuperAdmin');

        $crop = Crop::factory()->create(
            [
                'farmer_id' => $this->farmer->id
            ]
        );

        $response = $this->actingAs($admin)->getJson("/api/crops/{$crop->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Successfully Get Crop']);
    }



    /**
     * Summary of test_super_admin_can_view_all_crops
     * @return void
     */
    public function test_super_admin_can_view_all_crops()
    {

        $admin = User::factory()->create();
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        $admin->assignRole('SuperAdmin');

        $response = $this->actingAs($admin)->getJson('/api/crops/get-all');

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'SuccessFully Get All Crops']);
    }
}
