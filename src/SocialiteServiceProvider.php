<?php

namespace RingoProject\Socialite;

class SocialiteServiceProvider extends \Laravel\Socialite\SocialiteServiceProvider
{
	public function register()
	{
		$this->app->singleton('Laravel\Socialite\Contracts\Factory', function ($app)
		{
			return new SocialiteManager($app);
		});
	}
}