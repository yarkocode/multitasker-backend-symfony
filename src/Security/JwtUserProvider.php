<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class JwtUserProvider implements UserProviderInterface
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->userRepository->find($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->find((int)$identifier);
    }
}
