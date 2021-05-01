<?php


/* 
 * @param array $buttons
 * @param string $buttons[url] Url des Buttons
 * @param string $buttons[name] Bezeichnung des Buttons
 * @param boolean $buttons[confirm] default true 
 * @param string $buttons[class] CSS-Class default= btn-light
 * @param string $releasestatus [released,notreleased,expired,waiting] default= inactive
 */
?>
                                <div class="edit-line align-right">
                                    <?php if(isset($releasestatus)){ ?>   
                                    <div class="release-icons <?= (isset($releasestatus))?$releasestatus:'notreleased' ?>">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                    <?php } ?>
                                    <?php foreach($buttons as $item): ?>

                                    <a href="<?= $item['url'] ?>" class="btn <?= (isset($item['class']))?$item['class']:'btn-light' ?>" <?= (isset($item['confirm']) && $item['confirm'])? 'data-confirm="Sind Sie sicher, dass Sie die ganze Liste löschen möchten?"':'' ?> alt="<?= $item['name']?>">
                                        <?php if(isset($item['icon'])){ ?>
                                        <span class="material-icons"><?= $item['icon'] ?></span>
                                        <?php } 
                                        else echo $item['name'];
                                        ?>
                                    </a>

                                    <?php endforeach; ?>
                                </div>

