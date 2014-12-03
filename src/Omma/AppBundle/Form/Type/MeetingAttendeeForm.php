<?php
namespace Omma\AppBundle\Form\Type;

use Omma\AppBundle\Entity\Attendee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAttendeeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("user", "entity", array(
                "class" => "Application\Sonata\UserBundle\Entity\User",
            ))
            ->add("mandatory")
            ->add("status", "choice", array(
                "choices" => array(
                    Attendee::STATUS_ACCEPTED => Attendee::STATUS_ACCEPTED,
                    Attendee::STATUS_DECLIED => Attendee::STATUS_DECLIED,
                    Attendee::STATUS_INVITED => Attendee::STATUS_INVITED,
                    Attendee::STATUS_MAYBE => Attendee::STATUS_MAYBE,
                ),
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
