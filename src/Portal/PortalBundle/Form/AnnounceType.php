<?php

namespace Portal\PortalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

class AnnounceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('address')
            ->add('created', 'hidden') ->addViewTransformer(new DateTimeToStringTransformer())
            ->add('categoryId', null, array('label' => 'Category', 'property' => 'name'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Portal\PortalBundle\Entity\Announce',
        ));
    }

    public function getName()
    {
        return 'portal_portalbundle_announce';
    }
}
