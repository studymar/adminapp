<?php
use yii\helpers\Url;

?>

                    <div class="auth-content">
                        <p class="text-center">Passwort vergessen versendet</p>
                        <p class="text small">
                            An Ihre im Account hinterlegte Emailadresse wurde grade eine Email versendet, 
                            die Sie in Kürze erreichen sollte. Um Ihre Passwort zu ändern
                            und Ihren Account wieder nutzen zu können, folgen Sie bitte
                            den Anweisungen in dieser Email an.   
                        </p>
                        <div class="images-container"><!--um js-Fehler Sortable zu verhindern--></div>
                    </div>
                    <div class="text-center">
                        <a href="<?= Url::toRoute(['account/index']) ?>" class="btn btn-secondary btn-sm">
                            Weiter zum Login <i class="fa fa-arrow-right"></i></a>
                    </div>



