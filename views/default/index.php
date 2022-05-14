<?php declare(strict_types=1);

use Mailery\Activity\Log\Widget\ActivityLogLink;
use Mailery\Icon\Icon;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Subscriber\Entity\Group;
use Mailery\Widget\Link\Link;
use Mailery\Widget\Search\Widget\SearchWidget;
use Yiisoft\Html\Html;
use Yiisoft\Yii\DataView\GridView;

/** @var Yiisoft\Yii\WebView $this */
/** @var Mailery\Widget\Search\Form\SearchForm $searchForm */
/** @var Yiisoft\Aliases\Aliases $aliases */
/** @var Yiisoft\Router\UrlGeneratorInterface $url */
/** @var Yiisoft\Data\Paginator\PaginatorInterface $paginator */
/** @var Mailery\Campaign\Model\CampaignTypeList $campaignTypeList */

$this->setTitle('All campaigns');

?><div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <h4 class="mb-0">All campaigns</h4>
                    </div>
                    <div class="col-auto">
                        <div class="btn-toolbar float-right">
                            <?= SearchWidget::widget()->form($searchForm); ?>
                            <b-dropdown right size="sm" variant="secondary" class="mb-2">
                                <template v-slot:button-content>
                                    <?= Icon::widget()->name('settings'); ?>
                                </template>
                                <?= ActivityLogLink::widget()
                                    ->tag('b-dropdown-item')
                                    ->label('Activity log')
                                    ->group('campaign'); ?>
                            </b-dropdown>
                            <b-dropdown right size="sm" variant="primary" class="mx-sm-1 mb-2">
                                <template v-slot:button-content>
                                    <?= Icon::widget()->name('plus')->options(['class' => 'mr-1']); ?>
                                    Add new campaign
                                </template>
                                <?php foreach ($campaignTypeList as $campaignType) {
                                    echo Html::tag(
                                        'b-dropdown-item',
                                        $campaignType->getCreateLabel(),
                                        [
                                            'href' => $url->generate($campaignType->getCreateRouteName(), $campaignType->getCreateRouteParams()),
                                        ]
                                    );
                                } ?>
                            </b-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <?= GridView::widget()
                    ->layout("{items}\n<div class=\"mb-4\"></div>\n{summary}\n<div class=\"float-right\">{pager}</div>")
                    ->options([
                        'class' => 'table-responsive',
                    ])
                    ->tableOptions([
                        'class' => 'table table-hover',
                    ])
                    ->emptyText('No data')
                    ->emptyTextOptions([
                        'class' => 'text-center text-muted mt-4 mb-4',
                    ])
                    ->paginator($paginator)
                    ->currentPage($paginator->getCurrentPage())
                    ->columns([
                        [
                            'label()' => ['Name'],
                            'value()' => [fn (Campaign $model) => Html::a($model->getName(), $url->generate($model->getViewRouteName(), $model->getViewRouteParams()))],
                        ],
                        [
                            'label()' => ['Type'],
                            'value()' => [static function (Campaign $data) use ($campaignTypeList) {
                                $campaignType = $campaignTypeList->findByEntity($data);
                                return $campaignType ? $campaignType->getLabel() : null;
                            }],
                        ],
                        [
                            'label()' => ['Sender'],
                            'value()' => [static function (Campaign $data) use ($url) {
                                return Html::a(
                                    $data->getSender()->getName(),
                                    $url->generate($data->getSender()->getViewRouteName(), $data->getSender()->getViewRouteParams())
                                );
                            }],
                        ],
                        [
                            'label()' => ['Template'],
                            'value()' => [static function (Campaign $data) use ($url) {
                                return Html::a(
                                    $data->getTemplate()->getName(),
                                    $url->generate($data->getTemplate()->getViewRouteName(), $data->getTemplate()->getViewRouteParams())
                                );
                            }],
                        ],
                        [
                            'label()' => ['Groups'],
                            'value()' => [static function (Campaign $data) use ($url) {
                                return implode(
                                    '<br />',
                                    array_map(
                                        function (Group $group) use($url) {
                                            return Html::a(
                                                $group->getName(),
                                                $url->generate($group->getViewRouteName(), $group->getViewRouteParams())
                                            );
                                        },
                                        $data->getGroups()->toArray()
                                    )
                                );
                            }],
                        ],
                        [
                            'label()' => ['Edit'],
                            'value()' => [static function (Campaign $data) use ($url) {
                                return Html::a(
                                    Icon::widget()->name('pencil')->render(),
                                    $url->generate($data->getEditRouteName(), $data->getEditRouteParams()),
                                    [
                                        'class' => 'text-decoration-none mr-3',
                                    ]
                                )
                                ->encode(false);
                            }],
                        ],
                        [
                            'label()' => ['Delete'],
                            'value()' => [static function (Campaign $data) use ($csrf, $url) {
                                return Link::widget()
                                    ->csrf($csrf)
                                    ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render())
                                    ->method('delete')
                                    ->href($url->generate($data->getDeleteRouteName(), $data->getDeleteRouteParams()))
                                    ->confirm('Are you sure?')
                                    ->options([
                                        'class' => 'text-decoration-none text-danger',
                                    ])
                                    ->encode(false);
                            }],
                        ],
                    ]);
                ?>
            </div>
        </div>
    </div>
</div>
