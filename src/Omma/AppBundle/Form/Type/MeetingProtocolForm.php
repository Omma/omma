<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form for meeting protocols
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingProtocolForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("text")
            ->add("final")
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\Protocol',
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
