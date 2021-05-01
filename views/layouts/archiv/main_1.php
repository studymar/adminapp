<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\LayoutAsset;

LayoutAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
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

    <div>
      <div id="bar"></div>
      <div id="top">
      <div id="wrapper">

        <!-- Header -->
        <div id="header">
        <h1><a href="/">TTKV Harburg-Land e.V.</a></h1>
        <p class="title">Tischtennis in der südlichen Metropolregion von Hamburg</p>
        </div> <!-- Header end -->

        <!-- Menu -->
        <div id="menu-box" class="cleaning-box">
          <?= Yii::$app->runAction('menu/index', []); ?>
        </div> <!-- Menu end -->

        <hr class="noscreen" />
        <div id="skip-menu"></div>

        <div id="content"> 

          <div id="column-1">
            <div class="content">
              <?= $content ?>
            </div> <!-- content end -->
          </div> <!-- Column 1 end -->

          <div id="column-2">
            <?php if (isset($this->blocks['relatedButtons'])): ?>
              <span class="related-buttons">
                 <?= $this->blocks['relatedButtons'] ?>              
              </span>
            <?php endif; ?>
              
            <div id="column-21"> 
              <div class="content">
                <h4>News</h4>
                  <dl>
                    <dt>22. 8. 2008</dt>
                    <dd><a href="#">Justo Lorem vitae porttitor Nullam Pellentesque quis&hellip;</a></dd>

                    <dt>22. 8. 2008</dt>
                    <dd><a href="#">Justo Lorem vitae porttitor Nullam Pellentesque quis&hellip;</a></dd>

                    <dt>22. 8. 2008</dt>
                    <dd><a href="#">Justo Lorem vitae porttitor Nullam Pellentesque quis&hellip;</a></dd>
                  </dl>

              </div>
            </div> <!-- Column 21 end -->

            <div id="column-22">
              <div class="content">
                <h4>Advertisment</h4>
                <h4 class="nobg">Categories</h4> 
                <ul class="r-list">
                  <li><a href="#">Elit vel</a></li>
                  <li><a class="active" href="#">Justo lorem</a></li>
                  <li><a href="#">Vestibulum sed</a></li>
                  <li><a href="#">Proin ipsum</a></li>
                  <li><a href="#">Laoreet phasellus</a></li>
                </ul>

                <h4 class="nobg">Subjects</h4> 
                <ul class="r-list">
                  <li><a href="#">Elit vel</a></li>
                  <li><a href="#">Justo lorem</a></li>
                  <li><a href="#">Vestibulum sed</a></li>
                  <li><a href="#">Proin ipsum</a></li>
                  <li><a href="#">Laoreet phasellus</a></li>
                </ul>
              </div>
            </div> <!-- Column 22 end -->

          </div> <!-- Column 2 end -->
          <div class="cleaner">&nbsp;</div>
        </div>  <!-- Content of the site end -->


        <div id="footer">
          <div id="footer-in">
            <ul>
              <li><a href="#" class=" first active">Home</a> |</li>
              <li><a href="#">About us</a> |</li>
              <li><a href="#">Testimonials</a> |</li>
              <li><a href="#">We support</a> |</li>
              <li><a href="#">Contact</a></li>
            </ul>

            <p class="print"><a id="print" href="#">Print</a> | <a href="#top">Top</a> &uarr;</p>
            <div class="cleaner">&nbsp;</div>
            <p id="backs"><a href="http://www.mantisatemplates.com/">Mantis-a templates</a> | tip <a href="http://www.topas-tachlovice.cz/topas-tachlovice.aspx" title="Občanské sdružení TOPAS Tachlovice">Tachlovice</a></p>
          </div>
        </div> <!-- Footer end -->

      </div> <!-- Wrapper end -->
      </div> <!-- top -->
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
