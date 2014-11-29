<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", "text")
            ->add("dateStart", "datetime", array(
            "widget" => "single_text"
        ))
            ->add("dateEnd", "datetime", array(
            "widget" => "single_text"
        ))
            ->add("prev", "integer")
            ->add("next", "integer");
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "Omma\\AppBundle\\Entity\\Meeting"
        ));
    }

    public function getName()
    {
        return "";
    }
}
