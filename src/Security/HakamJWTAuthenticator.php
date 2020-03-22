<?php


namespace Hakam\AuthenticationBundle\Security;


use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Hakam\AuthenticationBundle\Adapters\TokenAdapter;
use Hakam\AuthenticationBundle\Services\JWTTokenAuthenticatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * @category Authentication
 * @author   Ramy Hakam <pencilsoft1@gmail.com>
 * @link     https://github.com/ramyhakam/JWTSymfonyAuthenticator
 */
class HakamJWTAuthenticator extends  AbstractGuardAuthenticator
{

    /**
     * @var JWTTokenAuthenticatorService
     */
    private $authenticatorService;
    /**
     * @var ServiceEntityRepository
     */
    private $userRepository;

    public function __construct(ServiceEntityRepository $userRepository,JWTTokenAuthenticatorService $authenticatorService)
    {
        $this->authenticatorService = $authenticatorService;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function getCredentials(Request $request)
    {
        return [
            'token' => $this->authenticatorService->decodeUserToken($request->headers->get('Authorization')),
            'userData' => $this->authenticatorService->getUserDataFromToken($request->headers->get('Authorization'))
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            return null;
        }

        if(array_values($credentials['userData'])[0] === TokenAdapter::TEMP_TOKEN)
        {
            return new User();
        }

        // if a User object, checkCredentials() is called
        return $this->userRepository
            ->findOneBy($credentials['userData']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

        ];
        return new JsonResponse($data, Response::HTTP_FORBIDDEN);

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return true;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}