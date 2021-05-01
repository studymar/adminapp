<?php

namespace app\models\helpers;
use Yii;

/**
 * Description of DateConverter
 * Zum Formatieren vonn Datumswerten
 * @author mwort
 */
class MyJWTCreator extends MyJWT{
    
    /**
     * Payload wird mit 1.username, 2.Token-Gueltig-bis-Datum in ms erstellt
     * (nur interne Verwendung)
     * @param User $user
     * @return string JSON
     */
    protected static function createNewPayload($user){
        $expireDate     = (time()+self::$duration)*1000;//umrechnen in ms
        return $expireDate.MyJWT::$divider.$user->username;
    }
    
    /**
     * Erstellt ein JWTToken für einen User
     * @param User $user
     * @return string token
     */
    public static function createJWTForUser($user){
        //1. base64 erzeugen
        $payload  = self::createNewPayload($user);
        //token speichern
        $user->authkey  = self::createJWTForPayload($payload);
        $user->lastlogindate = new \yii\db\Expression('NOW()');        
        if($user->save()) {
            $usertoken = new \app\models\user\Usertokens();
            $usertoken->token = $user->authkey;
            $usertoken->id = 0;
            if(!$usertoken->save())
                echo json_encode($usertokens->getErrors());
            //Yii::info("Save Refresh-token: ".$user->authkey,__METHOD__);
            return $user->authkey;
        } 
        Yii::error ('Fehler beim speichern eines Usertokens: '.json_encode ($user->getErrors()),__METHOD__);
        throw new \yii\base\ErrorException("JWT konnte nicht erzeugt werden");
    }
    
    /**
     * Erstellt ein Token (User ist bereits im Payload enthalten)
     * @param string $payload
     * @return string
     */
    public static function createJWTForPayload($payload){
        //header erzeugen
        $base64header   = self::createHeader();
        //verschluesseln als signature
        $base64signature = self::createSignature($payload);
        //3. jwt erzeugen
        $base64payload  = base64_encode($payload);
        // Yii::info("payload: " . $payload . " Base64Payload: ". $base64payload);
        // echo $base64header.self::$divider.$base64payload.self::$divider.$base64signature;
        // echo base64_decode($base64header).self::$divider.base64_decode($base64payload).self::$divider.$base64signature;
        return $base64header.self::$divider.$base64payload.self::$divider.$base64signature;
    }

    /**
     * Erzeugt die Signature anhand des Payloads und liefert sie base64-verschlüsselt zurück
     * @param string $payload
     * @return string
     */
    public static function createSignature($payload){
        $base64header   = self::createHeader();
        $base64payload  = base64_encode($payload);
        /*
        Yii::info("payload: ".$payload);
        Yii::info("base64payload: ".$base64payload);
        Yii::info("header+payload: ".$base64header.self::$divider.$base64payload);
        */
        $sig            = crypt($base64header.self::$divider.$base64payload,CRYPT_SHA512.self::$secret);
        return base64_encode($sig);
    }    

    public static function createHeader(){
        return base64_encode(json_encode(self::$header));
    }
    
}
