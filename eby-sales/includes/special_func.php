<?php

function insertinto($table, $values){
	if(ModifySql("insert into", $table, $values)){
		return true;
	}
}

function updatetable($table, $values){
	if(ModifySql("update", $table, $values)){
		return true;
	}
}

function calculate_fees($lot,$price, $shipping, $a_shipping){
	$ebay=.09;
	$ebay_ship=.09;
	$paypal=0.029;
	if($price>0){
		$paypal_stnd=.3;
	}
	else{
		$paypal_stnd=0;
	}

	$ebay_fee=$price*$ebay;
	if($ebay_fee>100){
		$ebay_fee=100.00;
	}
	$ebay_ship_fee=$shipping*$ebay_ship;
	$paypal_fee=(($price+$shipping)*$paypal)+$paypal_stnd;
	
	$crd_db=$shipping-$a_shipping;
	if($crd_db>=0){
		$vls="(".$lot.",".$crd_db.",0.00,'Handling fee')";
	}
	else{
		
		$vls="(".$lot.",0.00,".$crd_db*(-1).",'Handling loss')";
	}
	
	$values="(lot,credit, debit,cd_note) value(".$lot.",0.00,".$ebay_fee.",'Ebay fee'),
	(".$lot.",0.00,".$ebay_ship_fee.",'Ebay shipping fee'),(".$lot.",0.00,".$paypal_fee.",'Paypal fee'),".$vls;

	if(insertinto('cre_deb', $values)){
		return true;
	}
	else{
		return false;
	}

}


?>