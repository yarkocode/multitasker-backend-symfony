<?php

namespace App\Security\Voter;

use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

/**
 * Project access voter
 * @extends Voter
 */
final class ProjectVoter extends Voter
{
    public const string EDIT = 'PROJECT_EDIT';
    public const string VIEW = 'PROJECT_VIEW';
    public const string DELETE = 'PROJECT_DELETE';
    public const string CREATE_TASKS = 'PROJECT_TASKS_CREATE';
    public const string UPDATE_TASKS = 'PROJECT_TASKS_UPDATE';
    public const string VIEW_TASKS = 'PROJECT_TASKS_VIEW';

    private const array ACCEPTED_ACTIONS = [
        self::EDIT,
        self::VIEW,
        self::DELETE,
        self::CREATE_TASKS,
        self::UPDATE_TASKS,
        self::VIEW_TASKS
    ];

    /**
     * Check supports voter vote access to subject
     *
     * @param string $attribute action to do with subject
     * @param mixed|Project $subject subject to get access
     * @return bool can vote
     * @see Voter
     *
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, self::ACCEPTED_ACTIONS)
            && $subject instanceof Project;
    }

    /**
     * Vote accept action or not
     *
     * @param string $attribute
     * @param Project $subject
     * @param TokenInterface $token
     * @param Vote|null $vote old decision
     * @return bool action accepted
     * @see Voter
     *
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
            self::EDIT, self::VIEW, self::DELETE, self::CREATE_TASKS, self::UPDATE_TASKS, self::VIEW_TASKS
            => $this->isUserCreator($subject, $user),
            default => false,
        };
    }

    /**
     * Return is user creator or not
     *
     * @param Project $Project Project
     * @param UserInterface $user user to do action
     * @return bool is user creator
     */
    private function isUserCreator(Project $Project, UserInterface $user): bool
    {
        return $Project->getCreatedBy() == $user->getUserIdentifier();
    }
}
