<?php

namespace App\Api;

trait LoggingTrait
{
    public function add(array $attributes = [])
    {
        $model = $this->model->newInstance();

        $model->fill($attributes);
        if ($model->save()) {
            return $model->save();
        }

        return false;
    }
}
