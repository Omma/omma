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
class MeetingConfirmationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("status", "choice", array(
                "label"   => "app.confirmation.status.title",
                "choices" => array(
                    Attendee::STATUS_ACCEPTED => "app.confirmation.status.accepted",
                    Attendee::STATUS_DECLIED  => "app.confirmation.status.declined",
                    Attendee::STATUS_MAYBE    => "app.confirmation.status.maybe",
                ),
            ))
            ->add("message", "textarea", array(
                "label"    => "app.confirmation.message",
                "required" => false,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Attendee',
        ));
    }

    public function getName()
    {
        return "meeting_confirmation";
    }
}
