<?php
    use yii\helpers\Url;
    use app\models\user\User;
    use app\models\role\Right;
    use app\models\helpers\FormHelper;
    use yii\bootstrap4\ActiveForm;
    $this->params['relatedButtons'] = [];
    
    /**
     * $page
     * $pageHasTemplates
     */
    
?>

                        <section class="form">
                            <div class="d-flex justify-content-between title-block">
                                <h2><?= $page->headline ?> - Inhalte löschen</h2>
                            </div>
                            <div class="content-block">
                                <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                                    <div class="section-headline">
                                        Bitte wählen Sie aus, welches Element gelöscht werden soll:
                                    </div>

                                    <?php foreach($pageHasTemplates as $item){ ?>
                                    <div class="card card-selection">
                                        <div class="card-body">
                                            <div class="">
                                                <label>
                                                    <input class="checkbox" type="radio" name="RemoveTemplateForm[page_has_template_id]" value="<?= $item->id ?>" id="">
                                                    <span><?= $item->template->type ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <button type="submit" class="btn btn-primary">Löschen</button>
                                    <a href="<?= Url::toRoute(['page/edit','p'=>$page->urlname]) ?>" class="btn btn-outline-primary">Abbrechen</a>
                                <?php ActiveForm::end() ?>
                            </div>
                            
                            
                            
                        </section>

