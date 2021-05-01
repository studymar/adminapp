<?php

namespace app\models\helpers;
use Yii;
use yii\web\UnauthorizedHttpException;
use app\models\user\User;

/**
 * Description of DateConverter
 * Zum Formatieren vonn Datumswerten
 * @author mwort
 */
class MyJWTDecoder extends MyJWT{
    
    public function __construct($jwt) {
        $explode = explode(MyJWT::$divider, $jwt);
        $this->header    = json_decode(base64_decode($explode[0]));
        $this->payload   = json_decode(base64_decode($explode[1]));
        $this->signature = json_decode(base64_decode($explode[2]));
        
        parent::__construct();
    }

    
    public static function getPayloadFromJWT($jwt){
        $explode        = explode(MyJWT::$divider, $jwt);
        return base64_decode($explode[1]);
    }
    public static function getSignatureFromJWT($jwt){
        $explode        = explode(MyJWT::$divider, $jwt);
        return $explode[2];
    }
    public static function getUsernameFromPayload(){
        $explode        = explode(MyJWT::$divider, self::getPayloadFromJWT($jwt));
        return $explode[1];
    }
    public static function getExpiringTimestampFromJWT($jwt){
        $explode        = explode(self::$divider, self::getPayloadFromJWT($jwt));
        return $explode[0];
    }


    /**
     * Prüft, ob das Token gueltig ist
     * 1. Payload gegen signature pruefen
     * 2. validTo noch gueltig?
     * 3. User finden
     * Hinweis: Funktion wird in Controller aufgerufen (falls er geprüft werden soll)
     * Achtung: auch wenn nicht, muss zumindest OPTIONS erlaubt sein in URLManager (wird bei
     * eingeloggt automatisch mitgeschickt)
     * @param boolean $checkExpired [default=true] Ob auch die Gueltigkeit geprüft werden soll
     * @return User
     */
    public static function isValidToken($checkExpired = true) {
        $authHeader = Yii::$app->getRequest()->getHeaders()->get('Authorization');
        //Yii::info("App: ".json_encode(Yii::$app->getRequest()->getHeaders()->toArray()));
        $matches = [];
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            //1. Payload vergleichen (korrekt signiertes Token?)
            $jwt = $matches[1];
            if(self::isValidSignature($jwt)){
                //2. Noch gueltig?
                if($checkExpired)
                    self::isActiveToken(self::getExpiringTimestampFromJWT($jwt));
                //3. existiert User mit dem Token?
                
                $identity = User::findIdentityByAccessToken($jwt);
                if($identity){
                    return $identity;
                }
                else {
                    Yii::info("Unauthorized: Identity/User not found ",__METHOD__);
                    throw new UnauthorizedHttpException('Your request was made with invalid credentials: ' + $jwt);
                }
            }
            else {
                
            }
        }
        //Yii::info($authHeader);
        throw new UnauthorizedHttpException('Your request was made with invalid token.');
        return false;
        
    }

    public static function isValidSignature($jwt) {
        $payload        = self::getPayloadFromJWT($jwt); 
        $calcSignature  = MyJWTCreator::createSignature($payload);

        $signature      = self::getSignatureFromJWT($jwt);
        if($calcSignature == $signature)
            return true;
        Yii::info("Signature not valid: ".$calcSignature." == ".$signature,__METHOD__);
        throw new UnauthorizedHttpException('Your request was made with invalid signature.');
    }    

    public static function isActiveToken($timestamp) {
        if($timestamp<=(time()+self::$duration)*1000)//in ms
            return true;
        Yii::info("IsActiveToken - Token expired: ".$timestamp." <= ".(time()+self::$duration)*1000,__METHOD__);
        throw new UnauthorizedHttpException('Your request was made with expired token.');
    }    


    
}
