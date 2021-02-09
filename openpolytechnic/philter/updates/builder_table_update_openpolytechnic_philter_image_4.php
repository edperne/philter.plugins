<?php namespace Openpolytechnic\Philter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateOpenpolytechnicPhilterImage4 extends Migration
{
    public function up()
    {
        Schema::table('openpolytechnic_philter_image', function($table)
        {
            $table->renameColumn('user_id', 'user');
        });
    }
    
    public function down()
    {
        Schema::table('openpolytechnic_philter_image', function($table)
        {
            $table->renameColumn('user', 'user_id');
        });
    }
}
