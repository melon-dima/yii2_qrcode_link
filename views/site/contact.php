<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Спасибо за обращение. Мы ответим вам как можно скорее.
        </div>

        <p>
            Обратите внимание: если включён отладчик Yii, письмо можно
            посмотреть в почтовой панели отладчика.
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Так как приложение работает в режиме разработки, письмо не отправляется,
                а сохраняется в файл по пути <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                Установите <code>useFileTransport = false</code> в компоненте <code>mail</code>,
                чтобы включить реальную отправку писем.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <p>
            Если у вас есть вопросы или предложения, заполните форму ниже.
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'subject') ?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
