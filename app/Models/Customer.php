<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable {
	use HasApiTokens, HasFactory, Notifiable;

	const ID = 'id';

	protected $fillable = [];
	protected $hidden = [];
	protected $casts = [];

	public function stripeAccount() {
		return $this->hasOne(StripeAccount::class);
	}
}
