<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* 
 * Hauptmenu and Reponsive-Menu
 */
?>

        <?php foreach($menu as $item){ ?>
            <li class="<?= (isset($this->params['page']) && $item->path==null && $this->params['page']->urlname == $item->page->urlname)?'active':'' ?> <?= (isset($this->params['page']) && isset($this->params['page']->parentpage) && $item->path==null && $this->params['page']->parentpage->urlname == $item->path )?'open':'' ?>">
                <?php if(count($item->subnavigationsActive) < 1 && isset($item->page) && $item->path == null){ //interner Link ?>
                <a href="<?= Url::toRoute(['page/edit','p'=> $item->page->urlname]) ?>" class="<?= ($item->id == 1)?'first':'' ?> <?= ($item->isActive())?'active': '' ?>"><?= $item->name ?></a>

                <?php } else if($item->path != null){ //externer Link  ?>
                <a href="<?= ($item->path)?$item->path:'/' ?>" class="<?= ($item->id == 1)?'first':'' ?> <?= ($item->isActive())?'active': '' ?>" target="_blank"><?= $item->name ?></a>

                <?php } else { //Submenu öffnen ?>
                <a href="#" class="keepOpenByClick <?= ($item->id == 1)?'first':'' ?> <?= ($item->isActive())?'active': '' ?>"><?= $item->name ?></a>

                <?php } ?>
                <?php if($item->subnavigationsActive){ ?>
                <ul class="submenu sidebar-nav">
                    <?php foreach($item->subnavigationsActive as $subitem){ ?>
                    <li class="<?= (isset($this->params['page']) && isset($item->page) && $this->params['page']->urlname == $item->page->urlname)?'active':'' ?>">
                    <?php if($subitem->path == null){ //interner Link ?>
                    <a href="<?= Url::toRoute(['page/edit','p'=> $item->page->urlname]) ?>" class="<?= ($subitem->isActive())?'active': '' ?>"><?= $subitem->name ?></a>

                    <?php } else { //externer Link ?>
                    <a href="<?= ($subitem->path)?$subitem->path:'/' ?>" class="<?= ($subitem->isActive())?'active': '' ?>" target="_blank"><?= $subitem->name ?></a>

                    <?php } ?>
                    </li>

                    <?php } ?>
                </ul>

                <?php } ?>
            </li>        
        <?php } ?>


    <?php /*
    <!-- Hauptmenu breit Start -->
    <ul id="menu">
        
        <?php foreach($menu as $item){ ?>
        <li class="<?= ($item->subnavigationsActive)?'submenu-parent':'' ?>">
            <?php if(count($item->subnavigationsActive) < 1 && $item->external == '0'){ ?>
            <a href="<?= ($item->path)?$item->path:'/' ?>" target="<?= ($item->external != '0')?'_blank':'' ?>" class?>"="<?= ($item->id == 1)?'first':'' ?> <?= ($item->isActive())?'active': '' ?>"><?= $item->name ?></a>

            <?php } else if($item->external == '1'){ ?>
            <a href="<?= ($item->path)?$item->path:'/' ?>" class="<?= ($item->id == 1)?'first':'' ?> <?= ($item->isActive())?'active': '' ?>" target="_blank"><?= $item->name ?></a>

            <?php } else { //Submenu öffnen ?>
            <a href="#" class="keepOpenByClick <?= ($item->id == 1)?'first':'' ?> <?= ($item->isActive())?'active': '' ?>"><?= $item->name ?></a>

            <?php } ?>

            <?php if($item->subnavigationsActive){ ?>
            <ul class="submenu">
                <?php foreach($item->subnavigationsActive as $subitem){ ?>
                <li>
                <?php if($subitem->external == '0'){ //interner Link ?>
                <a href="<?= ($subitem->path)?$subitem->path:'/' ?>" class="<?= ($subitem->isActive())?'active': '' ?>"><?= $subitem->name ?></a>

                <?php } else { //externer Link ?>
                <a href="<?= ($subitem->path)?$subitem->path:'/' ?>" class="<?= ($subitem->isActive())?'active': '' ?>" target="_blank"><?= $subitem->name ?></a>

                <?php } ?>
                </li>

                <?php } ?>
            </ul>

            <?php } ?>
        </li>
        <?php } ?>

        <!--
        <li><nuxt-link  to="/" class="first">Home</nuxt-link></li>
        <li><a href="#">Click-tt</a></li>
        <li><a href="#">Pokal</a></li>
        <li class="submenu-parent"><a href="#" v-on:click.prevent="menuClicked" class="active">KM / RL</a>
            <ul class="submenu">
                <li><a href="#">Kreismeisterschaften</a></li>
                <li><a href="#">Ranglisten</a></li>
            </ul>
        </li>
        <li><a href="#">Anschriften</a></li>
        <li class="submenu-parent"><a href="#" v-on:click.prevent="menuClicked">Termine</a>
            <ul class="submenu">
                <li><a href="#">Termine Damen/Herren</a></li>
                <li><a href="#">Termine Jugend</a></li>
            </ul>
        </li>
        <li><a href="#">Downloads</a></li>
        <li><a href="#">Links</a></li>
        -->

        <li class="divider"></li>
        <?php if(!Yii::$app->user->isGuest){ ?>
        <li class="right-aligned user-menu submenu-parent"><a href="#" class="keepOpenByClick" id="myMenuButton">Mein Menü</a>
            <div class="flexBox">
            <ul class="submenu">
                <li class="headline">Mark Worthmann</li>
                <li><?= Html::a('Meine Daten',['account/my-account']) ?></li>
                <li><?= Html::a('Logout',['account/logout'],['id'=>'logout-link']) ?></li>

                <li class="headline">TVV Neu Wulmstorf</li>
                <li><a href="#">Vereinskontaktdaten pflegen</a></li>
                <li><a href="#">Vereinsmeldung abgeben</a></li>
            </ul>
            <ul class="submenu">            
                <li class="headline">Verwaltung</li>
                <li><?= Html::a('Hauptmenü verwalten',['menu/navigationmanager']) ?></li>
                <li><?= Html::a('Userverwaltung',['usermanager/index']) ?></li>
                <li><?= Html::a('Rollenverwaltung',['rolemanager/index']) ?></li>

                <li class="headline">Alle Vereine</li>
                <li><a href="#">Vereine verwalten</a></li>
                <li><a href="#">Vereinspositionen verwalten</a></li>
                <li><a href="#">Vereinsmeldung verwalten</a></li>
            </ul>
            <ul class="submenu">            
                <li class="headline">Spielbetrieb</li>
                <li><a href="#">Spielzeiten/Saisons</a></li>
                <li><a href="#">Spielklassen</a></li>
            </ul>
            <ul class="submenu">            
                <li class="headline">Abstimmungen/ Umfragen</li>
                <li><?= Html::a('Abstimmungen verwalten',['voting/edit']) ?></li>
            </ul>
            </div>
        </li>
        <?php } else { ?>
        <li class="right-aligned"><a href="<?= Url::toRoute(['account/index']) ?>">Login</a></li>
        <?php } ?>
    </ul>
    <!-- Hauptmenu Breit End -->
    
    
    <!-- Hauptmenu Responsive Start -->
    <ul id="menuResponsive">
        <li class="submenu-parent"><a href="#" data-target="MainMenu" class="menuOpenByClickResponsive first hamburger submenu-parent">Menü</a>
            <ul class="submenu hide" id="MainMenu">
                <!--
                <li><a href="#">Home</a></li>
                <li><a href="#">Click-tt</a></li>
                <li><a href="#">Pokal</a></li>
                <li class="submenu-parent"><a href="#" v-on:click.prevent="menuClickedResponsive('KMMenu',$event)" class="active">KM / RL >></a>
                </li>
                <li><a href="#">Anschriften</a></li>
                <li class="submenu-parent"><a href="#" v-on:click.prevent="menuClickedResponsive('TerminMenu',$event)">Termine >></a>
                </li>
                <li><a href="#">Downloads</a></li>
                <li><a href="#">Links</a></li>
                -->
                <?php
                $submenuArray = [];
                $submenuCounter = 1;
                foreach($menu as $item){ ?>
                <li class="<?= ($item->subnavigationsActive)?'submenu-parent':'' ?>">
                    <?php if(count($item->subnavigationsActive) < 1 && $item->external == '0'){ //interner Link ?>
                    <a href="<?= ($item->path)?$item->path:'/' ?>" target="<?= ($item->external != '0')?'_blank':'' ?>" class="<?= ($item->isActive())?'active': '' ?>"><?= $item->name ?></a>
                    <?php } else if($item->external == '1'){ //externer Link ?>
                    <a href="<?= ($item->path)?$item->path:'/' ?>" class="<?= ($item->isActive())?'active': '' ?>" target="_blank"><?= $item->name ?></a>
                    <?php } else { //Submenu öffnen ?>
                    <a href="#" data-target="<?= 'Sub'.$submenuCounter ?>" class="menuOpenByClickResponsive <?= ($item->isActive())?'active': '' ?>"><?= $item->name ?> <i class="material-icons">arrow_drop_down</i></a>
                    <?php } ?>
                </li>
                <?php 
                //alle Submenus in das Array ergänzen, um es erst weiter unten rendern zu können
                if($item->subnavigationsActive)
                    $submenuArray['Sub'.$submenuCounter++] = $item->subnavigationsActive;
                ?>
                <?php } ?>
            </ul>
        </li>


        <li class="divider"></li>
        <!--
        <li class="right-aligned user-menu"><a href="#" data-target="MainMenu" class="menuOpenByClickResponsive hamburger submenu-parent">Mein Menü</a></li>
            <div class="flexBox">
            <ul class="submenu">
                <li class="headline">Mein Account</li>
                <li><nuxt-link to="/meinedaten">Meine Daten</nuxt-link ></li>
                <li><?= Html::a('Logout',['account/logout']) ?></li>
                <li class="headline">Mein Verein (TVV Neu Wulmstorf)</li>
                <li><a href="#">Vereinskontaktdaten pflegen</a></li>
                <li><a href="#">Vereinsmeldung abgeben</a></li>
            </ul>
            <ul class="submenu">
                <li class="headline">Verwaltung</li>
                <li><nuxt-link  to="/navigation">Hauptmenü verwalten</nuxt-link></li>
                <li><nuxt-link  to="/usermanager">Userverwaltung</nuxt-link></li>
                <li><nuxt-link to="/usermanager/roles">Rollenverwaltung</nuxt-link></li>

                <li class="headline">Alle Vereine</li>
                <li><a href="#">Vereine verwalten</a></li>
                <li><a href="#">Vereinspositionen verwalten</a></li>
                <li><a href="#">Vereinsmeldung verwalten</a></li>
            </ul>
            <ul class="submenu">            
                <li class="headline">Spielbetrieb</li>
                <li><a href="#">Spielzeiten/Saisons</a></li>
                <li><a href="#">Spielklassen</a></li>
            </ul>
            </div>
        </li>
        -->
        <?php if(!Yii::$app->user->isGuest){ ?>
        <li class="right-aligned user-menu"><a href="#" data-target="flyerBox" class="menuOpenByClickResponsive hamburger submenu-parent">Mein Menü</a>
        <div class="flyerBox hide" id="flyerBox">
            <div class="flexBox">
            <ul class="submenu">
                <li class="headline">Mein Account</li>
                <li><?= Html::a('Meine Daten',['account/my-account']) ?></li>
                <li><?= Html::a('Logout',['account/logout']) ?></li>
                <li class="headline">Mein Verein (TVV Neu Wulmstorf)</li>
                <li><a href="#">Vereinskontaktdaten pflegen</a></li>
                <li><a href="#">Vereinsmeldung abgeben</a></li>
            </ul>
            <ul class="submenu">
                <li class="headline">Verwaltung</li>
                <li><?= Html::a('Hauptmenü verwalten',['menu/navigationmanager']) ?></li>
                <li><?= Html::a('Userverwaltung',['usermanager/index']) ?></li>
                <li><?= Html::a('Rollenverwaltung',['rolemanager/index']) ?></li>

                <li class="headline">Alle Vereine</li>
                <li><a href="#">Vereine verwalten</a></li>
                <li><a href="#">Vereinspositionen verwalten</a></li>
                <li><a href="#">Vereinsmeldung verwalten</a></li>
            </ul>
            <ul class="submenu">            
                <li class="headline">Spielbetrieb</li>
                <li><a href="#">Spielzeiten/Saisons</a></li>
                <li><a href="#">Spielklassen</a></li>
            </ul>
            <ul class="submenu">            
                <li class="headline">Abstimmungen/ Umfragen</li>
                <li><a href="#">Abstimmungen verwalten</a></li>
            </ul>
            </div>
        </div>
        <?php } else { ?>
        <li class="right-aligned"><?= Html::a('Login',['account/index']) ?></li>
        <?php } ?>
        <li>
            <?php foreach($submenuArray as $key => $submenu){ ?>
            <ul class="submenu hide" id="<?= $key ?>">
                <li><a href="#" data-target="MainMenu" class="menuOpenByClickResponsive"> Zurück <i class="material-icons">arrow_drop_up</i></a></li>
                <?php foreach($submenu as $item){ ?>
                <li>
                      <?php if($item->external == '0'){ //interner Link ?>                  
                      <a href="<?= ($item->path)?$item->path:'/' ?>" class="<?= ($item->isActive())?'active': '' ?>"><?= $item->name ?></a>
                      <?php } else { // externer Link ?>                    
                      <a href="<?= ($item->path)?$item->path:'/' ?>" class="<?= ($item->isActive())?'active': '' ?>" target="_blank"><?= $item->name ?></a>
                      <?php } ?>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
            <!--
            <ul class="submenu hide" id="submenuLayer">
                <li><a href="#" v-on:click.prevent="menuClickedResponsive('MainMenu',$event)"> Zurück <i class="material-icons">arrow_drop_up</i></a></li>
                <li v-for="subitem in subnavigation" :key="subitem.id" >
                    <nuxt-link v-bind:to="(subitem.path)?subitem.path:'/'" @click.native.prevent="closeSubmenu($event)" v-bind:class="{active: subitem.active}" v-if="subitem.external == '0'">{{subitem.name}}</nuxt-link>
                    <a v-bind:href="(subitem.path)?subitem.path:'/'" v-on:click.prevent="closeSubmenu($event)" v-bind:class="{active: subitem.active}" target="_blank" v-else>{{subitem.name}}</a>
                </li>
            </ul>
            -->
            <!--
            <ul class="submenu hide" id="KMMenu">
                <li><a href="#" v-on:click.prevent="menuClickedResponsive('MainMenu',$event)"> Zurück <i class="material-icons">arrow_drop_up</i></a></li>
                <li><a href="#">Kreismeisterschaften</a></li>
                <li><a href="#">Ranglisten</a></li>
            </ul>
            <ul class="submenu hide" id="TerminMenu">
                <li><a href="#" v-on:click.prevent="menuClickedResponsive('MainMenu',$event)"> Zurück <i class="material-icons">arrow_drop_up</i></a></li>
                <li><a href="#">Termine Damen/Herren</a></li>
                <li><a href="#">Termine Jugend</a></li>
            </ul>
            -->
        </li>
    </ul>    
    <!-- Hauptmenu Responsive End -->
     * 
     */
    ?>
    
    