<?php

use App\Models\User;

test('non_login_users_see_welcome_page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('non_login_users_can_see_login_page', function () {
    $response = $this->get('/admin/login');

    $response->assertStatus(200);
});

test('login_users_get_redirected_to_dashboard', function () {

   User::factory()->times(1)->create(['is_admin' => true]);

   $admin = User::first();
   
   $admin->assignRole('Super Admin');
        
    $this->actingAs($admin);

    $response = $this->get('/admin/login');

    $response->assertStatus(302);
});

it('can render dashboard for Admin', function () {

    User::factory()->times(1)->create(['is_admin' => true]);

    $admin = User::first();

    $admin->assignRole('Super Admin');

    $this->actingAs($admin);
   
    $this->get('/admin')->assertSuccessful();
   
});
