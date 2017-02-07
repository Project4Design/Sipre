<?php
class FormHelper{
	public function Label($for = "", $class = "control-label",$label){
		$label = "<label for=\"".$for."\" class=\"".$class."\">".$label."</label>";
		return $label;
	}

	public function Input($id = "",$class = "form-control", $type, $name, $holder = "", $title = "", $pattern = "", $required = false){
		$input = "<input class=\"".$id."\" class=\"".$class."\" type=\"".$type."\" name=\"".$name."\" placeholder=\"".$holder."\" title=\"".$title."\" pattern=\"".$pattern."\" ".($required ? "required":"").">";
		if($required){
			$input .= "<p class=\"help-block hide\">".$title."</p>";
		}
		return $input;
	}
}
?>