<?php

use app\models\user\User;
use app\models\role\Right;
use yii\helpers\Url;
use app\models\content\Page;

/* 
 * Pagebar, die beim editieren der Seite angezeigt wird
 */
?>

                            <aside class="pagebar">
                                <div class="pagebar-container">
                                    <div class="sidebar-header d-flex justify-content-between">
                                        <div class="brand">
                                            <div class="pagebar-logo">
                                                <i class="material-icons">settings</i>
                                            </div> Page Edit
                                        </div>
                                        <div></div>
                                        <div id="pagebar-collapse-btn">
                                            <a href="" class=""><i class="fa fa-chevron-right btn-close"></i></a>
                                        </div>
                                    </div>
                                    <nav class="menu">
                                        <ul class="pagebar-menu metismenu" id="pagebar-menu">
                                            <?php if(User::checkRight(Right::PAGE_CONFIG)){ ?>
                                            <li class="">
                                                <a href="<?= Url::toRoute(['page/config','p'=>$page->urlname]) ?>">
                                                    <i class="fa fa-cog"></i> Seite konfigurieren 
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php if(User::checkRight(Right::PAGE_ADD_CONTENT)){ ?>
                                            <li class="">
                                                <a href="<?= Url::toRoute(['page/edit-form','p'=>$page->urlname]) ?>">
                                                    <i class="fa fa-edit"></i> Inhalte bearbeiten
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php if(User::checkRight(Right::PAGE_ADD_CONTENT)){ ?>
                                            <li class="">
                                                <a href="index.html" data-toggle="collapse" data-target="#page-templatechooser">
                                                    <i class="fa fa-bars"></i> Inhalt zur Seite hinzufügen
                                                </a>
                                                <ul class="page-templatechooser collapse" id="page-templatechooser">
                                                    <li>
                                                        <?php foreach(Page::getAddableTemplates() as $template){ ?>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p class="templatename"><a href="<?= Url::toRoute(['page/add-template','p'=>$page->urlname,'p2'=>$template->id]) ?>"><?= $template->type ?></a></p>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </li>
                                                </ul>
                                            </li>
                                            <?php } ?>
                                            <?php if(User::checkRight(Right::PAGE_ADD_CONTENT)){ ?>
                                            <li class="">
                                                <a href="<?= Url::toRoute(['page/sort-templates','p'=>$page->urlname]) ?>">
                                                    <i class="fa fa-sort-amount-asc"></i> Inhalte sortieren
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php if(User::checkRight(Right::PAGE_ADD_CONTENT)){ ?>
                                            <li class="">
                                                <a href="<?= Url::toRoute(['page/remove-template-form','p'=>$page->urlname]) ?>">
                                                    <i class="fa fa-times"></i> Inhalte löschen
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php if(User::checkRight(Right::PAGE_ADD_CONTENT) && $page->pagetype_id != 1){ ?>
                                            <li class="">
                                                <a href="<?= Url::toRoute(['page/add-sub-page','p'=>$page->urlname]) ?>">
                                                    <i class="fa fa-cog"></i> Unterseite erstellen
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php if(User::checkRight(Right::PAGE_DELETE) && $page->pagetype_id != 1){ ?>
                                            <li class="">
                                                <a href="#" data-toggle="collapse" data-target=".page-delete-confirmation">
                                                    <i class="material-icons">delete</i> Seite löschen
                                                </a>
                                                <ul class="page-delete-confirmation collapse">
                                                    <li class="shortdescription">
                                                        <p class="description">
                                                            "<?= $page->headline ?>"
                                                            <br/>
                                                            <span class="font-italic">Sind Sie sicher?</span>
                                                        </p>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p class="templatename"><a href="<?= Url::toRoute(['page/delete','p'=>$page->urlname]) ?>">Ja</a></p>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p class="templatename"><a href="#" data-toggle="collapse" data-target=".page-delete-confirmation">Abbrechen</a></p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </nav>
                                </div>
                            </aside>

