<?php

namespace Orchestra\Installation\Http\Presenters;

use Orchestra\Contracts\Html\Form\Fieldset;
use Orchestra\Contracts\Html\Form\Grid as FormGrid;
use Orchestra\Contracts\Html\Form\Factory as FormFactory;

class Setup
{
    /**
     * Construct a new User presenter.
     *
     * @param  \Orchestra\Contracts\Html\Form\Factory  $form
     */
    public function __construct(FormFactory $form)
    {
        $this->form = $form;
    }

    public function form($model)
    {
        return $this->form->of('orchestra.install', function (FormGrid $form) use ($model) {
            $form->fieldset(trans('orchestra/foundation::install.steps.account'), function (Fieldset $fieldset) use ($model) {
                $this->userForm($fieldset, $model);
            });

            $form->fieldset(trans('orchestra/foundation::install.steps.application'), function (Fieldset $fieldset) use ($model) {
                $this->applicationForm($fieldset, $model);
            });
        });
    }

    protected function applicationForm(Fieldset $fieldset, $model)
    {

        $fieldset->control('text', 'site_name')
            ->label(trans('orchestra/foundation::label.name'))
            ->value('Orchestra Platform');
    }

    protected function userForm(Fieldset $fieldset, $model)
    {
        $fieldset->control('input:email', 'email')
            ->label(trans('orchestra/foundation::label.users.email'));

        $fieldset->control('password', 'password')
            ->label(trans('orchestra/foundation::label.users.password'));

        $fieldset->control('text', 'fullname')
            ->label(trans('orchestra/foundation::label.users.fullname'))
            ->value('Administrator');
    }
}
