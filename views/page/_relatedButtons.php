<?php
    use app\models\user\User;
    use app\models\role\Right;
    use yii\helpers\Url;
    
    $page           = $this->params['page'];
    $addedButtons   = $this->params['relatedButtons'];
/* 
 * Zeigt die Buttons zum Bearbeiten der Seite in der RelatedSpalte
 */
?>
                <?php $this->beginBlock('relatedButtons'); 
                    if(!Yii::$app->user->isGuest && User::getLoggedInUser()->checkRight(Right::PAGE_EDIT)):
                        if(!$page->isEditModus): ?>

                        <a href="<?= Url::toRoute(['page/edit','p'=>$page->urlname]) ?>" id="edit-modus" class="btn btn-secondary">Zum Edit-Modus wechseln</a>
                    
                        <?php else: ?>

                        <a href="<?= ($page->urlname)?$page->urlname:'/' ?>" id="finish-edit-modus" class="btn btn-secondary">Edit-Modus beenden</a>
                    
                        <?php endif;
                    endif;
                    
                    foreach ($addedButtons as $label=>$url):  ?>
                        <a href="<?= (is_array($url))?Url::toRoute($url):$url ?>" class="btn btn-secondary"><?= $label ?></a>
                    <?php endforeach;
                $this->endBlock(); ?>



