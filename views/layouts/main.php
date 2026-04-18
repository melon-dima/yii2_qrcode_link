<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $content */

\app\assets\AppAsset::register($this);
$isHomePage = Yii::$app->controller->route === 'site/index';

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            background-color: #f5f7fa;
        }
        .home-container {
            flex: 1 1 auto;
            display: flex;
            align-items: flex-start;
            padding-top: 24px;
            padding-bottom: 24px;
        }
        .main-form-card {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            padding: 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }
        .result-block {
            margin-top: 30px;
            text-align: center;
        }
        .result-block img {
            max-width: 250px;
            margin-bottom: 15px;
            border: 4px solid #f0f0f0;
            border-radius: 12px;
        }
        .short-link {
            font-size: 1.2rem;
            font-weight: 700;
            word-break: break-all;
        }
        .error-message {
            color: #dc3545;
            font-weight: 700;
            font-size: 1.05rem;
            margin-top: 20px;
        }
        .loading-spinner {
            display: none;
            margin-top: 20px;
        }
        @media (max-width: 767px) {
            .home-container {
                padding-top: 16px;
                padding-bottom: 16px;
            }
            .main-form-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Сервис коротких ссылок',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-lg navbar-dark bg-dark'],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container<?= $isHomePage ? ' home-container' : '' ?>">
        <?= $content ?>
    </div>
</div>

<footer class="footer py-3 bg-light text-center">
    <div class="container">
        <p class="text-muted mb-0">&copy; Сервис коротких ссылок <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
