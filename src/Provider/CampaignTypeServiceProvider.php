<?php

namespace Mailery\Campaign\Provider;

use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Di\Container;
use Yiisoft\Factory\Factory;
use Mailery\Campaign\Provider\CampaignTypeConfigs;
use Mailery\Campaign\Model\CampaignTypeList;

final class CampaignTypeServiceProvider extends ServiceProvider
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $factory = new Factory();
        $configs = $container->get(CampaignTypeConfigs::class)->getConfigs();
        foreach ($configs as $alias => $config) {
            $container->set($alias, fn () => $factory->create($config));
        }

        $container->set(
            CampaignTypeList::class,
            function () use($container, $configs) {
                $types = array_map(
                    function ($type) use($container) {
                        return $container->get($type);
                    },
                    array_keys($configs)
                );

                return new CampaignTypeList($types);
            }
        );
    }
}
