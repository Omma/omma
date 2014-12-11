<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAgendaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name")
            ->add("sorting_order", "integer")
        ;
        if ($options['recursion_level'] > 0) {
            $builder->add("children", "omma_meeting_agenda_collection", array(
                "options" => array(
                    "recursion_level" => $options['recursion_level'] - 1,
                )
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Agenda',
            "recursion_level" => 4,
        ));
    }

    public function getParent()
    {
        return "omma_rest_base";
    }

    public function getName()
    {
        return "omma_meeting_agenda";
    }
}
