<?php


namespace Hakam\AuthenticationBundle\Adapters;


interface TokenAdapterInterface
{
    /**
     * Generate a new Token based on the User data
     * @param array $userData
     * @return string
     */
    public function encodeToken(array $userData): string;

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