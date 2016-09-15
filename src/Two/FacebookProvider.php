<?php

namespace RingoProject\Socialite\Two;

use \Laravel\Socialite\Two\User;

class FacebookProvider extends \Laravel\Socialite\Two\FacebookProvider
{
	protected function mapUserToObject(array $user)
	{
		$avatarUrl = $this->graphUrl.'/'.$this->version.'/'.$user['id'].'/picture';

		return (new User)->setRaw($user)->map([
			'id'              => $user['id'],
			'nickname'        => null,
			'name'            => isset($user['name']) ? $user['name'] : null,
			'email'           => isset($user['email']) ? $user['email'] : null,
			'gender'          => isset($user['gender']) ? $user['gender'] : null,
			'avatar'          => $avatarUrl.'?type=normal',
			'avatar_original' => $avatarUrl.'?width=1920',
		]);
	}
}