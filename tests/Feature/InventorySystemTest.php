<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff Test',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->category = Category::create([
            'name' => 'Elektronik',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Mouse Gaming',
            'stock' => 10,
            'price' => 150000,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_user_can_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_can_access_dashboard_and_users(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);

        $responseUsers = $this->actingAs($this->admin)->get('/users');
        $responseUsers->assertStatus(200);
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->staff)->get('/users');
        $response->assertStatus(403);
    }

    public function test_staff_can_view_products(): void
    {
        $response = $this->actingAs($this->staff)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Mouse Gaming');
    }

    public function test_stock_in_increases_product_stock(): void
    {
        $response = $this->actingAs($this->staff)->post('/stock-in', [
            'product_id' => $this->product->id,
            'quantity' => 5,
            'notes' => 'Penerimaan batch baru',
        ]);

        $response->assertRedirect(route('stock-in.index'));
        $this->product->refresh();
        $this->assertEquals(15, $this->product->stock);
    }

    public function test_stock_out_decreases_product_stock(): void
    {
        $response = $this->actingAs($this->staff)->post('/stock-out', [
            'product_id' => $this->product->id,
            'quantity' => 4,
            'notes' => 'Pengeluaran untuk kantor',
        ]);

        $response->assertRedirect(route('stock-out.index'));
        $this->product->refresh();
        $this->assertEquals(6, $this->product->stock);
    }

    public function test_stock_out_fails_if_quantity_exceeds_stock(): void
    {
        $response = $this->actingAs($this->staff)->post('/stock-out', [
            'product_id' => $this->product->id,
            'quantity' => 999, // exceeds stock (10)
            'notes' => 'Pengeluaran melebihi batas',
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);
    }
}
