<?php
use yii\helpers\Url;
/* 
 * Nachdem der Registrierungslink geklickt wurde
 */
?>

                    <div class="auth-content">
                        <p class="text-center">Registrierung abgeschlossen</p>
                        <p class="text small">
                            Ihre Registrierung ist abgeschlossen. Sie können sich jetzt einloggen.  
                        </p>
                        <div class="images-container"><!--um js-Fehler Sortable zu verhindern--></div>
                    </div>
                    <div class="text-center">
                        <a href="<?= Url::toRoute(['account/index']) ?>" class="btn btn-secondary btn-sm">
                            Weiter zum Login <i class="fa fa-arrow-right"></i></a>
                    </div>



