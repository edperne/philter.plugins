<?php namespace Openpolytechnic\Philter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateOpenpolytechnicPhilterImage5 extends Migration
{
    public function up()
    {
        Schema::table('openpolytechnic_philter_image', function($table)
        {
            $table->renameColumn('user', 'user_id');
        });
    }
    
    public function down()
    {
        Schema::table('openpolytechnic_philter_image', function($table)
        {
            $table->renameColumn('user_id', 'user');
        });
    }
}
