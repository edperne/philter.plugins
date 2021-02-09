<?php namespace Openpolytechnic\Philter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateOpenpolytechnicPhilterImages2 extends Migration
{
    public function up()
    {
        Schema::rename('openpolytechnic_philter_image', 'openpolytechnic_philter_images');
        Schema::table('openpolytechnic_philter_images', function($table)
        {
            $table->string('name')->change();
            $table->string('description')->change();
            $table->string('filter')->change();
        });
    }
    
    public function down()
    {
        Schema::rename('openpolytechnic_philter_images', 'openpolytechnic_philter_image');
        Schema::table('openpolytechnic_philter_image', function($table)
        {
            $table->string('name', 191)->change();
            $table->string('description', 191)->change();
            $table->string('filter', 191)->change();
        });
    }
}
