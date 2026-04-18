<?php

/** @var yii\web\View $this */
/** @var app\models\LinkForm $model */

use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Короткие ссылки + QR';

$createUrl = Url::to(['/site/create']);
$createUrlJs = Json::htmlEncode($createUrl);
$csrfJs = Json::htmlEncode(Yii::$app->request->csrfToken);

$js = <<<JS
$(function () {
    function resetMessages() {
        $('#error-block').hide().text('');
        $('#result-block').hide();
    }

    function shortenUrl() {
        const url = $('#url-input').val().trim();
        resetMessages();

        if (!url) {
            $('#error-block').text('Введите URL').show();
            return;
        }

        $('#loading').show();
        $('#btn-shorten').prop('disabled', true);

        $.ajax({
            url: {$createUrlJs},
            type: 'POST',
            dataType: 'json',
            data: {
                url: url,
                _csrf: {$csrfJs}
            },
            success: function (response) {
                $('#loading').hide();
                $('#btn-shorten').prop('disabled', false);

                if (response.success) {
                    $('#qr-image').attr('src', response.qr_image);
                    $('#short-url').attr('href', response.short_url).text(response.short_url);
                    $('#stats-link').attr('href', response.stats_url);
                    $('#result-block').fadeIn();
                    return;
                }

                $('#error-block').text(response.message || 'Неизвестная ошибка').show();
            },
            error: function () {
                $('#loading').hide();
                $('#btn-shorten').prop('disabled', false);
                $('#error-block').text('Ошибка сервера. Попробуйте позже.').show();
            }
        });
    }

    $('#btn-shorten').on('click', shortenUrl);
    $('#url-input').on('keypress', function (e) {
        if (e.which === 13) {
            shortenUrl();
        }
    });

    $('#btn-copy').on('click', function () {
        const shortUrl = $('#short-url').text();
        if (!shortUrl) {
            return;
        }

        navigator.clipboard.writeText(shortUrl).then(function () {
            $('#btn-copy').text('Скопировано!');
            setTimeout(function () {
                $('#btn-copy').text('Скопировать ссылку');
            }, 1500);
        });
    });
});
JS;

$this->registerJs($js);
?>

<div class="main-form-card">
    <h2 class="text-center mb-4">Создать короткую ссылку</h2>
    <p class="text-center text-muted mb-4">Вставьте длинный URL и получите короткую ссылку с QR-кодом.</p>

    <div class="input-group input-group-lg">
        <input type="text" id="url-input" class="form-control" placeholder="https://example.com/very/long/url..." aria-label="Ссылка">
        <button class="btn btn-primary" type="button" id="btn-shorten">Сократить</button>
    </div>

    <div class="text-center loading-spinner" id="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
        <p class="text-muted mt-2">Проверяем URL и генерируем QR-код...</p>
    </div>

    <div class="error-message text-center" id="error-block" style="display:none;"></div>

    <div class="result-block" id="result-block" style="display:none;">
        <hr>
        <h4 class="mb-3">Ссылка создана</h4>
        <div>
            <img id="qr-image" src="" alt="QR-код">
        </div>
        <div class="short-link">
            <a id="short-url" href="#" target="_blank" rel="noopener noreferrer"></a>
        </div>
        <div class="mt-2">
            <button class="btn btn-outline-secondary btn-sm" id="btn-copy">Скопировать ссылку</button>
        </div>
        <div class="mt-3">
            <a id="stats-link" href="#" class="text-muted">Открыть статистику</a>
        </div>
    </div>
</div>
