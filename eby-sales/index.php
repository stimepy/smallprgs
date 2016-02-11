<?php

Fileinc();

if(login()){
	die();
}

Header("title");
//echo $_GET['action'];
if(!isset($_GET['action'])){
	index();
} 
else{
	switch($_GET['action']){
		case "view":
			views();
			break;
		case "add_item":
			add_item();
			break;
		case "add_credit":
			AddCreditDebit();
			break;
		case "submit_item":
			submit_item();
			add_item();
			break;
		case "submit_tran":
			SubmitTran();
			AddCreditDebit();
			break;
		case "item":
			SelectItem();
			break;
		case "add_repair":
			Repair();
			break;
		case "submit_repair":
			SubmitRepair();
			Repair();
			break;
		case "edit_item":
			EditItem($_POST['item']);
			break;
		case "submit_e_item":
			SubmitItemEdit();
			if(isset($_POST['view'])){
				views();
				break;
			}
			SelectItem();
			break;
		case "give_item":
			GiveItemtoItem();
			break;
		case "give":
			GivingItem();
			GiveItemtoItem();
			break;
		default:
			index();
			break;
	}
}

Foot();

function Fileinc(){
	require_once('includes/database.functions.php');
	connect('localhost', 'ebay', 'root',''); //Connect the the database, defaults provided.
	require_once('includes/select_statements.php');
	require_once('includes/special_func.php');
	require_once('includes/X1Cookie.class.php');
	require_once('ebay_updates.php');
	require_once('views.php');
}

function Head($title=''){
echo '<html>
	<head> 
		<title>$title</title>
	</head>
	<body>';
	
}

function Foot(){
	echo '</div></body>
	<div>
	<foot>'.flatmenu().'</foot>
	 </div>
	<html>';
	CloseConnection();
}


function index(){
		echo '<table>
			<a href="./index.php?action=add_item">Add Item</a>|
	<a href="./index.php?action=item">Edit Item</a>|
	<a href="./index.php?action=add_credit">Add Credit/Debit</a>|

	<a href="./index.php?action=give_item">Give items</a>|
	<a href="./index.php?action=add_repair">Repair Area</a>|
	<a href="./index.php?action=view">Views</a>
		</table>
	<div>';
	
	
}

function flatmenu(){
	echo '<br />
	<table>
	|<a href="./index.php?action=add_item">Add Item</a>|
	<a href="./index.php?action=item">Edit Item</a>|
	<a href="./index.php?action=add_credit">Add Credit/Debit</a>|
	<a href="./index.php?action=give_item">Give items</a>|
	<a href="./index.php?action=add_repair">Repair Area</a>|
	<a href="./index.php?action=view">Views</a>|
	</table>';
}


function login(){
	if(!X1Cookie::CheckLogin('ebay_auctions')){
		if(isset($_POST['user']) && isset($_POST['pass'])){
			if(strtolower($_POST['user'])!='admin' || $_POST['pass']!='th3h0us3m0us3'){
				echo 'Nope';
				log_htm();
				return true;
			}
			else
			{
				X1Cookie::SetCookie('ebay_auctions', 'admin', 'kris');
				return false;
			}
		}
		else{
			log_htm();
			return true;
		}
	}
	return false;
}



function log_htm(){
echo '<form action="index.php" method="post" >
		Chicken in the ally:<input type="text" name="user" value="" /><br />
		Drug_lord:<input type="text" name="pass" value="" /><br />
		<input type="submit" value="GO" />
	<form>';
}






?>