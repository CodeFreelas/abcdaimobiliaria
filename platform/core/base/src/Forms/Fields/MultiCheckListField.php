<?php

namespace Srapid\Base\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class MultiCheckListField extends FormField
{

    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        return 'core/base::forms.fields.multi-check-list';
    }
}
