<?php

/* 
 * Zeile für den Rolemanager
 */

use yii\helpers\Url;
?>

                    <tr>
                      <td><?= $model->name ?></td>
                      <td><?= $model->getCountUsers() ?></td>
                      <td>
                            <div class="d-flex">
                                <div class="mr-2">
                                    <a class="remove <?= ($model->getCountUsers() > 0)?'invisible':'' ?>" href="<?= Url::toRoute(['rolemanager/delete','p'=>$model->id]) ?>" data-toggle="modal" data-target="#confirm-modal" data-name="<?= $model->name ?>" >
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                                <div class="mr-2">
                                    <a class="edit" href="<?= Url::toRoute(['rolemanager/update','p'=>$model->id]) ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </div>
                      </td>
                    </tr>


