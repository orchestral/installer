<?php

namespace Orchestra\Installation\Http\Presenters;

use Illuminate\Support\Fluent;
use Orchestra\Contracts\Html\Form\Factory as FormFactory;
use Orchestra\Contracts\Html\Form\Fieldset;
use Orchestra\Contracts\Html\Form\Grid as FormGrid;

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

    /**
     * Create form.
     *
     * @param  \Illuminate\Support\Fluent $model
     *
     * @return \Orchestra\Html\Form\FormBuilder
     */
    public function form(Fluent $model)
    {
        return $this->form->of('orchestra.install', function (FormGrid $form) use ($model) {
            $form->fieldset(\trans('orchestra/foundation::install.steps.account'), function (Fieldset $fieldset) use ($model) {
                $this->userForm($fieldset, $model);
            });

            $form->fieldset(\trans('orchestra/foundation::install.steps.application'), function (Fieldset $fieldset) use ($model) {
                $this->applicationForm($fieldset, $model);
            });
        });
    }

    /**
     * Application form section.
     *
     * @param  \Orchestra\Contracts\Html\Form\Fieldset  $fieldset
     * @param  \Illuminate\Support\Fluent  $model
     *
     * @return void
     */
    protected function applicationForm(Fieldset $fieldset, Fluent $model): void
    {
        $fieldset->control('text', 'site_name')
            ->label(\trans('orchestra/foundation::label.name'))
            ->value(\data_get($model, 'site.name'))
            ->attributes(['autocomplete' => 'off']);
    }

    /**
     * User form section.
     *
     * @param  \Orchestra\Contracts\Html\Form\Fieldset  $fieldset
     * @param  \Illuminate\Support\Fluent  $model
     *
     * @return void
     */
    protected function userForm(Fieldset $fieldset, Fluent $model): void
    {
        $fieldset->control('input:email', 'email')
            ->label(\trans('orchestra/foundation::label.users.email'));

        $fieldset->control('password', 'password')
            ->label(\trans('orchestra/foundation::label.users.password'));

        $fieldset->control('text', 'fullname')
            ->label(\trans('orchestra/foundation::label.users.fullname'))
            ->value('Administrator');
    }
}
