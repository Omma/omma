<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", "text")
            ->add("date_start", "datetime", array(
                "widget" => "single_text",
            ))
            ->add("date_end", "datetime", array(
                "widget" => "single_text",
            ))
            ->add("prev", "entity", array(
                "class"    => 'Omma\AppBundle\Entity\Meeting',
                "property" => "id",
            ))
            ->add("meeting_recurring", new MeetingRecurringForm())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Meeting',
        ));
    }

    public function getParent()
    {
        return "omma_rest_base";
    }

    public function getName()
    {
        return "";
    }
}
