<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param mixed $attribute
     * @param mixed $subject
     * 
     * @return [type]
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['TASK_DELETE'])
            && $subject instanceof Task;
    }

    /**
     * @param mixed $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * 
     * @return [type]
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (null === $subject->getUser()) {
            if ($this->security->isGranted('ROLE_ADMIN')) {
                return true;
            }

            return false;
        }

        switch ($attribute) {
            case 'TASK_DELETE':
                return $subject->getUser()->getId() === $user->getId();
                break;
        }

        return false;
    }
}
