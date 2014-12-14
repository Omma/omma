<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Range;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("type", "integer")
            ->add("date_start", "datetime", array(
                "widget" => "single_text",
            ))
            ->add("date_end", "datetime", array(
                "widget" => "single_text",
            ))
        ;

        $recurring = $builder->create("config");
        $recurring
            ->add("every", "integer", array(
                "constraints" => array(new Range(array("min" => 1)))
            ))
            ->add("month_type", "choice", array(
                "choices" => array(
                    "absolute" => "absolute",
                    "relative" => "relative"
                )
            ))
            ->add("month_weekdays", "collection", array(
                "type" => "text"
            ))
            ->add("rel_month", "choice", array(
                "choices" => array(
                    "choices" => array(
                        "first"  => "first",
                        "second" => "second",
                        "third"  => "third",
                        "fourth" => "fourth",
                        "last"   => "last",
                    )
                )
            ))
            ->add("rel_month_day", "integer", array(
                "constraints" => array(new Range(array("min" => 1, "max" => 7)))
            ))
            ->add("abs_month_day", "integer", array(
                "constraints" => array(new Range(array("min" => 1, "max" => 31)))
            ))
            ->add("end_type", "choice", array(
                "choices" => array(
                    "absolute" => "absolute",
                    "relative" => "relative",
                )
            ))
            ->add("end_date", "datetime")
            ->add("end_after", "integer", array(
                "constraints" => arra(new Range(array("min" => 1)))
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\MeetingRecurring',
        ));
    }

    public function getName()
    {
        return "";
    }
}
