<?php

// Stvaramo tablice u bazi (ako već ne postoje od ranije).
require_once __DIR__ . '/db.class.php';

create_table_users();
create_table_restaurants();
create_table_food();
create_table_food_type();
create_table_orders();
create_table_contains();
create_table_has_food_type();
create_table_image();
create_table_deliverers();
create_table_neighborhood();

exit( 0 );

// --------------------------
function has_table( $tblname )
{
	$db = DB::getConnection();
	
	try
	{
		$st = $db->prepare( 
			'SHOW TABLES LIKE :tblname'
		);

		$st->execute( array( 'tblname' => $tblname ) );
		if( $st->rowCount() > 0 )
			return true;
	}
	catch( PDOException $e ) { exit( "PDO error [show tables]: " . $e->getMessage() ); }

	return false;
}


function create_table_users()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_users' ) )
		exit( 'Tablica spiza_users vec postoji. Obrisite ju pa probajte ponovno.' );


	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_users (' .
			'id_user int NOT NULL PRIMARY KEY AUTO_INCREMENT,'.
			'username varchar(50) NOT NULL,'.
			'password_hash varchar(255) NOT NULL,'.
			'email varchar(50) NOT NULL,'.
			'address varchar(80) NOT NULL,'.
			'registration_sequence varchar(20) NOT NULL,'.
			'has_registered int)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_users]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_users.<br />";
}


function create_table_restaurants()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_restaurants' ) )
		exit( 'Tablica spiza_restaurants vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_restaurants (' .
			'id_restaurant int NOT NULL PRIMARY KEY AUTO_INCREMENT,'.
			'username varchar(50) NOT NULL,'.
			'password_hash varchar(255) NOT NULL,'.
			'email varchar(50) NOT NULL,'.
			'registration_sequence varchar(20) NOT NULL,'.
			'has_registered int,'.
			'name varchar(50) NOT NULL,'.
			'address varchar(100) NOT NULL,'.
			'description varchar(200) NOT NULL)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_restaurants]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_restaurants.<br />";
}


function create_table_food()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_food' ) )
		exit( 'Tablica spiza_food vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_food (' .
			'id_food int NOT NULL PRIMARY KEY AUTO_INCREMENT,'.
			'name varchar(50) NOT NULL,'.
			'description varchar(200) NOT NULL,'.
			'waiting_time int NOT NULL,'.
			'price decimal(6,2) NOT NULL,'.
			'in_offering tinyint NOT NULL,'.
			'id_restaurant int NOT NULL,'.
			'image_path varchar(200)'.
			//'FOREIGN KEY (id_restaurant) REFERENCES spiza_restaurants(id_restaurant)'.
			')'		
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_food]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_food.<br />";
}

function create_table_food_type()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_food_type' ) )
		exit( 'Tablica spiza_food_type vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_food_type (' .
			'id_foodType int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'name varchar(30) NOT NULL,'.
			'image_path varchar(200))'		
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_food_type]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_food_type.<br />";
}

function create_table_orders()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_orders' ) )
		exit( 'Tablica spiza_orders vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_orders (' .
			'id_order int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'id_user int NOT NULL,' .
			'id_restaurant int NOT NULL,' .
			'id_deliverer int,' .
			'active tinyint NOT NULL,' .
			'order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,' .
			'delivery_time TIMESTAMP NULL,' .
			'lastchange_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,' .
			'price_total float,' .
			'discount float,' .
			'note varchar(100),' .
			'address varchar(80) NOT NULL,'.
			'feedback varchar(500),' .
			'rating float,' .
			'thumbs_up int,' .
			'thumbs_down int' .
			//'FOREIGN KEY (id_restaurant) REFERENCES spiza_restaurants(id_restaurant),' .
			//'FOREIGN KEY (id_user) REFERENCES spiza_users(id_user).
			')'		
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_orders]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_orders.<br />";
}

function create_table_contains()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_contains' ) )
		exit( 'Tablica spiza_contains vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_contains (' .
			'id_order int NOT NULL,' .
			'id_food int NOT NULL,' .
			'quantity int NOT NULL,' .
			'PRIMARY KEY (id_order, id_food)' .
			//'FOREIGN KEY (id_order) REFERENCES spiza_orders(id_order),' .
			//'FOREIGN KEY (id_food) REFERENCES spiza_food(id_food).
			')'		
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_contains]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_contains.<br />";
}

function create_table_has_food_type()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_has_food_type' ) )
		exit( 'Tablica spiza_has_food_type vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_has_food_type (' .
			'id_foodType int NOT NULL,' .
			'id_restaurant int NOT NULL,' .
			'PRIMARY KEY (id_foodType, id_restaurant)'.
			//'FOREIGN KEY (id_restaurant) REFERENCES spiza_restaurants(id_restaurant),' .
			//'FOREIGN KEY (id_foodType) REFERENCES spiza_food_type(id_foodType)'.
			')'		
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_has_food_type]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_has_food_type.<br />";
}
function create_table_image()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_image' ) )
		exit( 'Tablica spiza_image vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_image (' .
			'id_image int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'name varchar(200) NOT NULL,' .
			'image longtext,' .
			'id_restaurant int'.
			//'FOREIGN KEY (id_restaurant) REFERENCES spiza_restaurants(id_restaurant)' .
			')'		
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_image]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_image.<br />";
}

function create_table_deliverers()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_deliverers' ) )
		exit( 'Tablica spiza_deliverers vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_deliverers (' .
			'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'username varchar(50) NOT NULL,' .
			'password_hash varchar(255) NOT NULL,' .
			'email varchar(50) NOT NULL,' .
			'registration_sequence varchar(20) NOT NULL,' .
			'has_registered int)'		
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_deliverers]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_deliverers.<br />";
}


function create_table_neighborhood()
{
	$db = DB::getConnection();

	if( has_table( 'spiza_neighborhood' ) )
		exit( 'Tablica spiza_neighborhood vec postoji. Obrisite ju pa probajte ponovno.' );

	try
	{
		$st = $db->prepare( 
			'CREATE TABLE IF NOT EXISTS spiza_neighborhood (' .
			'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'id_restaurant int NOT NULL,'.
			'neighborhood varchar(50) NOT NULL)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error [create spiza_neighborhood]: " . $e->getMessage() ); }

	echo "Napravio tablicu spiza_neighborhood.<br />";
}



?> 
