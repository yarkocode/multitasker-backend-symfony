<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

/**
 * Task access voter
 * @extends Voter
 */
final class TaskVoter extends Voter
{
    public const string EDIT = 'TASK_EDIT';
    public const string VIEW = 'TASK_VIEW';
    public const string DELETE = 'TASK_DELETE';

    /**
     * Check supports voter vote access to subject
     *
     * @see Voter
     *
     * @param string $attribute action to do with subject
     * @param mixed|Task $subject subject to get access
     * @return bool can vote
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Task;
    }

    /**
     * Vote accept action or not
     *
     * @see Voter
     *
     * @param string $attribute
     * @param Task $subject
     * @param TokenInterface $token
     * @param Vote|null $vote old decision
     * @return bool action accepted
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::EDIT | self::VIEW | self::DELETE => $this->isUserCreator($subject, $user),
            default => false,
        };
    }

    /**
     * Return is user creator or not
     *
     * @param Task $task task
     * @param UserInterface $user user to do action
     * @return bool is user creator
     */
    private function isUserCreator(Task $task, UserInterface $user): bool
    {
        return $task->getCreatedBy() == $user->getUserIdentifier();
    }
}
