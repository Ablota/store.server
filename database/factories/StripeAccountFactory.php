<?php

namespace Database\Factories;

use App\Models\StripeAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class StripeAccountFactory extends Factory {
	protected $model = StripeAccount::class;

	public function definition() {
		return [
			StripeAccount::ACCOUNT_ID => $this->faker->unique()->randomNumber(),
		];
	}
}
