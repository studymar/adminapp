<?php

/* 
 * Teaser
 * @param Teaser $item
 */

use \app\models\helpers\DateConverter;

?>
<div class="card mb-12 teaser">
  <div class="row g-0">
    <div class="overlay h-100 collapse" id="overlay">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0" aria-label="Close" data-bs-toggle="collapse" data-bs-target="#overlay"></button>
        <div class="overlay-buttons align-middle">
            <a href="" class="btn btn-outline-light">Editieren</a><br/>
            <a href="" class="btn btn-outline-light">Löschen</a><br/>
        </div>
        <div class="position-absolute bottom-0 end-0 release-info"><span class="badge bg-danger">nicht sichtbar</span></div>
    </div>
    <div class="col-md-1 edit-column" data-bs-toggle="collapse" data-bs-target="#overlay" role="button">
        <div class="position-absolute bottom-0 end-0 visible-badge"><span class="badge bg-danger">N</span></div>
    </div>
    <div class="col-md-4">
      <img class="img-fluid" src="/content/images/up/kermit-20210125210055.jpg" alt="">
    </div>
    <div class="col-md-7 teaser-text">
      <div class="card-body">
        <h5 class="card-title">Card title</h5>
        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
      </div>
    </div>
  </div>
</div>

<div class="card mb-12 teaser">
  <div class="row g-0">
    <div class="col-md-1 edit-column">

    </div>
    <div class="col-md-4">
      <img class="img-fluid" src="/content/images/up/kermit1-20210111211242.jpg" alt="">
    </div>
    <div class="col-md-7 teaser-text">
      <div class="card-body">
        <h5 class="card-title">Card title</h5>
        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
      </div>
    </div>
  </div>
</div>


                                <div class="card mb-3<?= ($item->documentList->documents)?' withDocs':'' ?><?= (!empty($item->imageList->uploadedimages) && $item->imageList->uploadedimages[0]->name)?' withImageName':'' ?>">
                                    <?php if(!empty($item->imageList->uploadedimages)){ ?>
                                    <a href="" class="mb-3">
                                        <!--<img class="card-img-top" src="/content/images/up/<?= $item->imageList->uploadedimages[0]->filename ?>" alt="Card image cap">-->
                                        <div class="card-img-top-wrapper">
                                            <img class="card-img-top" src="/content/images/up/<?= $item->imageList->uploadedimages[0]->filename ?>" alt="<?= $item->imageList->uploadedimages[0]->name ?>">
                                        </div>
                                        <?php if($item->imageList->uploadedimages[0]->name){ ?>
                                        <div class="card-body m-0 py-0">
                                            <p class="card-text text-muted text-center">
                                                <?= $item->imageList->uploadedimages[0]->name ?>
                                            </p>
                                        </div>
                                        <?php } ?>
                                    </a>
                                    <?php } ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><a href=""><?= $item->headline ?></a></h5>
                                        <p class="card-text">
                                            <?= $item->text ?> 
                                            <?php if($item->linkItem->hasLink()){ ?><small class="text-muted"><a href="">>> mehr Details</a></small><?php } ?>
                                        </p>

                                        <?php if(!empty($item->documentList->documents)){
                                        foreach($item->documentList->documents as $document){ ?>
                                        <div class="document-manager">

                                            <div class="col-sm-9 ">
                                                <div class="docItem" id="document-1">
                                                    <div class="list column">
                                                        <a href="" class="icon pdf"></a>
                                                        <div>
                                                            <a href="/content/documents/up/<?= $document->filename ?>" target="_blank"><?= $document->name ?></a>
                                                            <div class="infos"><span class="size"><?= $document->size ?> kb</span></div>
                                                        </div>
                                                    </div>    
                                                </div>
                                            </div>

                                        </div>
                                        <?php }
                                        }?>

                                        
                                    </div>
                                </div>


