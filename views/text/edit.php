<?php

/* 
 * Text (ohne Edit-Line)
 */
use yii\helpers\Url;
use app\models\helpers\DateConverter;

?>
    <section class="text-template">
        <?php if($model->headline){ ?>
        <div class="text-headline">
            <h3><?= $model->headline ?></h3>
        </div>
        <?php } ?>

        
        <div class="card mb-3">
          <div class="no-gutters">
            <div class="card-body">
              <p class="card-text">
                  <?= $model->text ?>
              </p>
            </div>
          </div>
        </div>        

    </section>



