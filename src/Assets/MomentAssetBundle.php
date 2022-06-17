<?php

declare(strict_types=1);

namespace Mailery\Campaign\Assets;

use Yiisoft\Assets\AssetBundle;

class MomentAssetBundle extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public ?string $basePath = '@public/assets';

    /**
     * {@inheritdoc}
     */
    public ?string $baseUrl = '@assetsUrl';

    /**
     * {@inheritdoc}
     */
    public ?string $sourcePath = '@npm/moment/dist';

    /**
     * {@inheritdoc}
     */
    public array $js = [
        'moment.js',
    ];
}
