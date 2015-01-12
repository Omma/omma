<?php
namespace Omma\AppBundle\Voter;

use Omma\AppBundle\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Task security voter, to decide whether a task can be edited by a certain user
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class TaskVoter implements VoterInterface
{
    const EDIT = "edit";

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::EDIT,
        ));
    }

    public function supportsClass($class)
    {
        return $class === 'Omma\AppBundle\Entity\Task';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException("Only one attribute is allowed for VIEW or EDIT");
        }
        $attribute = $attributes[0];

        if (!$object instanceof Task) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        switch ($attribute) {
            case self::EDIT:
                if ($object->getUser() === $user) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
