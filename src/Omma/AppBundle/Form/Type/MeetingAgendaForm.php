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
class MeetingAgendaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("item")
            ->add("sorting_order", "integer")
            ->add("parent", "entity", array(
                "class"    => "Omma\AppBundle\Entity\Agenda",
                "property" => "item"
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Agenda'
        ));
    }

    public function getName()
    {
        return "";
    }
}
