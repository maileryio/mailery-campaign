<?php

declare(strict_types=1);

/**
 * Campaign module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-campaign
 * @package   Mailery\Campaign
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Campaign\Mapper;

use Mailery\Activity\Log\Mapper\LoggableMapper;
use Mailery\Campaign\Module;

/**
 * @Cycle\Annotated\Annotation\Table(
 *      columns = {
 *          "created_at": @Cycle\Annotated\Annotation\Column(type = "datetime"),
 *          "updated_at": @Cycle\Annotated\Annotation\Column(type = "datetime"),
 *          "_type": @Cycle\Annotated\Annotation\Column(type = "string(255)")
 *      }
 * )
 */
final class DefaultMapper extends LoggableMapper
{
    /**
     * {@inheritdoc}
     */
    protected function getModule(): string
    {
        return Module::NAME;
    }
}
