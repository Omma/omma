<?php
namespace Omma\AppBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Meeting Voter
 *
 * @author Adrian Woeltche
 */
class MeetingVoter implements VoterInterface
{

    const VIEW = "view";

    const EDIT = "edit";

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT
        ));
    }

    public function supportsClass($class)
    {
        return in_array($class, array(
            "Omma\\AppBundle\\Entity\\Meeting"
        ));
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $user = $token->getUser();

        if (! $user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if (! $this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException("Only one attribute is allowed for VIEW or EDIT");
        }

        $attribute = $attributes[0];

        if (! $this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        switch ($attribute) {
            case self::VIEW:
                break;
            case self::EDIT:
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}