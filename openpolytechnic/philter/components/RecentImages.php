<?php namespace Openpolytechnic\Philter\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Openpolytechnic\Philter\Models\Image as ImageModel;

class RecentImages extends ComponentBase
{
    public $images; 

    public function componentDetails()
    {
        return [
            'name'        => 'Recent Images Index Component',
            'description' => 'Handles recent image display.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRender()
    {
        $user = Auth::getUser();
        if (is_object($user)) {
            $this->images = ImageModel::othersImages($user->id)->latest()->get();
        } else {
            $this->images = ImageModel::with('tags')->latest()->get();
        }
    }
}
