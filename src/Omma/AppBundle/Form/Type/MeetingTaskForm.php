<?php
namespace Omma\AppBundle\Form\Type;

use Omma\AppBundle\Entity\Task;
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
            ->add("type", "integer")
            ->add("date", "datetime", array(
                "widget" => "single_text",
            ))
            ->add("priority", "integer")
            ->add("status", "choice", array(
                "choices" => array(
                    Task::STATUS_OPEN => Task::STATUS_OPEN,
                    Task::STATUS_CLOSED => Task::STATUS_CLOSED,
                    Task::STATUS_IN_PROGESS => Task::STATUS_IN_PROGESS,
                ),
            ))
            ->add("user", "entity", array(
                "class"    => 'Application\Sonata\UserBundle\Entity\User',
                "property" => "id",
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Task',
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
