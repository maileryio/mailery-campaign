<?php

declare(strict_types=1);

namespace Mailery\Campaign\Assets;

use Mailery\Web\Assets\VueAssetBundle;
use Yiisoft\Assets\AssetBundle;

class CampaignAssetBundle extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public ?string $basePath = '@public/assets/@maileryio/mailery-campaign-assets';

    /**
     * {@inheritdoc}
     */
    public ?string $baseUrl = '@assetsUrl/@maileryio/mailery-campaign-assets';

    /**
     * {@inheritdoc}
     */
    public ?string $sourcePath = '@npm/@maileryio/mailery-campaign-assets/dist';

    /**
     * {@inheritdoc}
     */
    public array $css = [
        'main.min.css',
    ];

    /**
     * {@inheritdoc}
     */
    public array $js = [
        'main.umd.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public array $depends = [
        VueAssetBundle::class,
        MomentAssetBundle::class,
    ];
}
