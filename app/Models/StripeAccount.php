<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeAccount extends Model {
	use HasFactory;

	const ID = 'id';
	const USER_ID = 'user_id';
	const ACCOUNT_ID = 'account_id';

	public function customer() {
		return $this->belongsTo(Customer::class);
	}
}
