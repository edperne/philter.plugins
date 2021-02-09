<?php namespace Openpolytechnic\Philter\Classes;

use App;
use Auth;
use Input;
use Request;
use Response;
use Exception;
use Openpolytechnic\Philter\Models\Image as ImageModel;
use Openpolytechnic\Philter\Models\Tag as TagModel;
use RainLab\User\Models\User as UserModel;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class Api
{
	/**
	 * The JWT token to be returned 
	 * as a custom header
	 */
	public $token = '';

	/**
	 * Method that accepts $_POST login data 
	 * and tries to log in the user. 
	 * On success it returns a message with a JWT token.
	 * 
     *
     * @return Response The October CMS response with a message
	 */
    public function login()
    {
		$login = Input::get('login');
		$password = Input::get('password');
		try{
			$user = Auth::authenticate([
				'login' => $login,
				'password' => $password
			]);
			$this->setToken($user);
			return $this->sendResponse('*LISA_TEST*, You are now logged in');
		}
		catch (Exception $e) {
			return $this->sendResponse($e->getMessage());
		}
    }
	
	/**
	 * Method that accepts $_POST login data 
	 * and tries to register the user. 
	 * On success it returns a message with a JWT token.
	 * 
     *
     * @return Response The October CMS response with a message
	 */
    public function registerUser()
    {	
		/**
		 * Our UserModel will return an error 
		 * if the email already exists, 
		 * or if the passwords do not match
		 */
		 try{
			$user = new UserModel();
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			$user->password = Input::get('password');
			$user->password_confirmation = Input::get('password_confirmation');
			$user->save();	
			$this->setToken($user);
			return $this->sendResponse('You have been registered and are logged in');
		} catch(Exception $e){
			return $this->sendResponse(false,$e->getMessage());
		}		

    }
	
/**
 * Method that logs out a user 
 * by returning an expired token.
 * 
	 *
	 * @return Response The October CMS response with a message
 */
	public function logout()
	{
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
		return $this->sendResponse(false, 'You are not authorised to make this request');
	}
	
	/**
	 * Method that retrieve a single UserModel, 
	 * but only if it matches the logged-in JWT User id
	 * 
     * @return Response The October CMS response with the UserModel
	 */
    public function getUser()
    {
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
			return $this->sendResponse(UserModel::find($user->id));
		}
		return $this->sendResponse(false, 'You are not authorised to view this user');
    }
	
	/**
	 * Method that update a UserModel from $_POST fields,
	 * but only if it matches the logged-in JWT User id
	 * 
     * @return Response The October CMS response with a message
	 */
    public function updateUser()
    {		
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
		return $this->sendResponse(false, 'You are not authorised to update this account');
    }
	
	/**
	 * Method that update a UserModel from $_POST fields,
	 * but only if it matches the logged-in JWT User id
	 * 
     * @return Response The October CMS response with a message
	 */
    public function deleteUser()
    {		
	
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
		return $this->sendResponse(false, 'You are not authorised to delete this account');
    }
	
	/**
	 * Returns an array of all images
	 * 
     *
     * @return Response The October CMS response with an array of images
	 */
    public function getImages()
    {
		//$data = ImageModel::get();
		$data=ImageModel::with(['image','image'])->get();		
		// print("<pre>".print_r($data,1)."</pre>");
		// $data = [];
		// die();
		return $this->sendResponse($data);		
    }
	
	/**
	 * Method that retrieve a single image model
	 * 
     * @param $image_id the id of the desried ImageModel
	 * 
     * @return Response The October CMS response the ImageModel
	 */
    public function getImage($image_id)
    {
    }
		
	/**
	 * Method that accepts $_POST and $_FILE data 
	 * and tries to add a new image
     *
     * @return Response The October CMS response with a message
	 */
    public function addImage()
    {
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
		return $this->sendResponse(false, 'You are not authorised to add an image');
    }
	
	/**
	 * Method that accepts $_POST and $_FILE data 
	 * and tries to update an existing image
	 * 
     * @param $image_id the id of the desried ImageModel
	 * 
     * @return Response The October CMS response with a message
	 */
    public function updateImage($image_id)
    {			

		/**
		 * Validate that we have 
		 * a logged-in user
		 */
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
		return $this->sendResponse(false, 'You are not authorised to update this image');
    }
	
	/**
	 * Method that deletes an existing image
	 * 
     * @param $image_id the id of the desried ImageModel
	 * 
     * @return Response The October CMS response with a message
	 */
    public function deleteImage($image_id)
    {			

		/**
		 * Validate that we have 
		 * a logged-in user
		 */
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
		return $this->sendResponse(false, 'You are not authorised to delete this image');
    }
		
		
	/**
	 * Method that either creates or saves an ImageModel 
	 * based on $_POST and $_FILE data
	 * 
     * @param $image ImageModel either a blank or existing model
     * @param $user logged-in UserModel
	 * 
     * @return void
	 */
	private function saveImageModel($image, $user)
	{				
	}

    public function getUserImages()
    {
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
    }

    public function getOthersImages()
    {
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
			
        } else {
			
		}
    }

	/**
	 * Wrapper function for JWTAuth's AddJWTToken
	 */
    private function setToken(UserModel $user)
    {
		$this->token = JWTAuth::AddJWTToken($user);
	}

	/**
	 * Wrapper function for JWTAuth's ExpireJWTToken
	 */
    private function expireToken(UserModel $user)
    {
		$this->token = JWTAuth::ExpireJWTToken($user);
	}

	/**
	 * Wrapper function for JWTAuth's CheckJWTToken
	 */
    private function checkToken()
    {
		try {
			$user = JWTAuth::GetJWTUser();
			if (is_a($user, 'RainLab\User\Models\User')) {
				$this->token = JWTAuth::CheckJWTToken($user->id);
				return $user;
			} else {
				return false;
			}
        } catch (\UnexpectedValueException $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }
	
	/**
	 * JSON encode response and 
	 * include the token
	 */
	private function sendResponse($data, $error=false)
	{
		if ($error) {
			return Response::json($error, '401');
		}
		return Response::json($data)->header('Authorization', 'bearer ' . $this->token);
	}

}