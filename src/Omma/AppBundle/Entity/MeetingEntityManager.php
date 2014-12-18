<?php
namespace Omma\AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingEntityManager extends AbstractEntityManager
{
    /**
     * Get previous meeting in recurrings for a specific date
     *
     * @param MeetingRecurring $recurring
     * @param \DateTime        $date
     *
     * @return Meeting
     */
    public function findPrevious($recurring, $date)
    {
        return $this->createQueryBuilder("m")
            ->select("m")
            ->where("m.meetingRecurring = :recurring AND m.dateStart < :date")
            ->orderBy("m.dateStart", "DESC")
            ->setMaxResults(1)
            ->setParameter("recurring", $recurring)
            ->setParameter("date", $date)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    /**
     * Get next 5 meetings for a user
     *
     * @param UserInterface $user
     *
     * @return Meeting[]
     */
    public function getUpcommingForUser(UserInterface $user)
    {
        $date = new \DateTime();
        return $this->createQueryBuilder("m")
            ->select("m")
            ->leftJoin("m.attendees", "a")
            ->where("m.dateStart > :date AND a.user = :user")
            ->setParameter("user", $user)
            ->setParameter("date", $date)
            ->orderBy("m.dateStart", "ASC")
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
}
