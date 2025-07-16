<?php

namespace App\Repo;

use Illuminate\Database\Eloquent\Model;

class BaseRepo
{
    public function __construct(public Model $model){}
}
