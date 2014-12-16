<?php
namespace Omma\AppBundle\Entity;

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
            ->orderBy("m.dateStart")
            ->setMaxResults(1)
            ->setParameter("recurring", $recurring)
            ->setParameter("date", $date)
            ->getQuery()
            ->getSingleResult()
            ;
    }
}
