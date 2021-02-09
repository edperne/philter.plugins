<?php namespace Openpolytechnic\Philter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateOpenpolytechnicPhilterImages extends Migration
{
    public function up()
    {
        Schema::create('openpolytechnic_philter_images', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('openpolytechnic_philter_images');
    }
}
