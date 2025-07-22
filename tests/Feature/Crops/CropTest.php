<?php

namespace Tests\Feature\Crops;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\CropManagement\Models\Crop;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CropTest extends TestCase
{
    use RefreshDatabase;

    protected User $farmer;

    /**
     * Summary of setUp
     * @return void
     */
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
            'name' => [
                'en' => 'test crop',
                'ar' => 'اختبار محصول',
            ],
            'description' => [
                'en' => 'This is a valid crop description that is long enough.',
                'ar' => 'هذا وصف صالح وطويل بما فيه الكفاية.',
            ],
            'farmer_id' => $this->farmer->id,

        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Successfully added new crop']);
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
            'name' => [
                'en' => 'test crop',
                'ar' => 'اختبار محصول',
            ],
            'description' => [
                'en' => 'This is a valid crop description that is long enough.',
                'ar' => 'هذا وصف صالح وطويل بما فيه الكفاية.',
            ],
        ]);

        $response->assertStatus(200);
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
            'name' => [
                'en' => 'test crop',
                'ar' => 'اختبار محصول',
            ],
            'description' => [
                'en' => 'This is a valid crop description that is long enough.',
                'ar' => 'هذا وصف صالح وطويل بما فيه الكفاية.',
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Successfully added new crop']);
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
            'name' => [
                'en' => 'admin updated crop',
                'ar' => 'محصول محدث من المسؤول',
            ],
            'description' => [
                'en' => 'This is a valid updated description by admin.',
                'ar' => 'هذا وصف محدث صالح بواسطة المسؤول.',
            ],
        ]);


        $response->assertStatus(200);
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
