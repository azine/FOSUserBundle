<?php

namespace FOS\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseContainerAwareCommand extends Command
{
    /** @var ContainerInterface|null */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function getContainer()
    {
        if (null === $this->container) {
            throw new \LogicException('The container is not set.');
        }

        return $this->container;
    }
}
