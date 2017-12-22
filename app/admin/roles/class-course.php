<?php

namespace BWP_LMS\App\Admin\Roles;

class Course {

	public $map;

	public function init() {
		$this->map = new \BWP_LMS\App\Data\Course_Map();
		// add_action( 'save_posts', array( $this, 'save' ) );
		add_action('admin_equeue_scripts',array($this, 'enqueue_nestable'));
		add_action( 'add_meta_boxes', array($this,'map_meta_box') );
	}

	public function map_meta_box(){
		add_meta_box( 'bwp-lms-course-map', 'Course Map', array( $this, 'display_pathway' ), 'course', 'advanced', 'high' );
	}

	public function display_pathway() {
		?>
		<div class="bwp-lms-pathway bwp-lms-pathway-course">

		</div>
		<input type="hidden" name="pathway_map" class="pw-map" value="<?php echo $this->map->get(); ?>"/>
		<?php
	}
	
	public function save() {
		$this->save_pathway();
	}

	public function save_pathway() {
		$map_json = filter_input( 'post', 'pathway_map' );
		$course_map = new \BWP_LMS\App\Data\Course_Map();
		$course_map->set_from_json( $map_json );
	}

}
