<?php namespace Openpolytechnic\Philter\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Images extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Openpolytechnic.Philter', 'main-menu-item');
    }
    //added by Lisa P.
        /**
     * Attaches our column select statements to the query. Unfortunately, this
     * can't be done from listExtendQueryBefore. Select statements that are
     * added before processing the query will be removed by the behavior.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function listExtendQuery($query)
    {
        $query
            ->with('image','image');
    }    
}
