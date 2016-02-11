<?php
function CreationOfMedia(){
	echo'<div>
	<form action="index.php?action=insertmedia" method="post">
		Media Description: <input type="text" name="media" value="" /><br />
		<input type="hidden" name="action" value="insert_media" />
		<input type="submit" value="Submit" />
	<form>';

}


function CreationOfGenre(){
	echo'<div>
	<form action="index.php?action=insert_genre" method="post">
		Genre Abreviation: <input type="text" name="abv" value="" maxlength="5" /><br />
		Genre Description: <input type="text" name="desc" value="" maxlength="255" /><br />
		<input type="hidden" name="action" value="insert_genre" />
		<input type="submit" value="Submit" />
	<form>';
	flatmenu();
}

function CreationOfPublisher(){
	echo'<div>
	<form action="index.php?action=insert_publish" method="post">
		Publisher: <input type="text" name="pub" value="" maxlength="255" /><br />
		<input type="hidden" name="action" value="insert_publish" />
		<input type="submit" value="Submit" />
	<form>';
	
}

function AddConsole(){
	$game_media=GetAllRows('*','games_media');
	$game_publisher=GetAllRows('*','game_publisher');
	echo'<div>
	<form action="index.php?action=insert_console" method="post">
		System name: <input type="text" name="system" value="" maxlength="255" /><br />
		Model #: <input type="text" name="model" value="" maxlength="255" /><br />
		Serial #: <input type="text" name="serial" value="" maxlength="255" /><br />
		Processor: <input type="text" name="proc" value="" maxlength="255" /><br />
		Hertz: <input type="text" name="hertz" value="" maxlength="255" /><br />
		Memory: <input type="text" name="mem" value="" maxlength="255" /><br />
		Size: <input type="text" name="siz" value="" maxlength="255" /><br />
		Power Cord info: <input type="text" name="pwr" value="" maxlength="255" /><br />
		Controller Ports: <input type="text" name="conprt" value="" maxlength="255" /><br />
		Make Year: <input type="text" name="mkyr" value="" maxlength="255" /><br />
		Maker: '.SelectPublisher($game_publisher, "pub").' <br />
		Original Price: <input type="text" name="price" value="" maxlength="255" /><br />
		Media Usage: '.SelectMedia($game_media, "media").' <br />
		Games Save to: '.SelectMedia($game_media, "sg").' <br />
		Other Specs: <input type="text" name="spec" value="" maxlength="255" /><br />
		<input type="hidden" name="action" value="insert_console" />
		<input type="submit" value="Submit" />
	<form>';

}

function AddGame(){
	$game_console=GetAllRows('*','game_console_system');
	$game_genre=GetAllRows('*','game_genre');
	$game_media=GetAllRows('*','games_media');
	$game_publisher=GetAllRows('*','game_publisher');
	echo'<div>
	<form action="index.php?action=insert_game" method="post">
		Game name: <input type="text" name="g_name" value="" maxlength="255" /><br />
		Console: '.SelectConsole($game_console, "console").'<br />
		Release Date: <input type="text" name="date" value="" maxlength="255" /><br />
		Publisher:'.SelectPublisher($game_publisher, "pub").' <br />
		Beaten?: <input type="text" name="beat" value="" maxlength="255" /><br />
		Rank: <input type="text" name="rank" value="" maxlength="20" /><br />
		Genre: '.SelectGenre($game_genre, "genre").'<br />
		Players: <input type="text" name="plr" value="" maxlength="4" /><br />
		Media Type: '.SelectMedia($game_media, "media").' <br />
		Other Info: <input type="text" name="info" value="" maxlength="255" /><br />
		<input type="hidden" name="action" value="insert_game" />
		<input type="submit" value="Submit" />
	<form> <br />';

}


?>