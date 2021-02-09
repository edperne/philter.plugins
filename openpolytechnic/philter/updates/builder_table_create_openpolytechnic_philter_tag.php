<?php namespace Openpolytechnic\Philter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateOpenpolytechnicPhilterTag extends Migration
{
    public function up()
    {
        Schema::create('openpolytechnic_philter_tag', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('tag');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('openpolytechnic_philter_tag');
    }
}
