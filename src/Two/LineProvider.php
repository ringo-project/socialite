<?php

namespace RingoProject\Socialite\Two;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class LineProvider extends AbstractProvider implements ProviderInterface
{
	protected function getAuthUrl($state)
	{
		return $this->buildAuthUrlFromBase('https://access.line.me/dialog/oauth/weblogin', $state);
	}

	protected function getTokenUrl()
	{
		return 'https://api.line.me/v2/oauth/accessToken';
	}

	protected function getUserByToken($token)
	{
		$response = $this->getHttpClient()->get('https://api.line.me/v2/profile', [
			'headers' => [
				'X-Line-ChannelToken' => $token,
			],
		]);

		return json_decode($response->getBody(), true);
	}

	protected function mapUserToObject(array $user)
	{
		return (new User())->setRaw($user)->map([
			'id'     => $user['userId'],
			'name'   => $user['displayName'],
			'avatar' => $user['pictureUrl'],
		]);
	}

	protected function getTokenFields($code)
	{
		return [
			'client_id'     => $this->clientId,
			'client_secret' => $this->clientSecret,
			'code'          => $code,
			'redirect_uri'  => $this->redirectUrl,
			'grant_type'    => 'authorization_code',
		];
	}
}
