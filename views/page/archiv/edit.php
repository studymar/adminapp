<?php
    use app\models\user\User;
    use app\models\role\Right;
    $this->params['relatedButtons'] = [];
?>

                        <?php $this->params['relatedButtons']['Eintrag zur Liste hinzufügen'] = ['teaserlist/add'] ?>
                        <?php //$this->params['relatedButtons']['Template zur Seite hinzufügen/entfernen'] = ['page/addTemplate'] ?>
                        <?= $this->render('_relatedButtons'); //Edit-Modus-Buttons ?>

                        <div>
                            <!--<h2>News</h2>-->
                            
                            <?php foreach($pageHasTemplates as $contenttemplate): //jedes template der Seite ausführen ?>
                            <?= $contenttemplate->template->type ?>
                            <?= Yii::$app->runAction($contenttemplate->template->controllername.'/edit',['p'=>$contenttemplate->id]) ?>
                            <?php endforeach; ?>
                            <div class="newslist">


                                <div class="card mb-3">
                                    <a href="">
                                        <img class="card-img-top" src="content/images/placeholder/kermit-anzug.jpg" alt="Card image cap">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="">Card title</a></h5>
                                        <p class="card-text">
                                            This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                            This is a wider card with supporting text below as a natural lead-in to additional content. 
                                            <small class="text-muted"><a href="">>> mehr Details</a></small>
                                        </p>
                                        <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
                                    </div>
                                </div>        

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="">Card title</a></h5>
                                        <p class="card-text">
                                            This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                            This is a wider card with supporting text below as a natural lead-in to additional content. 
                                            <small class="text-muted"><a href="">>> mehr Details</a></small>
                                        </p>
                                        <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
                                    </div>
                                </div>        

                                <div class="card mb-3">
                                  <img class="card-img-top" src="content/images/placeholder/Kermitmithut.jpg" alt="Card image cap">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="">Card title</a></h5>
                                        <p class="card-text">
                                            This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                            <small class="text-muted"><a href="">>> mehr Details</a></small>
                                        </p>
                                        <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="">Card title</a></h5>
                                        <p class="card-text">
                                            This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                            This is a wider card with supporting text below as a natural lead-in to additional content. 
                                            <small class="text-muted"><a href="">>> mehr Details</a></small>
                                        </p>
                                        <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
                                    </div>
                                </div>        

                            </div>

                                  <h2>Curabitur Semper</h2>
                                    <h3 class="sub">Sapien id sollicitudin senectus</h3>
                                    <p><strong>Lorem ipsum dolor sit amet consectetuer dui lacus natoque vitae et.</strong> Vestibulum ornare Curabitur 
                                    Nullam Ut sapien id sollicitudin senectus aliquam id. Cum et congue pede turpis lacus in gravida 
                                    dignissim interdum condimentum. Nulla pede platea tellus Aenean et Praesent elit tellus parturient euismod. 
                                    Auctor at quis vitae eros senectus vitae tincidunt.</p>

                                    <p>Elit vel auctor id volutpat netus velit magna turpis eget adipiscing. Id Phasellus Nulla et mattis 
                                    Aenean turpis Curabitur semper diam elit. Justo Lorem vitae porttitor Nullam Pellentesque quis hendrerit 
                                    hendrerit cursus elit.</p><br />
                                <!--

                                  <h3>Auctor at quis</h3>
                                    <div class="galerie">
                                      <div class="foto">
                                        <a href="#" title=""><img src="img/image.jpg" alt="#" width="120" height="90" /></a> 
                                      </div>

                                      <div class="foto">
                                        <a href="#" title=""><img src="img/image.jpg" alt="#" width="120" height="90" /></a> 
                                      </div>

                                      <div class="foto">
                                        <a href="#" title=""><img src="img/image.jpg" alt="#" width="120" height="90" /></a> 
                                      </div>

                                      <div class="foto">
                                        <a href="#" title=""><img src="img/image.jpg" alt="#" width="120" height="90" /></a> 
                                      </div>

                                      <div class="foto">
                                        <a href="#" title=""><img src="img/image.jpg" alt="#" width="120" height="90" /></a> 
                                      </div>

                                      <div class="foto">
                                        <a href="#" title=""><img src="img/image.jpg" alt="#" width="120" height="90" /></a> 
                                      </div>
                                    <div class="cleaner">&nbsp;</div>
                                    </div>
                                -->
                            </div>
