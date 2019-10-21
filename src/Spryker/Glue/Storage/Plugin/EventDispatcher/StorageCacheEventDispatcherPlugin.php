<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Storage\Plugin\EventDispatcher;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Shared\Storage\StorageConstants;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Client\Storage\StorageClientInterface getClient()
 */
class StorageCacheEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE;

    /**
     * {@inheritDoc}
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::TERMINATE` event.
     * - Persists a request cache based on the `\Spryker\Shared\Storage\StorageConstants::STORAGE_CACHE_STRATEGY`.
     * - Caches all used `Redis::get()`s for a given request to perform `Redis::mget()` for all upcoming requests.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::TERMINATE, function (PostResponseEvent $postResponseEvent) use ($container) {
            if ($container->has(StorageConstants::STORAGE_CACHE_STRATEGY)) {
                $request = $postResponseEvent->getRequest();
                $this->getClient()->persistCacheForRequest($request, $container->get(StorageConstants::STORAGE_CACHE_STRATEGY));
            }
        });

        if (!$container->has(StorageConstants::STORAGE_CACHE_STRATEGY)) {
            $container->set(StorageConstants::STORAGE_CACHE_STRATEGY, self::STORAGE_CACHE_STRATEGY);
            $container->configure(StorageConstants::STORAGE_CACHE_STRATEGY, ['isGlobal' => true]);
        }

        return $eventDispatcher;
    }
}
