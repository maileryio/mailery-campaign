<?php

use Yiisoft\Html\Html;
use Mailery\Campaign\Renderer\WrappedUrlGenerator;

/** @var Yiisoft\View\WebView $this */
/** @var WrappedUrlGenerator $wrappedUrlGenerator */

?>
<div class="mb-4"></div>
<div class="row">
    <div class="col-6 offset-3">
        <div class="card mb-3">
            <div class="card-body">
                <h2 class="h5 text-center">You're unsubscribed.</h2>
                <br/>
                <p class="text-center"><?= Html::a(
                    'Re-subscribe?',
                    $wrappedUrlGenerator->getSubscribe(),
                    [
                        'class' => 'btn btn-secondary',
                    ]
                ) ?></p>
            </div>
        </div>
    </div>
</div>
