<?php

namespace Fintem\MQNotificationBundle\Tests\Functional;

use Fintem\MQNotificationBundle\Tests\Functional\app\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class KernelTestCase.
 */
class KernelTestCase extends BaseKernelTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function getKernelClass()
    {
        return AppKernel::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $fs = new Filesystem();
        $fs->remove(__DIR__.'/app/cache');
        $fs->remove(__DIR__.'/app/logs');
    }
}