<?php


namespace Hakam\AuthenticationBundle\Adapters;


use DateTime;
use Firebase\JWT\JWT;

/**
 * @category Authentication
 * @author   Ramy Hakam <pencilsoft1@gmail.com>
 * @link     https://github.com/RamyHakam/symfony-authentication-bundle
 */
class TokenAdapter implements TokenAdapterInterface
{
    const TEMP_TOKEN = 'temp_token';
    /**
     * @var JWT
     */
    private $JWTToken;
    /**
     * @var string
     */
    private $publicKey;
    /**
     * @var string
     */
    private $privateKey;

    /**
     * TokenAdapter constructor.
     * @param JWT $JWTToken
     * @param string $publicKeyPath
     * @param string $privateKeyPath
     */
    public function __construct(JWT $JWTToken, string $publicKeyPath, string $privateKeyPath)
    {
        $this->JWTToken = $JWTToken;
        $this->publicKey = file_get_contents($publicKeyPath);
        $this->privateKey = file_get_contents($privateKeyPath);
    }

    /**
     * @param string $apiKey
     * @return string
     */
    public function encodeToken(string $apiKey): string
    {
        return $this->JWTToken::encode($this->getPayload($apiKey), $this->privateKey, 'RS256');
    }

    /**
     * Generate the token payload using the User object
     * The default expired Date is 1 day after generating the token
     * @param string $apiKey
     * @param DateTime $expiredAt
     * @return array
     */
    private function getPayload(string $apiKey, DateTime $expiredAt = null): array
    {
        return [
            "iat" => time(),
            "exp" => $expiredAt? $expiredAt->getTimestamp() : strtotime('+1 day', time()),
            "sub" => "JWT Token For User",
            'apiKey' => $apiKey
        ];
    }

    /**
     * @param string $token
     * @return array
     */
    public function decodeToken(string $token): array
    {
        return (array)$this->JWTToken::decode($this->GetJWtTokenStringFromHeaderValue($token), $this->publicKey, ['RS256']);
    }

    /**
     * Get The Token string value from the header value
     * @param string $authorizationHeaderValue
     * @return string|null
     */
    private function GetJWtTokenStringFromHeaderValue(string $authorizationHeaderValue): ?string
    {
        if (preg_match('/Bearer\s(\S+)/', $authorizationHeaderValue, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function isValidToken(string $token): bool
    {
        // TODO: Implement isValidToken() method.
    }

    public function isExpiredToken(string $token): bool
    {
        // TODO: Implement isExpiredToken() method.
    }
}