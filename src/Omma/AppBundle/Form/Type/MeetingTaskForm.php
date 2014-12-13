<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingTaskForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("task")
            ->add("description")
            ->add("type")
            ->add("date", "datetime", array(
                "widget" => "single_text",
            ))
            ->add("priority", "integer")
            ->add("status", "integer")
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Task',
        ));
    }

    public function getName()
    {
        return "";
    }
}
