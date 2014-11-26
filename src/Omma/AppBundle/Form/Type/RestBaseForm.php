<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Base form type for forms used with REST controllers.
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class RestBaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // don't map id, created and updated fields to entity
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "csrf_protection" => false, // disable csrf for REST
        ));
    }

    public function getName()
    {
        return "omma_rest_base";
    }
}
