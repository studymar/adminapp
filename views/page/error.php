<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

                    <div class="auth-content">
                        <p class="text-center">Error</p>
                        <p class="text small">
                            <?= nl2br(Html::encode($message)) ?>
                        </p>
                        <div class="images-container"><!--um js-Fehler Sortable zu verhindern--></div>
                    </div>

