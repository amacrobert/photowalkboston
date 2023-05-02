<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\ApplicationRecipient;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * @extends AbstractAdmin<ApplicationRecipient>
 */
final class ApplicationRecipientAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('email')
            ->add('name')
            ->add('active');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('email')
            ->add('name')
            ->add('active', null, ['editable' => true])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with(
                'Recipient Info',
                [
                    'class' => 'col-md-6',
                    'description' => 'Application recipients are people who receive emails from the "apply" form.',
                ]
            )
            ->add('email')
            ->add('name')
            ->add(
                'active',
                null,
                [
                    'help' => 'Turning this off will prevent this user from receiving membership application emails'
                ]
            )
            ->end();
    }
}
