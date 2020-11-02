<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\StripeAccount;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	public function run() {
		Customer::factory(10)->create();
		StripeAccount::factory(10)->create();
	}
}
