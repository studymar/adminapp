<?php


/* 
 * @param string $editUrl
 * @param string $deleteUrl
 * @param string $sortUpUrl
 * @param Release $release
 * @param Object $item
 * @param Boolean $alignright
 */
?>
                                <div class="edit-line <?= (isset($alignright) && $alignright)?'align-right':'' ?>">
                                    <?php if($editUrl): ?>

                                    <a href="<?= $editUrl ?>" id="edit<?= $item->id ?>" class="btn btn-secondary">Edit</a>
                                    <?php endif; ?>
                                    <?php if($deleteUrl): ?>

                                    <a href="<?= $deleteUrl ?>" id="delete<?= $item->id ?>" class="btn btn-danger" data-confirm="Sind Sie sicher, dass Sie das ausgewählte Element löschen möchten?">Delete</a>
                                    <?php endif; ?>
                                    <?php if($sortUpUrl): ?>

                                    <a href="<?= $sortUpUrl ?>" id="sortUp<?= $item->id ?>" class="btn btn-info">SortUp</a>
                                    <?php endif; ?>
                                    <i><?= (isset($release))?$release->getReleasestatusText():'' ?></i>
                                </div>

