<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class Helper
{

    /**
     * @param Model $model
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public static function getUserByColumnAndValue(
        Model $model,
        string $column,
        string $value
    ) {
        return $model::getUserByColumnAndValue($column, $value);
    }

}
