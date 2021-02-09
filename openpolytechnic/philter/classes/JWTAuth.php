<?php namespace Openpolytechnic\Philter\Classes;

use Auth;
use Config;
use Request;
use Response;
use RainLab\User\Models\User as UserModel;

class JWTAuth
{

    /**
     * A salt to help harden the encoding.
     */
    const OP_JWT_KEY = "Dq3k)0@F%3FNVl&;Cr_j9I3vVi}jOE.RjlNBC7.X~}t!6|sIju=BmK<38I`SvPw`";

    /**
     * Method that optionally checks the October User id 
     * against the JWT token user, then returns
     * the JWT token, or false on failure
     * 
     * @param $user_id int the October User id
     * 
     * @return  bool|object The JWT's payload as a PHP object, or false on failure
     */
    public static function CheckJWTToken($user_id=false)
	{
		$jwt_user = self::GetJWTUser();
		if ($jwt_user) {
			if ($user_id && ($jwt_user->id != $user_id)) {
				return false;
			}
			return self::AddJWTToken($jwt_user);
		}
		return false;
	}

    /**
     * Finds the October user from the JWT token user,
     * or false on failure
     * 
     * @return  bool|object User Model or false on failure
     */
    public static function GetJWTUser()
    {
        /*
         * Looking for the JWT header, if not present just
         * return the user.
         */
        $token = self::GetRequestToken();
		if ($token && isset($token->data->user->id)) {
			$jwt_user = UserModel::find($token->data->user->id);
			if (is_a($jwt_user, 'RainLab\User\Models\User')) {
				return $jwt_user;
			}
		}
        return false;

    }

    /**
     * Method that uses the October User id 
     * to generate a 7-day JWT token, 
     * or to optionally expire the token
     * 
     * 
     * @param $jwt_user object UserModel
     * @param $expired boolean whether to set as expired
     *
     * @return object The JWT's payload as a PHP object
     */
    public static function AddJWTToken(UserModel $jwt_user, $expired=false)
    {

        /**
         * Valid credentials,
         * the user exists
         * create the token data
         */
        $issued_at = time();
        $not_before = $issued_at;
        if ($expired) {
            $expire = time();
        } else {
            $expire = $issued_at + (86400 * 7);
        }

        $token = array(
            'iss' => Config::get('app.url'), 	//Issurer 		= The base url of the blog
            'iat' => $issued_at,				//Issued at 	= The current timestamp
            'nbf' => $not_before,				//Not before 	= The earliest time this will be valid, also the current timestamp
            'exp' => $expire,					//Expiry 		= When the token expires
            'data' => array(		
                'user' => array(
                    'id' => $jwt_user->id,		//Data 			= User specific data
                ),
            ),
        );

        /**
         * Return the token
         */
        return JWT::encode($token, self::OP_JWT_KEY);
    }

    /**
     * Returns an expired token.
     * 
     * @param $jwt_user object UserModel
     * 
     * @return 
     */
    public static function ExpireJWTToken(UserModel $jwt_user)
    {
        return self::AddJWTToken($jwt_user, true);
    }

    /**
     * Conditional retrieval of a JWT token, 
     * returning false if it's not in the server request
     *
     * @return bool|object The JWT's payload as a PHP object, or false on failure
     */
    private static function GetRequestToken()
    {

        /**
         * Next check the
         * token is set
         */
        $token = self::GetJWTToken();
        if (!$token) {
            return false;
        }
		return JWT::decode($token, self::OP_JWT_KEY, array('HS256'));
    }

    /**
     * Checks the request for the 
     * required custom header
     * 
     * @return bool|mixed
     */
    private static function GetJWTToken()
    {
        $auth = isset($_SERVER['HTTP_AUTHORIZATION']) ?  filter_var($_SERVER['HTTP_AUTHORIZATION'], FILTER_SANITIZE_STRING) : false;
        if ($auth) {
            $parts = explode(' ', $auth);
            if (count($parts) == 2 && $parts[0] == 'Bearer') {
                return $parts[1];
            }
        }
        return false;
    }

}

