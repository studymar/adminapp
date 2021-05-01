<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\models\user;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use app\models\helpers\MyJWTDecoder;
//use app\models\user\User;

/**
 * HttpBearerAuth is an action filter that supports the authentication method based on HTTP Bearer token.
 *
 * You may use HttpBearerAuth by attaching it as a behavior to a controller or module, like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'bearerAuth' => [
 *             'class' => \yii\filters\auth\HttpBearerAuth::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MyHttpBearerAuth extends HttpBearerAuth
{

    /**
     * Überschreibt die Prüfung der Authenitifizierung des Users
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        //options durch lassen
        if($request->isOptions)
           return true;

        Yii::debug('Method: '.$request->getMethod(),__METHOD__);
        Yii::debug('PathInfo: '.$request->getPathInfo(),__METHOD__);
       
        //Authentification
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $identity = MyJWTDecoder::isValidToken(true);
            $isAuthorized = $identity->isAuthorizedForMethod($request->getMethod(),$request->getPathInfo());
            //$identity = $user->loginByAccessToken($matches[1], get_class($this));
            if ($identity === null || !$isAuthorized) {
                $this->handleFailure($response);
            }
            return $identity;
        }

        return null;
    }

}
