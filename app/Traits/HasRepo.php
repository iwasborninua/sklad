<?php

namespace App\Traits;

use App\Repo\BaseRepo;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait HasRepo
{

    private string $repoPath = "\\App\\Repo\\";
    public BaseRepo $repository;

    public function __construct()
    {
        $class = $this->repoPath . class_basename($this) . 'Repo';
        if (class_exists($class)) {
            $this->repository = new $class($this);
        }else{
            throw new NotFoundHttpException('Repository class for ' . get_class($this) . ' not found');
        }
    }
}
