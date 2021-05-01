<?php

/* 
 * Teaserlist (ohne Edit-Line)
 * Zeigt den Teaser an
 */

?>
                            <div class="newslist">
                                <h3><?= $model->headline ?></h3>

                                <?php foreach($items as $item): ?>
                                <?= $this->render('@app/views/teaserlist/_teaser',['item'=>$item]); //Edit-Modus-Buttons ?>
                                
                                <?php endforeach; ?>
                            </div>

