<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class BaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id", "text", array(
                "mapped" => false,
            ))
            ->add("created", "text", array(
                "mapped" => false,
            ))
            ->add("updated", "text", array(
                "mapped" => false,
            ))
        ;
    }

    public function getName()
    {
        return "omma_base";
    }
}
