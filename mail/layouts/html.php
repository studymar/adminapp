<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets;

$bundle = assets\LayoutAsset::register($this);
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body style="">
        <style type="text/css">
            @media only screen and (max-width:599px) {
            }
        /** from style.css BEGIN **/
            * { margin: 0; padding: 0; }

            html { height: 100%; font-size: 62.5% }

            body {
            height: 100%;
            background: #FFFFFF url('@themes/mail/assets/img/layout/hd-bg.gif') repeat-x 0 0;
            text-align: center;
            font: 1.2em Verdana, Arial, Helvetica, sans-serif;
            min-width: 960px;
            }

            a:link, a:visited {
            color: #005890;
            text-decoration: underline;
            font-weight: bolder;
            }

            a:hover {
            text-decoration: none;
            }

            address {
            line-height: 2;
            text-align: left;
            margin: 0.3em 1em;
            }

            .noscreen {
            display: none;
            }


            /* Cleaner */

            .cleaner {
            clear: both;
            height: 0;
            font-size: 0;
            visibility: hidden;
            }


            /* Skip menu */

            .hidden {
            position: absolute;
            top: -10000px;
            left: 0;
            width: 1px;
            height: 1px;
            overflow: hidden;
            }

            .cleaning-box { 
            min-height: 1px;
            }

            .cleaning-box:after {
            display: block; 
            clear: both; 
            visibility: hidden; 
            height: 0; 
            font-size: 0; 
            content: ' ';
            }


            /* Images */



            /* Wrapper */

            #wrapper {
            margin: 0 auto;
            }


            /* Headings, paragraphs */

            h1 {
            font: 2.7em Georgia, "Times New Roman", Times, serif;
            color: #FFFFFF;
            padding: 20px 0 4px 0;
            margin: 0 0 0 4px;
            border-bottom: 1px solid #6A9E4D;
            }

            h1 a:link, h1 a:visited {
            text-decoration: none;
            color: #FFFFFF;
            font-weight: normal;
            }

            h2 {
            font-size: 1.9em;
            font-weight: normal;
            color: #538700;
            margin-bottom: 3px;
            }

            h3.sub {
            font: 1.4em Georgia, "Times New Roman", Times, serif;
            color: #3A3A3A;
            margin-bottom: 10px;
            background: none;
            padding: 0;
            }

            h3 {
            font: 1.4em Georgia, "Times New Roman", Times, serif;
            color: #3A3A3A;
            margin-bottom: 10px;
            background-color: #E3F5FF;
            padding: 3px 0 3px 7px;
            }

            h4 {
            font: bold 1.1em Arial, Helvetica, sans-serif;
            letter-spacing: 1px;
            margin-bottom: 5px;
            background-color: #006BA3;
            color: #FFFFFF;
            padding: 6px 0 6px 10px;
            }

            h4.nobg { 
            background: none;
            padding: 0;
            color: #000000;
            }

            p {
            line-height: 1.8;
            color: #252525;
            margin-bottom: 10px;
            }


            /* Header */

            #header {
            width: 960px;
            height: 149px;
            margin: 0 auto;
            position: relative;
            text-align: left;
            background: #327000 url('@themes/mail/img/layout/hd-bg.gif') repeat-x 0 0;
            }

            #header p.title {
            color: white;
            line-height: 1.8;
            position: relative;
            z-index: 2;
            padding: 5px 0 0 0;
            margin: 0 0 0 5px;
            font: 1.3em Georgia, "Times New Roman", Times, serif;
            color: #FFFFFF;
            letter-spacing: 1px;
            }


            /* Menu */

            #menu-box {
            background-color: #EEEEEE;
            border-bottom: 1px solid #B0BCC3;
            }

            #menu {
            width: 960px;
            margin: 0 auto;
            list-style: none;
            text-align: left;
            font: 1.2em Georgia, "Times New Roman", Times, serif;
            letter-spacing: 1px;
            }

            #menu li {
            display: inline;
            text-align: center;
            line-height: 34px;
            }

            #menu li a {
            float: left;
            display: block;
            color: #474747;
            font-weight: normal;
            padding: 0 18px;
            border-right: 1px solid #B0BCC3;
            text-decoration: none;
            }

            #menu li a.first {
            border-left: 1px solid #B0BCC3;
            }

            #menu li a:hover {
            color: #006BA3;
            }

            #menu li a.active {
            background-color: #ADDEFF;
            color: #202020;
            }


            /* 3 columns layout */

            #content {
            width: 960px;
            margin: 0 auto;
            text-align: left;
            background-color: #FFFFFF;
            }

            #column-1 {
            float: left;
            width: 510px;
            }

            #column-2 {
            float: right;
            width: 448px;
            }

            #column-1, #column-2 { padding-bottom: 60px; }

            #column-2 #column-21 { float: left; width: 212px; margin-top: 1em; font-family: Arial, Helvetica, sans-serif; }
            #column-2 #column-22 { float: right; width: 234px; margin-top: 1em; }

            #column-1 div.content { margin: 1em 1.7em 0 0; }
            #column-21 div.content{ margin: 0.4em 0.8em 1.2em 0.5em; }
            #column-22 div.content { margin: 0.4em 0 1.2em 1.5em; }


            /* Right menu */

            ul.r-list { list-style: none; margin: 12px 0 35px 0; }

            ul.r-list li { margin-bottom: 10px; }

            ul.r-list li a, ul.r-list li a:visited {
            display: block;
            padding: 8px 10px 9px 10px;
            text-decoration: none;
            font-weight: normal;
            border-right: 1px solid #CCCCCC;
            border-bottom: 1px solid #CCCCCC;

            height: 1%; /* The Holly Hack for IE 6 */
            }

            ul.r-list li a:hover {
            border-right: 1px solid #909090;
            border-bottom: 1px solid #909090;
            }

            ul.r-list li a.active {
            background-color: #E3F5FF;
            }


            /* Definition list - middle columns (News) */

            #column-21 dl { margin: 8px 0 20px 1px; }
            #column-21 dt { font-weight: bold; margin: 0 0 1px 0; }
            #column-21 dd { margin: 0 0 11px 0; padding: 0 0 5px 0; border-bottom: 1px dotted #909090; line-height: 1.5; }
            #column-21 dd a, #column-21 dd a:visited { color: #101010; font-weight: normal; text-decoration: none; }
            #column-21 dd a:hover { text-decoration: underline; }


            /* Gallery */

            .galerie { margin: 18px 0 0 0; }

            .foto {
            float: left;
            display: inline;
            width: 122px;
            height: 100px;
            margin: 0 23px 16px 0;
            background-color: white;
            position: relative;
            }

            .foto img { border: 1px solid silver; }
            .foto a:hover img { border: 1px solid #606060; }


            /* Footer */

            #footer {
            background-color: #327000;
            height: 90px;
            border-top: 6px solid #1B3D00;
            }

            #footer-in {
            width: 960px;
            margin: 0 auto;
            font: 1.2em Georgia, "Times New Roman", Times, serif;
            }

            #footer-in ul {
            list-style: none;
            padding: 10px 0 0 0;
            text-align: left;
            font-size: 0.9em;
            float: left;
            width: 650px;
            color: #FBFBFB;
            }

            #footer-in ul li {
            float: left;
            display: inline;
            white-space: nowrap;
            }

            #footer-in ul li a, #footer-in ul li a:visited {
            color: #FBFBFB;
            margin: 0 8px;
            font-weight: normal;
            }

            #footer-in p.print {
            float: right;
            text-align: right;
            width: 240px;
            padding: 10px 0 0 0;
            line-height: 1;
            margin: 0;
            color: #FFFFFF;
            }

            #footer-in p.print a, #footer-in p.print a:visited {
            color: #FBFBFB;
            font-weight: normal;
            }

            #footer p.print {
                font-size: 8pt;
                color: silver;
                text-align: left;
                margin: 0px 5px;
            }
            #footer-in p#backs {
            line-height: 1;
            margin: 45px 0 0 0;
            color: #D1DDC5;
            font-size: 0.8em;
            }

            #footer-in p#backs a, #footer-in p#backs a:visited {
            color: #D1DDC5;
            font-weight: normal;
            padding: 0 1px;
            }

            #footer-in p#backs a:hover {
            color: #FFFFFF;
            }
        /** from style.css END **/

        /** from style-layout.css BEGIN **/
            /* hide clientError at forms on valid fields */
            .was-validated .form-control:valid ~ .invalid-feedback.clientFormError {
              display: none;
            }
            /* error-text direct below select-fields */
            .custom-select {
                margin-bottom: 0px !important;
            }

            .was-validated .is-invalid {
              border-color: #dc3545 !important;
            }


            @media only screen and (max-width: 979px) {
            #content {
                /*Responsive: column-2 unter column-1*/
                display: flex;
                flex-direction: column;
            }
            }
            body {
              min-width: auto;
            }

            /* bar oberer Rand */
            #bar {
                background: #151515;
                height: 10px;
            }
            /* header background in top verschoben, um bar darüber zu ermöglichen */
            #top {
                background: #FFFFFF url('~/assets/img/layout/hd-bg.gif') repeat-x ;
            }
            #header h1 a {
              padding: 0 10px;
            }
            #header p.title {
              padding: 5px 10px 0 10px;
            }
            #column-1, #column-2 {
              padding: 10px;
            }
            #column-1 {
            width: 710px;
            }
            #column-2 {
            width: 248px;
            }

            @media print {
              /*  This template was created by Mantis-a [http://www.mantisa.cz/]. For more templates visit Free website templates [http://www.mantisatemplates.com/]. */

              /* CSS Document - print style sheet */
              body {
              background-color: #FFFFFF;
              font-family: "Times New Roman", Times, serif;
              line-height: 1.3;
              }

              h1, h2, h3, h4, h5, h6 {
              font-family: Verdana, Arial, Helvetica, sans-serif;
              color: #595959;
              }

              h1 {
              font-size: 16pt;
              }

              h2 {
              font-size: 14pt;
              }

              h3, h4, h5, h6 {
              font-size: 11pt;
              }

              a:link, a:visited {
              text-decoration: underline;
              font-weight: bolder;
              color: #000000;
              }

              a img {
              border: 1px solid #595959;
              }

              .hidden {
              position: absolute;
              top: -10000px;
              left: 0;
              width: 1px;
              height: 1px;
              overflow: hidden;
              }

              #menu, p.print {
                display: none;
              }

        /** from style-layout.css END **/

        /** from MainMenu-Component Begin **/
            #menu {
                position: relative;
                display: flex;
            }

            @media only screen and (max-width: 979px) {
            #menu {
                /*Desktop: ausgeblendet*/
                display: none;
            }
            }

            #menu li a {
                display: inline-block;
                white-space: nowrap;
            }
            #menu li a.hamburger {
                background-image: url('~/assets/img/layout/hamburger-menu2.gif');
                background-repeat: no-repeat;
                background-size: 26px;
                background-position-y: 4px;
                background-position-x: 2px;
                padding-left: 35px;
            }
            #menu .submenu-parent { 
                position: relative;
            }
            #menu .submenu-parent:hover .submenu, #menu .submenu-parent.menuClicked .submenu {
                width: auto;
                visibility: visible; /* shows sub-menu */
                opacity: 1;
                z-index: 999;
                transform: translateY(0%);
                transition-delay: 0s, 0s, 0.3s; /* this removes the transition delay so the menu will be visible while the other styles transition */
            }
            #menu .submenu {
                border: 1px solid #b0bcc3;
                background: #eee;
                visibility: hidden; /* hides sub-menu */
                opacity: 1;
                position: absolute;
                top: 2.35em;
                left: -1px;
                /* transform: translateY(-4em); */
                z-index: 999;
            }
            #menu .submenu li {
                border-bottom: 1px solid #b0bcc3;
                text-align: left;
                width: 100%;
            }
            #menu .submenu li a {
                border-right: 0px;
                width: 100%;
                display: inline-block;
            }
            #menu .submenu li {
                display: inline-block;
                text-align: left;
            }
            #menu .submenu li a {
                float:none;
                white-space: nowrap;
            }
            #menu li.divider {
                width: 100%;
            }
            #menu li.right-aligned {
                border-right: 0px;
                border-left: 1px solid #B0BCC3;
            }

            #menu li.user-menu {
                position: relative;
            }
            #menu li.user-menu .flyerBox {
                position: absolute;
                right: 0px;
                top: 2.35em;
                visibility: hidden; /* hides sub-menu */
            }
            #menu li.user-menu .flexBox {
                display: flex;
            }
            #menu li.user-menu a {
                background: #dd0000;
                color: #ffffff;
            }
            #menu li.user-menu a:hover {
                background: #dd0000;
                color: #dddddd;
            }
            #menu li.user-menu .submenu, #menu li.user-menu .submenu a {
                background: #ff0000;
            }
            #menu li.user-menu ul.submenu {
                right: 0px;
                left: auto;
                position: inherit;
            }
            #menu li.user-menu ul.submenu li.headline {
                text-decoration: underline;
                font-style: italic;
                color: #ffffff;
                background: #dd0000;
                padding: 0 18px;
                border-bottom: 0px;
                width: 100%;
                white-space: nowrap;
            }
        /** from MainMenu-Component End **/

        
        </style>
        <div>
          <div id="bar"></div>
          <div id="top">
          <div id="wrapper">

            <!-- Header -->
            <div id="header">
            <h1><a href="<?= Yii::$app->params['domain'] ?>">TTKV Harburg-Land e.V.</a></h1>
            <p class="title">Tischtennis in der südlichen Metropolregion von Hamburg</p>
            </div> <!-- Header end -->

            <!-- Menu -->
            <div id="menu-box" class="cleaning-box">
                <ul id="menu">
                    <li><a href="<?= Yii::$app->params['domain'] ?>" class="first">Home</a></li>
                  <li><a href="<?= Yii::$app->params['domain'] ?>/page/test">Pokal</a></li>
                  <li><a href="<?= Yii::$app->params['domain'] ?>/page/test">Anschriften</a></li>
                  <li><a href="<?= Yii::$app->params['domain'] ?>/page/test">Termine</a></li>
                  <li><a href="<?= Yii::$app->params['domain'] ?>/page/test">Downloads</a></li>
                  <li><a href="<?= Yii::$app->params['domain'] ?>/page/test">Links</a></li>
                  <li class="divider"></li>
                </ul>
                
            </div> <!-- Menu end -->

            <hr class="noscreen" />
            <div id="skip-menu"></div>

            <div id="content"> 

              <div id="column-1">
                <div class="content">
    
                    <!-- Inhalt -->
                    <?= $content ?>

                </div> <!-- content end -->
              </div> <!-- Column 1 end -->

              <div class="cleaner">&nbsp;</div>
            </div>  <!-- Content of the site end -->


            <div id="footer">
              <div id="footer-in">
                <!--
                <ul>
                  <li><a href="#" class=" first active">Home</a> |</li>
                  <li><a href="#">About us</a> |</li>
                  <li><a href="#">Testimonials</a> |</li>
                  <li><a href="#">We support</a> |</li>
                  <li><a href="#">Contact</a></li>
                </ul>

                <p class="print"></p>
                <div class="cleaner">&nbsp;</div>
                <p id="backs"><a href="http://www.mantisatemplates.com/">Mantis-a templates</a> | tip <a href="http://www.topas-tachlovice.cz/topas-tachlovice.aspx" title="Občanské sdružení TOPAS Tachlovice">Tachlovice</a></p>
                -->
              </div>
                <p class="print">
                    Sie erhalten diese Mail, weil Sie einen Account auf der Seite ttkv-harburg.de erstellt haben
                    oder dem TTKV Harburg-Land e.V. als Vereinskontakt zur Verfügung stehen.
                </p>
            </div> <!-- Footer end -->

          </div> <!-- Wrapper end -->
          </div> <!-- top -->
        </div>
        
    </body>
</html>
<?php $this->endPage() ?>
