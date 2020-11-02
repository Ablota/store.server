<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
	public const HOME = '/';
	protected $namespace = 'App\\Http\\Controllers';

	public function boot() {
		$this->configureRateLimiting();

		$this->routes(
			function() {
				Route::prefix('v1')
					->middleware('api')
					->namespace($this->namespace . '\\V1')
					->group(base_path('routes/api_v1.php'));

				Route::middleware('web')
					->namespace($this->namespace)
					->group(base_path('routes/web.php'));
			}
		);
	}

	protected function configureRateLimiting() {
		RateLimiter::for(
			'api',
			function(Request $request) {
				return Limit::perMinute(1000)->by(optional($request->user())->id ?: $request->ip());
			}
		);
	}
}
