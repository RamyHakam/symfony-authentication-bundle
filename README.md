# Symfony 4/5 API Authentication Bundle  
![](https://github.com/RamyHakam/symfony-authentication-bundle/workflows/current_build/badge.svg)


Symfony Authentication bundle provides JWT API token authentication for your symfony project with these features:  

  - Generate and validate encrypted JWT token 
  - Authenticate Login User with the token validation using symfony security
  - Refresh tokens before expiration (later)

### Installation

This bundle requires [symfony](https://symfony.org/) v4+ to run.

Install using Composer

```sh
$ composer require ramyhakam/symfony-authentication-bundle
``` 
 ### Using the Bundle
 1. First, make sure you've followed the main [Security Guide](https://symfony.com/doc/current/security.html) to create your User class. Then, to keep things simple, add an `apiToken` property directly to your User class
 2. Now you have a new entity for your Authentication that implements `Symfony\Component\Security\Core\User\UserInterface;`
 3. If you have a custom auth property rather than the default `apiToken`, Add it to the configuration file
 4. Enable the bundle in your `bundle.php` file by adding this line
 
     ```php
    return [ 
         //...
    Hakam\AuthenticationBundle\HakamAuthenticationBundle::class => ['all' => true],
    ];
 5. Then You need to use the Authenticator as your auth in your firewall by adding these lines in your `packages/security.yml` 
    ```yaml
    #example firewalls
    
    login:  
       pattern:   /api/login
       stateless: true
       scurity: false
       
     api:
         pattern:   ^/api
         stateless: true
          guard:
              authenticators:
                 - token-authenticator  #using jwt authenticator here
 
 6. Now you can inject `JWTTokenAuthenticatorService` and generate user tokens based on the user data
   
    ```php
    
    \\ Controller\AccountController.php
        
        namespace App\Controller;
    
    
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Hakam\AuthenticationBundle\Services\JWTTokenAuthenticatorService;
    
       public class AccountController extends AbstractController
    
       /**
        * @var JWTTokenAuthenticatorService
        */
        private $authenticatorService;
    
        public function __construct(JWTTokenAuthenticatorService $authenticatorService)
          {
             $this->authenticatorService = $authenticatorService;
          }
    
        //..
    
        public funcion generateToken()
         {
            $authToken = $this->authenticatorService->generateUserToken($user->getApiToken());
         }
    ```
 7. Now use can inject `UserIntetface` in your controller which will be replaced by the login User object after token authentication is verified.
    ```php
    \\ Controller\AccountController.php
    
    namespace App\Controller;

    
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    public class AccountController extends AbstractController
    
        /**
         * @param UserInterface $user
         * @Security("is_granted('ROLE_User') 
         */
        public function needsAuthUser( UserInterface $user)
          { 
              $user  // is the logged in user object with ROLE_USER
              //..
          }
    ```  
 ### Configuration
 
 1. Generate your private and public keys:
 ```sh
 $ openssl genrsa -out config/jwt/private.pem 2048
 $ openssl rsa -in config/jwt/private.pem -outform PEM -pubout -out config/jwt/public.pem
 ```
 2. After generating your public and private keys you should create the bundle configuration file 
 lives in
  `config/packages/hakam_authentication_bundle.yaml` with these configuration:
 ``` yaml 
  hakam_authentication:
      public_path_key:      '%kernel.project_dir%/config/jwt/public.pem'
      private_path_key:     '%kernel.project_dir%/config/jwt/private.pem'
      auth_property:        apiToken
 ```
             
### Contribution

Want to contribute? Great!
 - Fork your copy from the repository
 - Add your new Awesome features 
 - Write MORE Tests
 - Create a new Pull request 

License
----

# MIT
**Free Software, Hell Yeah!**
