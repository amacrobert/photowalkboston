<?php

namespace App;

use Bref\SymfonyBridge\BrefKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

class Kernel extends BrefKernel
{
    use MicroKernelTrait;

    public function allowEmptyBuildDir(): bool
    {
        return false;
    }

    public function getBuildDir(): string
    {
        $build_dir =  $this->getProjectDir() . '/var/build/' . $this->environment;

        if (!file_exists($build_dir) && is_writable($this->getProjectDir())) {
            mkdir(
                directory: $build_dir,
                recursive: true,
            );
        }

        if ($this->isLambda() && !is_dir($build_dir)) {
            if (!$this->allowEmptyBuildDir()) {
                throw new \Exception(
                    'You must warm and deploy the build directory as part of your Lambda package
                    as this directory is readonly on Lambda.
                    You can alternatively define the function allowEmptyBuildDir() in your Kernel class
                    and return "true" if you still want to deploy without a warm build directory.
                '
                );
            }

            return $this->getCacheDir();
        }

        return $build_dir;
    }
}
