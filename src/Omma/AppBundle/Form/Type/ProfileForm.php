<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Profile form for user settings
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class ProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("username", "text", array(
                "label" => "app.profile.username",
            ))
            ->add("email", "email", array(
                "label" => "app.profile.email",
            ))
            ->add("firstname", "text", array(
                "label" => "app.profile.firstname",
            ))
            ->add("lastname", "text", array(
                "label" => "app.profile.lastname",
            ))
            ->add("plain_password", "password", array(
                "label"    => "app.profile.password",
                "required" => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "Application\Sonata\UserBundle\Entity\User"
        ));
    }

    public function getName()
    {
        return "omma_profile";
    }
}
