<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\LayoutAsset;
use yii\helpers\Url;

LayoutAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html class="no-js" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="main-wrapper">
        <div class="app" id="app">
            <header class="header">
                <div class="header-block header-block-collapse d-lg-none d-xl-none">
                    <button class="collapse-btn" id="sidebar-collapse-btn">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
                
                <div class="header-block header-block-name">
                    <img src="/content/images/placeholder/kreiskarteTT.gif" />
                    <h1>TTKV Harburg-Land e.V.</h1>
                </div>
                <div class="header-block header-block-nav">


                    <ul class="nav-profile">
                        <li class="notifications new">
                        </li>
                        <?php if(!Yii::$app->user->isGuest){ ?>
                        <?= Yii::$app->runAction('account/show-user', []); ?>
                        <?php } ?>
                    </ul>
                </div>
            </header>
    
            <?php // Yii::$app->runAction('menu/index', []); ?>
            <!-- Menu Begin -->
            <?= (isset($this->params['pagebar']) && $this->params['pagebar'])? $this->render('//page/_pagebar',['page'=>$this->params['page']]):'' ?>
            <aside class="sidebar">
                <div class="sidebar-container">
                    <div class="sidebar-header">
                        <div class="brand">
                            <div class="logo">
                                <span class="l l1"></span>
                                <span class="l l2"></span>
                                <span class="l l3"></span>
                                <span class="l l4"></span>
                                <span class="l l5"></span>
                            </div> TTKV Loginbereich
                        </div>
                    </div>
                    <nav class="menu">
                        <ul class="sidebar-menu metismenu" id="sidebar-menu">
                            <li class="active">
                                <a href="index.html">
                                    <i class="fa fa-home"></i> Dashboard </a>
                            </li>
                            <?php /*
                            <li class="<?= (isset($this->params['page']))?'open':''?>">
                                <a href="">
                                    <i class="fa fa-th-large"></i> Content Manager <i class="fa arrow"></i>
                                </a>
                                -->
                                <!--
                                <ul class="sidebar-nav">
                                    <li>
                                        <a href="<?= Url::toRoute(['menu/navigationmanager']) ?>"> Hauptmenü </a>
                                    </li>
                                    <?= Yii::$app->runAction('menu/index', []); ?>
                                    <!--
                                    <li>
                                        <a href="items-list.html"> Home </a>
                                    </li>
                                    <li>
                                        <a href="item-editor.html"> Click-tt </a>
                                    </li>
                                    <li>
                                        <a href="item-editor.html"> Pokal </a>
                                    </li>
                                    <li>
                                        <a href="item-editor.html"> KM / RL </a>
                                    </li>
                                    <li>
                                        <a href="item-editor.html"> Anschriften </a>
                                    </li>
                                    <li>
                                        <a href="item-editor.html"> Termine </a>
                                    </li>
                                    <li>
                                        <a href="item-editor.html"> Downloads </a>
                                    </li>
                                    <li>
                                        <a href="item-editor.html"> Links </a>
                                    </li>
                                    -->
                                </ul>
                            </li>
                             * 
                             */
                            ?>
                            <li>
                                <a href="">
                                    <i class="fa fa-user"></i> Userverwaltung <i class="fa arrow"></i>
                                </a>
                                <ul class="sidebar-nav">
                                    <li>
                                        <a href="<?= Url::toRoute(['usermanager/index']) ?>">Usermanager </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::toRoute(['rolemanager/index']) ?>"> Rollenmanager</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="<?= (Yii::$app->controller->id == 'voting')?'open':''?>">
                                <a href="">
                                    <i class="fa fa-user"></i> Voting/Umfragen <i class="fa arrow"></i>
                                </a>
                                <ul class="sidebar-nav">
                                    <li class="active">
                                        <a href="<?= Url::toRoute(['voting/edit']) ?>">Voting </a>
                                    </li>
                                </ul>
                            </li>
                            
                        </ul>
                        
                    </nav>
                </div>
                <footer class="sidebar-footer">
                </footer>
                </aside>
            
            
            
                <div class="sidebar-overlay" id="sidebar-overlay"></div>
                <div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
                <div class="mobile-menu-handle"></div>
                
                <article class="content dashboard-page <?= (isset($this->params ['contentCSS']))?$this->params ['contentCSS'] : '' ?>">
                        <?= $content ?>
                </article>

                <footer class="footer">
                    <div class="footer-block buttons">
                    </div>
                    <div class="footer-block author">
                        <ul>
                            <li> <a href="<?= Url::toRoute(['content/impressum']) ?>">Impressum</a>
                            </li>
                            <li>
                                <a href="<?= Url::toRoute(['content/datenschutz']) ?>">Datenschutz</a>
                            </li>
                        </ul>
                    </div>
                </footer>
                <div class="modal fade" id="modal-media">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Media Library</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                            </div>
                            <div class="modal-body modal-tab-container">
                                <ul class="nav nav-tabs modal-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#gallery" data-toggle="tab" role="tab">Gallery</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#upload" data-toggle="tab" role="tab">Upload</a>
                                    </li>
                                </ul>
                                <div class="tab-content modal-tab-content">
                                    <div class="tab-pane fade" id="gallery" role="tabpanel">
                                        <div class="images-container">
                                            <div class="row">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade active in" id="upload" role="tabpanel">
                                        <div class="upload-container">
                                            <div id="dropzone">
                                                <form action="/" method="POST" enctype="multipart/form-data" class="dropzone needsclick dz-clickable" id="demo-upload">
                                                    <div class="dz-message-block">
                                                        <div class="dz-message needsclick"> Drop files here or click to upload. </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Insert Selected</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <div class="modal fade" id="confirm-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><i class="fa fa-warning"></i> Alert</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Sind Sie sicher, dass Sie <span class="deletename">dieses Element</span> löschen wollen?</p>
                            </div>
                            <div class="modal-footer">
                                <a href="##" class="btn btn-primary" id="modal-confirmed-button" role="button">Löschen</a>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
        </div>
        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>
            
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
