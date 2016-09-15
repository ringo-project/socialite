<?php

namespace RingoProject\Socialite\Two;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

use GuzzleHttp\ClientInterface;

class YahooProvider extends AbstractProvider implements ProviderInterface
{
	protected $scopeSeparator = ' ';

	protected $scopes = [
		'openid',
		'profile',
		'email',
	];

	protected function getAuthUrl($state)
	{
		return $this->buildAuthUrlFromBase('https://auth.login.yahoo.co.jp/yconnect/v1/authorization', $state);
	}

	protected function getTokenUrl()
	{
		return 'https://auth.login.yahoo.co.jp/yconnect/v1/token';
	}

	protected function getUserByToken($token)
	{
		$response = $this->getHttpClient()->get('https://userinfo.yahooapis.jp/yconnect/v1/attribute?schema=openid', [
			'headers' => [
				'Authorization' => 'Bearer '.$token,
			],
		]);

		return json_decode($response->getBody(), true);
	}

	protected function mapUserToObject(array $user)
	{
		return (new User())->setRaw($user)->map([
			'id'         => $user['user_id'],
			'name'       => $user['name'],
			'email'      => $user['email'],
			'birth_year' => $user['birthday'],
			'gender'     => $user['gender'],
		]);
	}

	/**
	 * Basic認証が必要なのでOverWriteする。
	 * @param string $code
	 * @return mixed
	 */
	public function getAccessTokenResponse($code)
	{
		$postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

		$basic_auth_key = base64_encode($this->clientId.":".$this->clientSecret);

		$response = $this->getHttpClient()->post($this->getTokenUrl(), [
			'headers' => [
				'Authorization' => 'Basic '.$basic_auth_key,
				'Content-Type'  => 'application/x-www-form-urlencoded',
			],
			$postKey  => $this->getTokenFields($code),
		]);

		return json_decode($response->getBody(), true);
	}

	/**
	 * TokenFieldsに過不足があるのでOverWriteする。
	 * :Basic認証のため不要
	 *    - client_id
	 *    - client_secret
	 * :必須項目追加
	 *    + grant_type
	 * @param string $code
	 * @return array
	 */
	protected function getTokenFields($code)
	{
		return [
			'code'         => $code,
			'redirect_uri' => $this->redirectUrl,
			'grant_type'   => 'authorization_code',
		];
	}
}