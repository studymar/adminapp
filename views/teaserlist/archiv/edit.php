<?php
use yii\helpers\Url;
use app\models\content\Teaser;
/* 
 * Teaserlist (mit Edit-Line)
 * Zeigt den teaser im Edit-Modus an (mit Buttons zum ändern)
 */

?>
                        <?php $this->params['relatedButtons']['Teaser/News hinzufügen'] = ['teaserlist/create-item','p'=>$model->id] ?>


                            <div class="">
                                <h3><?= $model->headline ?></h3>

                                <div class="edit-list-line align-right">
                                    <a href="" class="btn btn-outline-light fa fa-sort-amount-desc"></a>
                                    <a href="" class="btn btn-outline-light material-icons">add</a>
                                </div>
                                <!--
                                <?= $this->render('@app/views/partials/_listeditline',[
                                    'releasestatus' => $model->pageHasTemplate->release->getReleasestatusCSSClass(),
                                    'buttons'   => [
                                        [
                                            'name'    => 'Edit',
                                            'url'     => Url::toRoute(['teaserlist/edit-list','p'=>$model->id]),
                                            'icon'    => 'edit',
                                        ],
                                        [
                                            'name'    => 'Teaser hinzufügen',
                                            'url'     => Url::toRoute(['teaserlist/create-item','p'=>$model->id]),
                                            'icon'    => 'playlist_add',
                                        ],
                                        [
                                            'name'    => 'Löschen',
                                            'url'     => Url::toRoute(['teaserlist/delete','p'=>$model->id]),
                                            'class'   => 'btn-danger',
                                            'confirm' => true,
                                            'icon'    => 'delete',
                                        ]
                                    ]
                                ]); //List-Edit-Buttons ?>
                                -->

                               <?php foreach($items as $item): ?>
                                <!--
                                <?= $this->render('@app/views/partials/_editline',[
                                    'releasestatus' => $item->release->getReleasestatusCSSClass(),
                                    'buttons'   => [
                                        [
                                            'name'    => 'Edit',
                                            'url'     => Url::toRoute(['teaserlist/edit-item','p'=>$item->id]),
                                            'icon'    => 'edit',
                                        ],
                                        [
                                            'name'    => 'Hoch sortieren',
                                            'url'     => ($item->sort<Teaser::getMaxSort($item->teaserlist_id))?Url::toRoute(['teaserlist/sort-up','p'=>$item->id]):'',
                                            'icon'    => 'arrow_upward',
                                        ],
                                        [
                                            'name'    => 'Löschen',
                                            'url'     => Url::toRoute(['teaserlist/delete-item','p'=>$item->id]),
                                            'class'   => 'btn-danger',
                                            'confirm' => true,
                                            'icon'    => 'delete',
                                        ]
                                    ]
                                ]); //List-Edit-Buttons ?>
                                -->
                                <?= $this->render('@app/views/teaserlist/_teaser',['item'=>$item]); //Edit-Modus-Buttons ?>
                                
                                <?php endforeach; ?>
                            </div>

