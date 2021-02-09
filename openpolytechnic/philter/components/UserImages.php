<?php namespace Openpolytechnic\Philter\Components;

use Auth;

use Cms\Classes\ComponentBase;
use Openpolytechnic\Philter\Models\Image as ImageModel;

class UserImages extends ComponentBase
{
    public $images;
    public function componentDetails()
    {
        return [
            'name'        => 'UserImages Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRender()
    {
        $user = Auth::getUser();
        $this->images = [];
        if (is_object($user)) {
            $this->images = ImageModel::usersImages($user->id)->get();
        }
    }
}
