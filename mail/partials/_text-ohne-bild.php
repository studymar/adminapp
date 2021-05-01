<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $text Textinhalt */
/* @var $headline Headline */

if(!isset($text)){
    $text = "Dies ist ein Typoblindtext. An ihm kann man sehen, ob alle Buchstaben da sind und wie sie aussehen. Manchmal benutzt man Worte wie Hamburgefonts, Rafgenduks oder Handgloves, um Schriften zu testen. Manchmal Sätze, die alle Buchstaben des Alphabets enthalten - man nennt diese Sätze »Pangrams«.";
}
?>
                                <!-- Text ohne Bild -->
                                  <tr>
                                        <td style="padding: 10px;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <?php if(isset($headline)){ ?>
                                                <!-- Headline -->
                                                <tr>
                                                    <td valign="top" style="font-weight: bold; font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; line-height: 130%; color: #333; font-size: 13px;">
                                                    <?= $headline ?>
                                                    </td>
                                                </tr>
                                                <!-- Abstand zwischen headline und Text -->
                                                <tr>
                                                    <td height="10" valign="top" style="height: 10px">
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                <td valign="top" style=" font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; line-height: 130%; color: #333; font-size: 13px;">
                                                    <?= $text ?>
                                                    <br />
                                                </td>
                                                </tr>
                                                </table>
                                        </td>
                                  </tr>