<?php

namespace App\Controller;

use App\Dto\Auth\AuthRegisterDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth')]
final class AuthController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @param JWTTokenManagerInterface $jwtTokenManager
     * @param AuthenticationSuccessHandler $authSuccessHandler
     */
    public function __construct(
        private readonly UserRepository               $userRepository,
        private readonly JWTTokenManagerInterface     $jwtTokenManager,
        private readonly AuthenticationSuccessHandler $authSuccessHandler,
    )
    {
    }

    /**
     * Register new user and login
     *
     * @param AuthRegisterDto $registerUserDto data for registration
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response<JWTAuthenticationSuccessResponse>
     */
    #[Route('/register', name: 'auth_register', methods: ['POST'])]
    public function register(#[MapRequestPayload(validationFailedStatusCode: 400)]
                             AuthRegisterDto             $registerUserDto,
                             UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->userRepository->userExistsByEmail($registerUserDto->getEmail()))
            throw new BadRequestException('Email already taken');

        $user = new User()
            ->setEmail($registerUserDto->getEmail())
            ->setRoles(['ROLE_USER']);

        $password = $passwordHasher->hashPassword($user, $registerUserDto->getPassword());

        $user->setPassword($password);
        $this->userRepository->save($user);

        $token = $this->jwtTokenManager->create($user);

        return $this->authSuccessHandler->handleAuthenticationSuccess($user, $token);
    }
}
