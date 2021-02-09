<?php namespace Openpolytechnic\Philter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateOpenpolytechnicPhilterImageTag extends Migration
{
    public function up()
    {
        Schema::create('openpolytechnic_philter_image_tag', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('image_id');
            $table->integer('tag_id');
            $table->primary(['image_id','tag_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('openpolytechnic_philter_image_tag');
    }
}
