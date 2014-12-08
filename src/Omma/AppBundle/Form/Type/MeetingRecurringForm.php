<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("type", "integer")
            ->add("recurring", "integer")
            ->add("dateStart", "datetime", array(
            "widget" => "single_text"
        ))
            ->add("dateEnd", "datetime", array(
            "widget" => "single_text"
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\MeetingRecurring'
        ));
    }

    public function getName()
    {
        return "";
    }
}
