<?php
function add_item(){
	$item=GetItems("'D','I', 'N'",'lot, item_description', " and part_of='0'");
	echo'<div>
	<form action="index.php?action=submit_item" method="post" >
		Check for non-sellable: <input type="checkbox" name="sella" value="checked" /><br />
		Description :<input type="text" name="desc" value="" /><br />
		From Lot: '.SelectLot($item, "item").'<br />
		Cost: $<input type="text" name="cost" value="0.00" /><br />
		Shipping: $<input type="text" name="ship" value="0.00" /><br />
		Quanity:<input type="text" name="quanity" value="1" /> <br />
		Current Condition:'.SelectCondition("condition").'<br />
		Repairs needed:'.SelectYesNo("repair", 1).'<br />
		Special Notes: <input type="text" name="notes" value="" /><br />
		<input type="hidden" name="action" value="submit_item" />
		<input type="submit" value="Submit" />
	<form>';

} 
function submit_item(){
	$lots=FileWiz("lots.dat","r");
	$lots=explode ( ',', $lots );
	if(empty($_POST['sella'])){
		$lots[0]=($lots[0]+1);
		$lot=$lots[0];
		$condit='W';
	}
	else{
		$lots[1]=($lots[1]+1);
		$lot="A".$lots[1];
		$condit='I';
	}
	$lots=implode ( ',', $lots );
	if(FileWiz("lots.dat","w", $lots)){
		unset($lots);
	}
	if(empty($_POST['desc']) || empty($_POST['cost']) || empty($_POST['ship']) || empty($_POST['notes'])){
		if(empty($_POST['desc'])){
			echo "No Description.";
			return false;
		}
		if(empty($_POST['cost'])){
			$_POST['cost']=0.00;
		}
		if(empty($_POST['ship'])){
			$_POST['ship']=0.00;
		}
		if(empty($_POST['notes'])){
			$_POST['notes']="N/A";
		}
	}
	
	$values="(`lot`, `item_description`, `cost`, `c_shipping`,
	`i_condition`, `repair_needed`, `i_note`, `i_state`, `part_of`, `quanity`)
	values('".$lot."',
	'".$_POST['desc']."',
	'".$_POST['cost']."',
	'".$_POST['ship']."',
	'".$_POST['condition']."',
	'".$_POST['repair']."',
	'".$_POST['notes']."',
	'".$condit."',
	'".$_POST['item']."',
	'".$_POST['quanity']."')";
	
	if(insertinto('item', $values)){
		echo 'Success!';
		return true;
	}
	else{
		$lots=FileWiz("lots.dat","r");
		$lots=explode ( ',', $lots );
		if(empty($_POST['sella'])){
			$lots[0]=($lots[0]-1);
			$lot=$lots[0];
		}
		else{
			$lots[1]=($lots[1]-1);
			$lot="A".$lots[1];
		}
		$lots=implode ( ',', $lots );
		if(FileWiz("lots.dat","w", $lots)){
			unset($lots);
		}
		return false;
	}
	
}

function SelectItem($action="edit_item"){
	$item=GetItems("'D','I','P'");
	echo '<form action="index.php?action='.$action.'" method="post" >
		Item: '.SelectLot($item, "item").'
		<input type="hidden" name="action" value="'.$action.'" />
		<input type="submit" value="Submit" />
	</form>';
}
function EditItem($id='', $action="submit_e_item"){
	if(empty($id)){
		
	}
	else{
		$item=GetOneRow('*','item', "where lot='".$id."'");
	}
	
	$state=GetAllRows('code, c_description','`condition`', "where type in('S')");
		
	$output= '<form action="index.php?action='.$action.'" method="post" >
		Lot #: <input type="text" readonly="readonly" name="lot" value="'.$item['lot'].'" /><br />
		Description :<input type="text" name="desc" value="'.$item['item_description'].'" /><br />
		Cost: $<input type="text" name="cost" value="'.$item['cost'].'" /><br />
		Shipping: $<input type="text" name="ship" value="'.$item['c_shipping'].'" /><br />
		Listing Date: <input type="date" name="date" value="'.$item['list_date'].'" /> <br />
		Paid for?:'.SelectYesNo("paid",$item['paid']).' <br />
		<input type="hidden" name="pd_old" value="'.$item['paid'].'" />
		Auction/Buy it now start fee:<input type="text" name="e_init" value="'.$item['ebay_str_cost'].'" /> <br />
		Sold for: <input type="text" name="sold" value="'.$item['sold_price'].'" /> <br />
		ebay Shipping cost: <input type="text" name="shipping" value="'.$item['s_shipping'].'" /> <br />
		Actual Shipping cost: <input type="text" name="a_shipping" value="'.$item['actual_shipping'].'" /> <br />
		Quanity avaliable:<input type="text" name="quanity" value="'.$item['quanity'].'" /> <br />
		Quanity sold:<input type="text" name="q_sold" value="'.$item['sold_quanity'].'" /> <br />
		Current Condition:'.SelectCondition("condition", $item['i_condition']).'<br />
		Repairs needed:'.SelectYesNo("repair", $item['repair_needed']).'<br />
		Current State: '.SelectState("state",$item['i_state'],$state).'<br />
		<input type="hidden" name="state_old" value="'.$item['i_state'].'" />
		Special Notes: <input type="text" name="notes" value="'.$item['i_note'].'" /><br />
		<input type="hidden" name="action" value="'.$action.'" />';
		if(isset($_POST['view'])){
			$output .='<input type="hidden" name="view" value="'.$_POST['view'].'">';
		}
		$output .='<input type="submit" value="Submit" />
	<form>';
	
	echo $output;
}

function SubmitItemEdit(){
	if($_POST['sold']>0 && ($_POST['quanity']<=$_POST['q_sold'])){
		if($_POST['condition']!='D'){
			$output="Please note items state is not in sold.";
			echo $output;
		}
	}
	if($_POST['paid']==1 && $_POST['paid']!=$_POST['pd_old']){
		if($_POST['state_old']=='S'){
			$_POST['sold']=$_POST['sold']*$_POST['q_sold'];
			$_POST['shipping']=$_POST['shipping']*$_POST['q_sold'];
			$_POST['a_shipping']=$_POST['a_shipping']*$_POST['q_sold'];
		}
		if(!calculate_fees($_POST['lot'],$_POST['sold'],$_POST['shipping'], $_POST['a_shipping'])){
			echo "Error";
			return false;
		}
	}
	
	$value="set item_description='".$_POST['desc']."',
	cost='".$_POST['cost']."', c_shipping='".$_POST['ship']."',
	list_date='".$_POST['date']."',	sold_price='".$_POST['sold']."', 
	s_shipping='".$_POST['shipping']."', quanity='".$_POST['quanity']."', 
	sold_quanity='".$_POST['q_sold']."', i_condition='".$_POST['condition']."',
	actual_shipping='".$_POST['a_shipping']."', repair_needed='".$_POST['repair']."', 
	i_state='".$_POST['state']."', paid='".$_POST['paid']."', ebay_str_cost='".$_POST['e_init']."' where lot='".$_POST['lot']."'";
	
	echo $value;
	updatetable('item',$value);
	
}

function AddCreditDebit(){
	$items=GetItems("'W'");//"'D','W','I'"); //I?,
	echo'<div>
	<form action="index.php?action=submit_tran" method="post">
		Lot: '.SelectLot($items,"lot").'<br />
		Credit: <input type="text" name="crd" value="0.00" maxlength="255" /><br />
		Debit: <input type="text" name="dbt" value="0.00" maxlength="255" /><br />
		Note:  <input type="text" name="note" value="" /><br />
		<input type="hidden" name="action" value="submit_tran" />
		<input type="submit" value="Submit" />
	<form>';
	flatmenu();
}


function SubmitTran(){
	if(empty($_POST['crd']) || empty($_POST['dbt']) || empty($_POST['note']) || empty($_POST['quanity'])){
		if(empty($_POST['note'])){
			echo "a Note is required.";
			return;
		}
		if(empty($_POST['crd'])){
			$_POST=0.00;
		}
		if(empty($_POST['dbt'])){
			$_POST=0.00;
		}
	}
	$values ="(`lot`, `credit`, `debit`, `cd_note`)
	values('".$_POST['lot']."',
	'".$_POST['crd']."',
	'".$_POST['dbt']."',
	'".$_POST['note']."')";
	if(insertinto('cre_deb', $values)){
		echo "success!";
	}
}

function Repair(){
	$items=GetItems("'D','W','I','P'");
	echo'<div>
	<form action="index.php?action=submit_repair" method="post">
		Lot: '.SelectLot($items,"lot").'<br />
		Part: <input type="text" name="part" value="" maxlength="255" /><br />
		Part Cost: <input type="text" name="deb" value="0.00" maxlength="255" /> <br />
		Repair Done?: '.SelectYesNo("repair", 0).'<br />
		<input type="hidden" name="action" value="submit_repair" />
		<input type="submit" value="Submit" />
	<form>';
}

function SubmitRepair($edit=false){
	$values="(lot,part_number,repair_done) values('".$_POST['lot']."',
	'".$_POST['part']."',
	'".$_POST['repair']."')";
	
	if($_POST['deb']>0){
		$lot_count=count_totals("*", "repair", "where lot=".$_POST['lot'] );
		$values2="(lot,debit,cd_note) values('".$_POST['lot']."',
		'".$_POST['deb']."',
		'".$_POST['lot'].":".($lot_count+1).":Repair part')";
		if(!insertinto('cre_deb', $values2)){
			echo "error";
			return;
		}
	}
	if(insertinto('repair', $values)){
		echo "success!";
	}
}


function GiveItemtoItem(){
$items=GetItems($not_in="'W'");//,'W','P','I','N'");
	echo '<form action="index.php?action=give" method="post">
		Lot to give: '.SelectLot($items,"give").'<br />
		Lot to receive: '.SelectLot($items,"receive").'<br />
		Quanity:<input type="text" name="quanity" value="0" /><br />
		<input type="hidden" name="action" value="give" />
		<input type="submit" value="Submit" />
	<form>';
	return;
}

function GivingItem(){
	$giver=$_POST['give'];
	$receiver=$_POST['receive'];
	if($_POST['quanity']>=1){
		$select="((a.cost+a.c_shipping)/a.quanity)*".$_POST['quanity']."-IFNULL(e.total,0) as totals ";
		$from="item a left outer join (select sum(credit-debit) as total, lot from cre_deb where lot in('193') and locate('Giving lot(193) to lot(', cd_note)=0 group by lot)e on(a.lot=e.lot)";
		updatetable('item', 'set sold_quanity=sold_quanity+'.$_POST['quanity']);
	}
	else{
		$select="a.cost+a.c_shipping-IFNULL(e.total,0) as totals";
		$from="item a left outer join (select sum(credit-debit) as total, lot 
			from cre_deb where lot in('".$giver."') group by lot) e on(a.lot=e.lot)";
	}
	$item_cost_g=GetOneRow($select, $from, "where a.lot in('".$giver."')");
	
//echo $item_cost_g['totals']."<br />";
	
	if($item_cost_g['totals']>0){
		$credit1=$debit2=0;
		$debit1=$credit2=$item_cost_g['totals'];
	}
	elseif($item_cost_g['totals']<0){
		$debit1=$credit2=0;
		$credit1=$debit2=(-1*$item_cost_b['totals']);
	}
	else{//=0
		$credit1=$debit1=$credit2=$debit2=0;
	}
	
	$value="(lot, credit,debit,cd_note) values('".$receiver."',
	".$credit1.",".$debit1.",'Receiveed lot to(".$receiver.") from lot(".$giver.")'),
	('".$giver."',".$credit2.",".$debit2.",'Giving lot(".$giver.") to lot(".$receiver.")')";
echo $value."<br />";

	if(insertinto('cre_deb', $value)){
		echo "Success!";
	}
	
	//$values2=set i_state=
}


function GetItems($not_in="'D','W'", $what='lot, item_description', $other=''){
	return GetAllRows($what,'item', "where i_state not in(".$not_in.")".$other);
}

function FileWiz($file, $mode="r", $contents=''){
	$myfile = fopen($file, $mode);
	switch($mode){
		case "r":
			$contents = fread($myfile, filesize($file));
			break;
		case "w":
			$contents = fwrite ($myfile ,$contents);
			break;
			
	}
	fclose($myfile);
	return $contents;
}



?>