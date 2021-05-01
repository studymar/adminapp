<?php
    use app\models\user\User;
    use app\models\role\Right;
    $this->params['relatedButtons'] = [];
?>

                        <?= $this->render('_relatedButtons'); //Edit-Modus-Buttons ?>

                        <div>
                            <h2 class="title-block"><?= $page->headline ?></h2>
                        </div>

                        <div class="content-block">

                            <?php foreach($pageHasTemplates as $pageHasTemplate): //jedes template der Seite ausführen ?>
                            <?php // echo $pageHasTemplate->template->type ?>
                            <?= Yii::$app->runAction(app\models\helpers\StringConverter::camelCase2Hyphen($pageHasTemplate->template->controllername).'/index',['p'=>$pageHasTemplate->id]) ?>
                            <?php endforeach; ?>
                                
                        </div>

