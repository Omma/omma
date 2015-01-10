<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Holds a collection of {@link MeetingAgendaForm}
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAgendaCollectionForm extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "type"            => "omma_meeting_agenda",
            "allow_add"       => true,
            "allow_delete"    => true,
            "by_reference"    => false,
        ));
    }

    public function getParent()
    {
        return "collection";
    }

    public function getName()
    {
        return "omma_meeting_agenda_collection";
    }
}
