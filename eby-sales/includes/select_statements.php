<?php
function SelectCondition($name, $select=''){
	$conditions = GetAllRows('code, c_description', '`condition`', "where type='C' order by lvl");
	//$conditions = array(array("P","Perfect"),array("E","Excellent"),array("G","Good"),array("F","Fair"),array("O","Poor"),array("B","Broken/Junk"));
	$output ="<select name=".$name.">";
	foreach($conditions as $cond){
		if($select==$cond['code']){
			$sel='selected';
		}
		else{
			$sel='';
		}
		$output.="<option ".$sel." value='".$cond['code']."'>".$cond['c_description']."</option>";
	}
	$output.="</select>";
	
	return $output;
}
 
function SelectState($name, $select='',$info=''){
	$output ="<select name=".$name.">";
	foreach($info as $state){
		if($select==$state[0]){
			$sel='selected';
		}
		else{
			$sel='';
		}
		$output.="<option ".$sel." value='".$state[0]."'>".$state[1]."</option>";
	}
	$output.="</select>";
	return $output;
}

function SelectYesNo($name, $select=''){
	$output ="<select name=".$name.">";
	if($select==1 || $select=='yes'){
		$output.="<option selected value='1'>Yes</option>
			<option value='0'>No</option>";
	}
	else{
		$output.="<option value='1'>Yes</option>
			<option selected value='0'>No</option>";
	}
	$output.="</select>";
	
	return $output;
}

function SelectLot($info, $name, $select=''){
	$output ="<select name=".$name.">";
	if(empty($select)){
		$output.="<option selected value='0'>Select Lot</option>";
	}
	foreach($info as $row){
		if($select==$row['lot'] && $select!=''){
			$sel='selected';
		}
		else{
			$sel='';
		}
		$output.="<option ".$sel." value='".$row['lot']."'>".$row['lot']." ".$row['item_description']."</option>";
	}
	$output.="</select>";
	
	return $output;
}


?>