<?php namespace Openpolytechnic\Philter;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Openpolytechnic\Philter\Components\RecentImages' => 'RecentImages',
            'Openpolytechnic\Philter\Components\UserImages' => 'UserImages',
            'Openpolytechnic\Philter\Components\AddImage' => 'AddImage',
            'Openpolytechnic\Philter\Components\DeleteImage' => 'DeleteImage'
        ];
    }

    public function registerSettings()
    {
    }
}