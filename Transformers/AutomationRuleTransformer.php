<?php

namespace Modules\Requestable\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;

class AutomationRuleTransformer extends CrudResource
{
    /**
     * Method to merge values with response
     */
    public function modelAttributes($request)
    {
        return [
            'statusName' => $this->statusName,
        ];
    }
}
