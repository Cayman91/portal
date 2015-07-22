<?php

namespace Portal\PortalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnnounceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('address')
            ->add('created')
            ->add('userId')
            ->add('categoryId')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Portal\PortalBundle\Entity\Announce'
        ));
    }

    public function getName()
    {
        return 'portal_portalbundle_announce';
    }
}
