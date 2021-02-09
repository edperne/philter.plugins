<?php namespace Openpolytechnic\Philter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateOpenpolytechnicPhilterImage3 extends Migration
{
    public function up()
    {
        Schema::table('openpolytechnic_philter_image', function($table)
        {
            $table->dropColumn('file_path');
        });
    }
    
    public function down()
    {
        Schema::table('openpolytechnic_philter_image', function($table)
        {
            $table->string('file_path', 191);
        });
    }
}
