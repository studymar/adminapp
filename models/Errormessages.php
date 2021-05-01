<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of Errormessages
 *
 * @author mwort
 */
class Errormessages {
    public static $errors = [
        'MISSINGREQUESTDATAERROR'  => ['id' => 1000, 'message' => 'Missing Requestdaten to proceed'],
        'IMAGELISTVALIDATIONERROR' => ['id' => 1001, 'message' => 'Validationerror: Not able to save ImageList from Form.'],
        'IMAGEITEMSAVINGERROR'  => ['id' => 1002, 'message' => 'Not able to save ImageItem with this uploadedimageid.'],
        'TEASERNOTFOUNDERROR'  => ['id' => 1003, 'message' => 'Teaser not found'],
    ];
}
