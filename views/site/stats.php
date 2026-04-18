<?php

/** @var yii\web\View $this */
/** @var app\models\Link $link */
/** @var app\models\LinkLog[] $logs */
/** @var string $qrBase64 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Статистика: ' . $link->code;
?>

<div class="main-form-card">
    <h2 class="text-center mb-4">Статистика ссылки</h2>

    <div class="text-center mb-4">
        <img src="<?= $qrBase64 ?>" alt="QR-код" style="max-width: 200px; border-radius: 12px; border: 3px solid #eee;">
    </div>

    <table class="table table-bordered">
        <tr>
            <th>Короткая ссылка</th>
            <td>
                <a href="<?= Html::encode($link->getShortUrl()) ?>" target="_blank" rel="noopener noreferrer">
                    <?= Html::encode($link->getShortUrl()) ?>
                </a>
            </td>
        </tr>
        <tr>
            <th>Оригинальная ссылка</th>
            <td>
                <a href="<?= Html::encode($link->original_url) ?>" target="_blank" rel="noopener noreferrer" style="word-break: break-all;">
                    <?= Html::encode($link->original_url) ?>
                </a>
            </td>
        </tr>
        <tr>
            <th>Создана</th>
            <td><?= Html::encode($link->created_at) ?></td>
        </tr>
        <tr>
            <th>Переходы</th>
            <td><span class="badge bg-primary fs-6"><?= (int) $link->clicks ?></span></td>
        </tr>
    </table>

    <?php if (!empty($logs)): ?>
        <h4 class="mt-4">Журнал переходов</h4>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>IP</th>
                        <th>User Agent</th>
                        <th>Дата/время</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $i => $log): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= Html::encode($log->ip) ?></td>
                            <td style="max-width: 320px; word-break: break-all; font-size: 0.85rem;">
                                <?= Html::encode($log->user_agent) ?>
                            </td>
                            <td><?= Html::encode($log->visited_at) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center mt-3">Переходов пока нет.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-outline-primary">На главную</a>
    </div>
</div>
