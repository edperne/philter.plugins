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
			return $this->sendResponse('You are now logged in');
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
			// console.log("registration error",$e);
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
		$user = JWTAuth::GetJWTUser();
		$this->expireToken($user);
		return $this->sendResponse('You are now logged out');
	}

			/* $user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
		}
		return $this->sendResponse(false, 'You are not authorised to make this request');
		*/

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
	 * Returns an array of the latest eight images
	 * 
     *
     * @return Response The October CMS response with an array of images
	 */
    public function getLatestImages()
    {
		$data = ImageModel::latest()->get();
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
		if ($user)
		{
			$image = new ImageModel();
			$this->saveImageModel($image, $user);
			return $this->sendResponse('Hey, Your image has been successfully uploaded');
		}
		return $this->sendResponse('Sorry, You are not authorised to add an image');
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
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) 
		{
			/**
			 * Only this user should be 
			 * updating this image
			 */
			$image = ImageModel::usersImages($user->id)->find($image_id);
			if ($image) {
				$image->delete();
				return $this->sendResponse('Your image has been successfully deleted');
			}			
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
		$image->name = Input::get('name');
		$image->description = Input::get('description');
		$image->filter = Input::get('filter');
		$image->image = Input::file('file');
		$tags = Input::get('tags');
		$tag_array = explode(', ', $tags); //split string into array seperated by ', '
		$tag_models = [];
		$image->save();
		foreach ($tag_array as $tag) {
			$tag = ucfirst(strtolower(trim($tag)));
			$tag_model = TagModel::where('tag', $tag)->first();
			if (!$tag_model || 
				($tag_model && !$image->tags->contains($tag_model->id))) {
				$tag_models[] = TagModel::getTag($tag);
			}
		}
		$image->tags()->attach($tag_models);
		$image->user = $user;
		$image->save();					
	}

	/**
	 * Method that retrieve images of the authenticated user
	 * 
	 * @param user of the authenticated user
	 * 
	 * @return Response The October CMS response with a message
	 */
    public function getUserImages()
    {
		$user = $this->checkToken();
		if (is_object($user)) {
			$data = ImageModel::usersImages($user->id)->get();
			return $this->sendResponse($data);		
		}
		else {
			return $this->sendResponse('You are not authorized to access the images');	
		}
    }

	/**
	 * Method that retrieve other images of the authenticated user
	 * 
	 * @param user of the authenticated user
	 * 
	 * @return Response The October CMS response with a message
	 */
    public function getOthersImages()
    {
		$user = $this->checkToken();
		if (is_a($user, 'RainLab\User\Models\User')) {
			$data = ImageModel::othersImages($user->id)->get();
			return $this->sendResponse($data);
        } else {
			return $this->sendResponse('You are not authorized to access the images');				
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