<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>

<div class="main-form-card text-center">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger mt-3">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-primary mt-3">На главную</a>
</div>
