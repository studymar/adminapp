<?php

/* 
 * Zeigt des eingeloggten User
 * @param Object $model
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php if(!Yii::$app->user->isGuest){ ?>

                        <li class="profile dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="name"> <?= Yii::$app->user->identity->getName() ?> </span>
                            </a>
                            <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                <a class="dropdown-item" href="<?= Url::toRoute(['account/my-account']) ?>">
                                    <i class="fa fa-user icon"></i> Mein Account </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= Url::toRoute(['account/logout']) ?>">
                                    <i class="fa fa-power-off icon"></i> Logout </a>
                            </div>
                        </li>
<?php } ?>


