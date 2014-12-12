<?php
namespace Omma\AppBundle\Voter;

use Omma\AppBundle\Entity\AttendeeEntityManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Omma\AppBundle\Entity\Meeting;

/**
 * Meeting Voter
 *
 * @author Adrian Woeltche
 */
class MeetingVoter implements VoterInterface
{
    const VIEW = "view";

    const EDIT = "edit";

    const OWNER = "owner";

    /**
     * @var AttendeeEntityManager
     */
    protected $attendeeEntityManager;

    public function __construct(AttendeeEntityManager $attendeeEntityManager)
    {
        $this->attendeeEntityManager = $attendeeEntityManager;
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::OWNER,
        ));
    }

    public function supportsClass($class)
    {
        return in_array($class, array(
            'Omma\AppBundle\Entity\Meeting'
        ));
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $user = $token->getUser();

        if (! $user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (! $this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException("Only one attribute is allowed for VIEW or EDIT");
        }
        $attribute = $attributes[0];

        if (!$object instanceof Meeting) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (self::OWNER !== $attribute and in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if (! $this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        switch ($attribute) {
            case self::VIEW:
                $count = $this->attendeeEntityManager->createQueryBuilder("a")
                    ->select("COUNT(a)")
                    ->where("a.meeting = :meeting AND a.user = :user")
                    ->setParameter("meeting", $object)
                    ->setParameter("user", $user)
                    ->getQuery()
                    ->getSingleScalarResult()
                ;
                if ($count > 0) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::OWNER:
                /* fall through */
            case self::EDIT:
                $count = $this->attendeeEntityManager->createQueryBuilder("a")
                    ->select("COUNT(a)")
                    ->where("a.meeting = :meeting AND a.user = :user AND a.owner = 1")
                    ->setParameter("meeting", $object)
                    ->setParameter("user", $user)
                    ->getQuery()
                    ->getSingleScalarResult()
                ;
                if ($count > 0) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
