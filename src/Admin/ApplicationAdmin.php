<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Application;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @extends AbstractAdmin<Application>
 */
final class ApplicationAdmin extends AbstractAdmin
{
    public function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::SORT_BY] = 'created';
        $sortValues[DatagridInterface::SORT_ORDER] = 'DESC';
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name')
            ->add('instagram')
            ->add('pursuit')
            ->add('referral')
            ->add('website')
            ->add('experience')
            ->add('openResponse')
            ->add('created');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('name')
            ->add('instagram')
            ->add('pursuit')
            ->add('referral')
            ->add('created', null, ['label' => 'Application date'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('event')
            ->add('instagram')
            ->add('pursuit')
            ->add('referral')
            ->add('website')
            ->add('experience')
            ->add('openResponse');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('created')
            ->add('name')
            ->add('event')
            ->add('instagram')
            ->add('pursuit')
            ->add('referral')
            ->add('website')
            ->add('experience')
            ->add('openResponse');
    }
}
