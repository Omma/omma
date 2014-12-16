<?php
namespace Omma\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Range;

/**
 *
 * @author Adrian Woeltche
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingRecurringForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("type", "choice", array(
                "choices" => array(
                    "day"   => "day",
                    "week"  => "week",
                    "month" => "month",
                    "year"  => "year",
                )
            ))
            ->add("date_start", "datetime", array(
                "widget" => "single_text",
            ))
            ->add("date_end", "datetime", array(
                "widget" => "single_text",
            ))
        ;

        $config = $builder->create("config", "form", array(
            "compound" => true,
        ));
        $builder->add($config);
        $config
            ->add("every", "integer", array(
                "constraints" => array(new Range(array("min" => 1))),
            ))
            ->add("month_type", "choice", array(
                "choices" => array(
                    "absolute" => "absolute",
                    "relative" => "relative",
                ),
            ))
            ->add("month_weekdays", "collection", array(
                "type" => "text",
                "allow_add" => true,
                "allow_delete" => true,
            ))
            ->add("rel_month", "choice", array(
                "choices" => array(
                    "first"  => "first",
                    "second" => "second",
                    "third"  => "third",
                    "fourth" => "fourth",
                    "last"   => "last",
                ),
            ))
            ->add("rel_month_day", "integer", array(
                "constraints" => array(new Range(array("min" => 1, "max" => 7))),
            ))
            ->add("abs_month_day", "integer", array(
                "constraints" => array(new Range(array("min" => 1, "max" => 31))),
            ))
            ->add("end_type", "choice", array(
                "choices" => array(
                    "absolute" => "absolute",
                    "relative" => "relative",
                )
            ))
            ->add("end_date", "datetime", array(
                "widget" => "single_text",
            ))
            ->add("end_after", "integer", array(
                "constraints" => array(new Range(array("min" => 1))),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Omma\AppBundle\Entity\MeetingRecurring',
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
