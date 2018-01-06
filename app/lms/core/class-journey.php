<?php
/**
 * 
 */

namespace BWP_LMS\App\LMS\Core;

use BWP_LMS\App\LMS as LMS;

use BWP_LMS\App\Data as Data;

use BWP_LMS\App\Roles as Roles;


/**
 * 
 */
class Journey {
	
	public $route = false;
	
	public $map = false;
	
	public $map_utils;
	
	use Roles\Journey\Position;
	use Roles\Journey\Visibility;
	use Roles\Journey\Progress;
	
	function __construct($path_id, $user = false) {
		
		$this->map_utils = new LMS\Utilities\Map();
		
		$this->stati = bwp_lms()->stati;
		
		$this->populate($path_id, $user);
		
		$this->set_position();
	}
	
	private function populate($path_id, $user){
		
		$path = new Data\Pathway();
		
		$role = ( int ) array_search( $user->persona, bwp_lms()->registered_roles );
		
		$path_route = $path->get_route($path_id, $role );
		
		if(!$path_route){
			return false;
		}
		
		$this->setup_map($path_id, $path_route, $user);
	}
	
	private function setup_map($path_id, $path_route, $user){
		
		$journey = new Data\Journey($path_id, $user);
		
		$journey_route = $journey->get_route();
		
		$merged_steps = $this->map_utils->merge_recursive($path_route, $journey_route);
		
		$this->route = $this->order($merged_steps);
		
		$map = $this->map_utils->map_from_route($merged_steps);
		
		$this->map = $this->order($map);

	}
	
	private function order($map){
		$counter = 0;
		
		return $this->map_utils->order_recursive($map, &$counter);
	}
	
	
	
	public function refresh(){
		
		// save map to & re-retreive from db
		
	}
	
	public function html($link = false){
		$map = $this->map;
		$html = '<ul>';
		$html .= $this->html_recursive($map, $link);
		$html .= '<ul>';
		
		return $html;
	}
	
	public function html_recursive($map, $link = false){
		
		$html = '';
		foreach($map as $unit){
			$current = ($this->current['unit_id'] === $unit['unit_id'])? ' current': '';
			$allowed = ($unit['allowed'])?' allowed':'';
			$html .= '<li class="'.$unit['type'].$current.$allowed.'" title="'.$unit['unit_title'].'">';
			if($link!==false){
				$html .= '<a href="'. get_permalink( $unit['unit_id']).'" alt="'.$unit['unit_title'].'">';
			}
			$html .= '<span>'.$unit['name'].'</span>';
			if($link!==false){
				$html .= '</a>';
			}
			if(!empty($unit['children'])){
				$html .= '<ul>';
				$html .= $this->html_recursive($unit['children'], $link);
				$html .= '<ul>';
			}else{
				$html .= '</li>';
			}
		}
		
	}
	
	public function unit_nav(){
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h2 class="screen-reader-text">Path Navigation</h2>
			<ul class="nav-links">
					<?php echo $this->adjacent_unit_link(); ?>

					<?php echo $this->adjacent_unit_link(false); ?>
			</ul>
		</nav>
		<?php
	}
	
	public function adjacent_unit_link($previous=true){
		$rel = $previous ? 'prev' : 'next';
		$class_sfx = $previous? 'previous': 'next';
		$class = 'nav-'.$class_sfx;
		$unit = $previous? $this->previous: $this->next;
		
		if(!$unit){
			return '';
		}
		
		$link = '<li class="'.$class.'">';
		$link .= '<a href="' . get_permalink( $unit['unit_id'] ) . '" rel="'.$rel.'">';
		$link .= $unit['unit_title'];
		$link .= '</a>';
		$link .= '</li>';
		return $link;
	}	
}