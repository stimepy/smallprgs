<?php

function views(){
	if(empty($_GET['view'])){
		view_index();
		return;
	}
	
	switch($_GET['view']){
		case 'all':
			main_view('item.*, `condition`.c_description as descript', " inner join `condition` on code=i_state and type='S'",'', 'all');
			break;
		case 'sold':
			main_view('item.*, `condition`.c_description as descript', " inner join `condition` on code=i_state and type='S'"," i_state='D'", 'all');
			break;
		case 'sale':
			main_view('item.*, `condition`.c_description as descript', " inner join `condition` on code=i_state and type='S'"," i_state in('A','S')", 'all');
			break;
		case 'parted':
			main_view('item.*, `condition`.c_description as descript', " inner join `condition` on code=i_state and type='S'"," i_state in('P')", 'all');
			break;
		case 'unsold':
			main_view('item.*, `condition`.c_description as descript', " inner join `condition` on code=i_state and type='S'"," i_state in('N')", 'all');
			break;
		case 'nonsel':
			main_view('item.*, `condition`.c_description as descript', " inner join `condition` on code=i_state and type='S'"," i_state in('I')", 'all');
			break;
		case 'totals':
			math_view('total');
			break;
		case 'detail':	
			details($_POST['item']);
			break;
	}
}




function view_index(){
	$output ='<table border="1">
		<tr>
			<td>
				<form action="index.php?action=view&view=all" method="post" >
					<input type="hidden" name="view" value="all" />
					<input type="submit" value="all" />
				</form>
			</td>
			<td>
				<form action="index.php?action=view&view=sale" method="post" >
					<input type="hidden" name="view" value="sale" />
					<input type="submit" value="for sale" />
				</form>
				<form action="index.php?action=view&view=sold" method="post" >
					<input type="hidden" name="view" value="sold" />
					<input type="submit" value="sold" />
				</form>
			</td>
			<td>
				<form action="index.php?action=view&view=unsold" method="post" >
					<input type="hidden" name="view" value="unsold" />
					<input type="submit" value="Unsold" />
				</form>
				<form action="index.php?action=view&view=parted" method="post" >
					<input type="hidden" name="view" value="parted" />
					<input type="submit" value="Parted" />
				</form>
				<form action="index.php?action=view&view=nonsel" method="post" >
					<input type="hidden" name="view" value="nonsel" />
					<input type="submit" value="Nonsellable inventory" />
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<form action="index.php?action=view&view=totals" method="post" >
					<input type="hidden" name="view" value="totals" />
					<input type="submit" value="totals" />
				</form>
			</td>
		</tr>
	</table>';
	
	echo $output;	
}

function math_view($what){
	if($what=='total'){
		$items=GetOneRow( 'sum(sold_price-(cost+c_shipping)+IFNULL(a.cred_deb,0)) as total, sum(cost+c_shipping) as total_purchase, sum(sold_price) as total_sales, sum(IFNULL(a.cred_deb,0)) as crd', 'item left outer join (select lot, sum(credit-debit) as cred_deb from cre_deb group by lot)a on item.lot=a.lot');
		$output = '<table border="1">
			<thead>
			</thead>
			<tbody>
				<tr>
					<td>
						Total Sales
					</td>
					<td>
						$'.$items['total_sales'].'
					</td>
				</tr>
				<tr>
					<td>
						Total purchased
					</td>
					<td>
						$'.$items['total_purchase'].'
					</td>
				</tr>
				<tr>
					<td>
						Total Credit/Debit
					</td>
					<td>
						$'.$items['crd'].'
					</td>
				</tr>
				<tr>
					<td>
						Total
					</td>
					<td>
						$'.$items['total'].'
					</td>
				</tr>
			</tbody>
		</table>';
	}
			
	
	echo $output;
}

function main_view($select, $tables='',$conditions='', $view='all', $return=false){
	if(empty($conditions) && empty($tables)){
		$items=GetAllRows($select, 'item', 'order by lvl');
	}
	else{
		if(!empty($conditions)){
			$conditions=' where '.$conditions;
		}
		$items=GetAllRows($select, 'item '.$tables, $conditions.' order by lvl');
	}
	
	$output = '<table border="1">
		<thead>
			<tr>
				<th>lot</th>
				<th>Description</th>
				<th>State</th>
				<th>Orig Cost</th>
				<th>Sold for</th>
				<th>Paid for</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>';
		 
	foreach($items as $item){
		$output.="<tr>
				<td>
					".$item['lot']."
				</td>
				<td>
					".$item['item_description']."
				</td>
				<td>
					".$item['descript']."
				</td>
				<td>
					".($item['cost']+$item['c_shipping'])."
				</td>
				<td>
					".$item['sold_price']."
				</td>
				<td>
					".yesno($item['paid'])."
				</td>
				<td>
					<form action='index.php?action=edit_item&lot=".$item['lot']."' method='post'>
						<input type='hidden' name='view' value='".$view."' />
						<input type='hidden' name='item' value='".$item['lot']."' />
						<input type='submit' value='edit' />
					</form>
				</td>
				<td>
					<form action='index.php?action=view&view=detail&lot=".$item['lot']."' method='post'>
						<input type='hidden' name='item' value='".$item['lot']."' />
						<input type='submit' value='details' />
					</form>
				</td>
			</tr>";
	}
	$output .="</tbody>
	<tfoot>
		
	</tfoot>
	</table>";
	if(!$return){
		echo $output;
	}
	else{
		return $output;
	}
}

function details($lot){
	$sum_crdb=0;
	$sum_of_fees;

	
	
	$item=GetOneRow("*, state.c_description as state, condi.c_description as conditio", 
	"item inner join (select code, c_description from `condition` where type='S')state on(item.i_state=state.code) inner join (select code, c_description from `condition` where type='C')condi on(item.i_condition=condi.code)" , 
	"where lot=".$lot);

	/*$fees=GetAllRows('*', 'cre_deb', "where lot=".$lot." and cd_note in('Paypal fee', 'Ebay fee', 'Ebay shipping fee','Handling fee', 'Handling loss' )");*/
	$cred_db=GetAllRows('*', 'cre_deb', "where lot=".$lot);
		/* 	$item['list_date'] 	$item['i_note'] 		$item['ebay_str_cost'] 	$item['case_open'] 	$item['repair_needed']  	$item['part_of']  */
	$output = "<table>
		<tr>
		<td>
		<table border='1'>
		<tr>
			<td>
				Lot #
			</td>
			<td>
				".$item['lot']."
			</td>
		</tr>
		<tr>
			<td>
				Item Desciption
			</td>
			<td>
				".$item['item_description']."
			</td>
		</tr>
		<tr>
			<td>
				Current Condition
			</td>
			<td>
				".$item['conditio']."
			</td>
		</tr>
		<tr>
			<td>
				Current State
			</td>
			<td>
				".$item['state']."
			</td>
		</tr>
		<tr>
			<td>
				Item initial Cost
			</td>
			<td>
				".$item['cost']."
			</td>
		</tr>
		<tr>
			<td>
				Item Initial Shipping cost
			</td>
			<td>
				".$item['c_shipping']."
			</td>
		</tr>
		<tr>
			<td>
				Initial Quanity
			</td>
			<td>
				".$item['quanity']."
			</td>
		</tr>";
		if($item['quanity']>1){
		$output.="<tr>
			<td>
				Quanity Sold:
			</td>
			<td>
				".$item['sold_quanity']."
			</td>
		</tr>";
			}
		$output.="<tr>
			<td>
				Has Item Sold?
			</td>
			<td>
				".yesno($item['sold'])."
			</td>
		</tr>
		</tr>
			<td>
				Price sold @
			</td>
			<td>
				$".$item['sold_price'] ."
			</td>
		</tr>
		</tr>
			<td>
				Shipping via ebay
			</td>
			<td>
				".$item['s_shipping']."
			</td>
		</tr>
		</tr>
			<td>
				Actual shipping cost
			</td>
			<td>
				".$item['actual_shipping']."
			</td>
		</tr>
		</tr>
			<td>
				Item Paid for
			</td>
			<td>
				".yesno($item['paid'])."
			</td>
		</tr>
		</tr>
			<td>
				Item in need of repair?
			</td>
			<td>
				".yesno($item['repair_needed']) ."
			</td>
		</tr>
		</table>";
		if($item['part_of']!=0){
			$output .="Part of <br />".main_view('item.*, `condition`.c_description as descript', " inner join `condition` on code=i_state and type='S'","lot='".$item['part_of']."'", '', true);
		}
		
		if($cred_db[0]!=NULL){
			$output.= CreDebView(true, $item['lot'],$cred_db, $sum_crdb,true);
			$fees=get_fees($cred_db);
			
			$handling_fee=isset($fees['Handling fee'])?$fees['Handling fee'][0]:(-1*$fees['Handling loss'][0]);
			
			$fee_info="<tr>
				<td>
					Ebay Sell Fee
				</td>
				<td>
					$".$fees['Ebay fee'][0]."
				</td>
			</tr>
			<tr>
				<td>
					Ebay Shipping Fee
				</td>
				<td>
					$".$fees['Ebay shipping fee'][0]."
				</td>
			</tr>
			<tr>
				<td>
					Paypal Fee
				</td>
				<td>
					$".$fees['Paypal fee'][0]."
				</td>
			</tr>
			<tr>
				<td>
					Handling Fee/Debit
				</td>
				<td>
					$".$handling_fee."
				</td>
			</tr>
			<tr>
				<td>
					Other Credit/Debit
				</td>
				<td>
					$".number_format($sum_crdb+($fees['Ebay shipping fee'][0]+$fees['Paypal fee'][0]+$fees['Ebay fee'][0])+($handling_fee*-1),2)."
				</td>
			</tr>";
			
			//$sum=$sum+$fees['Paypal fee'][0]+$fees['Ebay fee'][0]+$fees['Ebay shipping fee'][0]+$handling_fee;
		}

		$total=($item['sold_price']+$sum_crdb-($item['cost']+$item['c_shipping']+$item['ebay_str_cost']))<.01?'0.00':($item['sold_price']+$sum_crdb-($item['cost']+$item['c_shipping']+$item['ebay_str_cost']));
		$output .="Total Profit/Loss for lot<br />
		<table border='1'>
			<tr>
				<td>
					Total Cost
				</td>
				<td>
					$".($item['cost']+$item['c_shipping'])."
				</td>
			</tr>
			<tr>
				<td>
					Ebay list Fee
				</td>
				<td>
					$".$item['ebay_str_cost']."
				</td>
			</tr>";
				//$output .= $c;
			if(isset($fee_info)){
				$output.= $fee_info;
			}
			$output .="<tr>
				<td>
					Sold price
				</td>
				<td>
					$".$item['sold_price']."
				</td>
			</tr>
			<tr>
				<td>
					Total
				</td>
				<td>
					$".number_format($item['sold_price']+$sum_crdb-($item['cost']+$item['c_shipping']+$item['ebay_str_cost']),2)."
				</td>
			</tr>
		</table>
		</td>
		</tr>
	</table>";
			
				
	echo $output;
}


function get_fees($data){
	$count=0;
	$found=false;
	$length=count($data);
	for($length; $length>$count; $count++){
	//	echo $count;
		$test=$data[$count]['cd_note'];
		if($test=='Paypal fee'||$test=='Ebay fee'||$test=='Ebay shipping fee'||$test=='Handling fee'||$test=='Handling loss'){
			switch($test){
				case 'Paypal fee':
					$fees[$test][]=$data[$count]['debit'];
					break;
				case 'Ebay fee':
					$fees[$test][]=$data[$count]['debit'];
					break;
				case 'Ebay shipping fee':
					$fees[$test][]=$data[$count]['debit'];
					break;
				case 'Handling fee':
					$fees[$test][]=$data[$count]['credit'];
					break;
				case 'Handling loss':
					$fees[$test][]=$data[$count]['debit'];
					break;
			}
			$found=true;
		}
	}
	if(!$found){
		$fees['Paypal fee'][0]=0.00;
		$fees['Ebay fee'][0]=0.00;
		$fees['Ebay shipping fee'][0]=0.00;
		$fees['Handling fee'][0]=0.00;
	}
	return $fees;
	
	
}


function CreDebView($is_data=false, $lot=-1, $data='', &$sum_of=0, $return=false){
	if($lot==-1){
		echo "error on lot#.";
		return false;
	}
	
	$summation_cr_db=0;
	$all_cr=0;
	$all_db=0;
	
	if(!$is_data){
		$data=GetAllRows('*','cre_deb',' where lot='.$lot);
	}
	$output = "Credit/Debit table<br />
	<table border='1'>
		<thead>
			<th>Transaction #</th>
			<th>Credit</th>
			<th>Debit</th>
			<th>Reason</th>
		</thead>
		<tbody>";
						
		foreach($data as $cr_db){
			$output .="<tr>
				<td>
					".$cr_db['cd_trans_num']."
				</td>
				<td>
					".$cr_db['credit']."
				</td>
				<td>
					".$cr_db['debit']."
				</td>
				<td>
					".$cr_db['cd_note']."
				</td>
			</tr>";
			$all_cr=$all_cr+$cr_db['credit'];
			$all_db=$all_db+$cr_db['debit'];
			$summation_cr_db=$summation_cr_db+($cr_db['credit']-$cr_db['debit']);
		}
		$output .="</tbody>
		<tfoot>
			<tr>
				<td>
				</td>
				<td>
					All Credit= $".$all_cr."
				</td>
				<td>
					All Debit= $".$all_db."
				</td>
				<td>
					Over all Total= $".$summation_cr_db."
				</td>
			</tr>
		</tfoot>
	</table>";
	$sum_of=$summation_cr_db;
	
	if(!$return){
		echo $output;
	}
	else{
		return $output;
	}
}

function yesno($question){
	if($question==1||strtolower($question)=='yes'){
		return "Yes";
	}
	else{
		return "No";
	}
}

?>