<?php


namespace Hakam\AuthenticationBundle\Adapters;

/**
 * @category Authentication
 * @author   Ramy Hakam <pencilsoft1@gmail.com>
 * @link     https://github.com/RamyHakam/symfony-authentication-bundle
 */
interface TokenAdapterInterface
{
    /**
     * Generate a new Token based on the User token
     * @param string $userToken
     * @return string
     */
    public function encodeToken(string $userToken): string;

    /**
     * Decode User token and return The user data
     * @param string $token
     * @return array
     */
    public function decodeToken(string $token): array;

    /**
     * Validate Token
     * @param string $token
     * @return bool
     */
    public function isValidToken(string $token): bool;

    /**
     * check if the token is expired
     * @param string $token
     * @return bool
     */
    public function isExpiredToken(string $token): bool;
}