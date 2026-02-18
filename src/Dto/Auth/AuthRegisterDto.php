<?php

namespace App\Dto\Auth;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * DTO to register a new user
 */
class AuthRegisterDto
{
    #[Assert\Email(message: 'Please enter a valid email address')]
    protected string $email;

    #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_STRONG)]
    protected string $password;

    #[Assert\EqualTo(propertyPath: 'password', message: 'Passwords do not match')]
    protected string $passwordConfirm;

    /**
     * @param string $email
     * @param string $password
     * @param string $passwordConfirm
     */
    public function __construct(string $email, string $password, string $passwordConfirm)
    {
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirm = $passwordConfirm;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirm(): string
    {
        return $this->passwordConfirm;
    }
}
