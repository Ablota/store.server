<?php

use App\Models\StripeAccount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
	public function up() {
		Schema::create(
			'stripe_accounts',
			function(Blueprint $table) {
				$table->id();
				$table->unsignedBigInteger(StripeAccount::USER_ID);
				$table->unsignedBigInteger(StripeAccount::ACCOUNT_ID);
				$table->timestamps();
			}
		);
	}

	public function down() {
		Schema::dropIfExists('stripe_accounts');
	}
}
