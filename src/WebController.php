<?php

declare(strict_types=1);

/**
 * Campaign module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-campaign
 * @package   Mailery\Campaign
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Campaign;

use Cycle\ORM\ORMInterface;
use Mailery\Brand\Service\BrandLocator;
use Mailery\Common\Web\Controller;
use Mailery\Campaign\Assets\CampaignAssetBundle;
use Mailery\Web\Assets\AppAssetBundle;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\View\WebView;

abstract class WebController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        AssetManager $assetManager,
        BrandLocator $brandLocator,
        ResponseFactoryInterface $responseFactory,
        Aliases $aliases,
        WebView $view,
        ORMInterface $orm
    ) {
//        $bundle = $assetManager->getBundle(AppAssetBundle::class);
//        $bundle->depends[] = CampaignAssetBundle::class;

        parent::__construct($brandLocator, $responseFactory, $aliases, $view, $orm);
    }
}
