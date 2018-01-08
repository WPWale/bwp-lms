<?php
namespace BWP_LMS\App\Roles\Tasks\Questions;

trait Choice{
	
	public $options;
	
	public function result(){
		$result = $this->check();
		
		$this->score($result);
	}
	
	public function check(){
		if(is_array($this->key)){
			return $this->check_single();
		}
		
		return $this->check_multi();
		
	}
	
	public function check_single(){
		if($this->response === $this->key){
			return true;
		}
		return false;
	
	}
	
	public function check_multi(){
		$result = array();
		
		foreach($this->key as $key=>$value){
			$result[$key] = ($this->response[$key] === $value);
		}
		
		return $result;
	}
	
}
