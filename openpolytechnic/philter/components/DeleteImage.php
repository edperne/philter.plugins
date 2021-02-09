<?php namespace Openpolytechnic\Philter\Components;

use Auth;
use Input;
use Flash;
use Redirect;
use Cms\Classes\ComponentBase;
use Openpolytechnic\Philter\Models\Image as ImageModel;

class DeleteImage extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'DeleteImage Component',
            'description' => 'Handles the deletion of a user\'s images'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun() 
    {
        $id = Input::get('delete_image');
        $user = Auth::getUser();
        if ($id && $user) {
            $image = ImageModel::find($id);
            if ($image && ($image->user->id === $user->id)){
                ImageModel::destroy($id);
                Flash::success('Your image has been deleted');
                return Redirect::back();
            }
        }
    }
}
