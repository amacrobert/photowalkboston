<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Application\Application;
use App\Entity\Application\ApplicationStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('approve', $this->getRouterIdParameter() . '/approve')
            ->add('reject', $this->getRouterIdParameter() . '/reject');
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name')
            ->add('email')
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
            ->add('status')
            ->add('created', null, ['label' => 'Application date'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'approve' => ['template' => 'CRUD/list__action_approve.html.twig'],
                    'reject' => ['template' => 'CRUD/list__action_reject.html.twig'],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with(
                'Application',
                [
                    'class' => 'col-md-6',
                    'description' => 'Only edit these to make corrections.'
                ]
            )
            ->add('name')
            ->add('email')
            ->add('event')
            ->add('instagram')
            ->add('pursuit')
            ->add('referral')
            ->add('website')
            ->add('experience')
            ->add('openResponse')
            ->end();

        $form
            ->with(
                'Admin',
                [
                    'class' => 'col-md-6',
                    'description' => 'Accepted applicants will receive email invites to future events.'
                ]
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' => array_combine(
                        array_column(ApplicationStatus::cases(), 'name'),
                        array_column(ApplicationStatus::cases(), 'value')
                    )
                ]
            )
            ->end();
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
