<?php

namespace RingoProject\Socialite;

class SocialiteManager extends \Laravel\Socialite\SocialiteManager
{
	protected function createFacebookDriver()
	{
		$config = $this->app['config']['services.facebook'];

		return $this->buildProvider('RingoProject\Socialite\Two\FacebookProvider', $config);
	}

	protected function createYahooDriver()
	{
		$config = $this->app['config']['services.yahoo'];

		return $this->buildProvider('RingoProject\Socialite\Two\YahooProvider', $config);
	}

	protected function createLineDriver()
	{
		$config = $this->app['config']['services.line'];

		return $this->buildProvider('RingoProject\Socialite\Two\LineProvider', $config);
	}
}
