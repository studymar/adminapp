<?php
    use yii\helpers\Url;
    use app\models\user\User;
    use app\models\role\Right;
    $this->params['relatedButtons'] = [];
    
    use app\assets\PagebarAsset;
    PagebarAsset::register($this);
    
    //Pagemenü-HTML, wenn Recht dazu vorhanden auf Seite einbauen (wird in main-Layout includiert)
    if(isset($page) && User::hasRightToOpenPageMenu($page)){
        $this->params['pagebar'] = true;
    }
    
?>


                        <div class="page-edit">
                            <div class="d-flex justify-content-between title-block">
                                <h2><?= $page->headline ?></h2>
                                <div></div>
                                <?php if(User::hasRightToOpenPageMenu($page) ){ ?>
                                <div id="pagebar-open-btn">
                                    <a class="btn single btn-light"><i class="material-icons">settings</i></a>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="content-block">
                                <div class="template-headline">
                                    <a href="<?= Url::toRoute(['page/edit-form','p'=>$page->urlname]) ?>" class="btn btn-primary">Inhalte bearbeiten</a>
                                </div>
                                <?php foreach($pageHasTemplates as $pageHasTemplate): //jedes template der Seite ausführen ?>
                                <?php //echo $pageHasTemplate->template->type ?>
                                <?= Yii::$app->runAction(app\models\helpers\StringConverter::camelCase2Hyphen($pageHasTemplate->template->controllername).'/edit',['p'=>$pageHasTemplate->id]) ?>
                                <?php endforeach; ?>
                            </div>

                        </div>

