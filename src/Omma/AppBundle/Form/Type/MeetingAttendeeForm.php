<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form for meeting attendees
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAttendeeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("user", "entity", array(
                "class" => 'Application\Sonata\UserBundle\Entity\User',
            ))
            ->add("mandatory")
            ->add("owner", "text", array(
                "mapped" => false,
            ))
            ->add("status", "text", array(
                "mapped" => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Attendee'
        ));
    }

    public function getName()
    {
        return "";
    }
}
