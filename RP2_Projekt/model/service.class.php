<?php 

require_once __DIR__ . '/../app/database/db.class.php';

class Service{

    //                      F   -   je          za          LOGIN
    function userExsists($databaseName, $username)
    {
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM '.$databaseName.' WHERE username=:user');
        $st->execute(['user'=>$username]);
        if( $st->rowCount() !== 0)
            return True;
        else
            return False;
    }

    function emailConfirmed($databaseName, $username )
    {
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT has_registered FROM '.$databaseName.' WHERE username=:user');
        $st->execute(['user'=>$username]);
        $st = $st->fetch();
        if( $st[0] )
            return True;
        else
            return False;
    }

    function loginToDatabase( $databaseName )    //  username i password primamo preko $_POST-a
    {   
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM '.$databaseName.' WHERE username=:user');
        $st->execute(['user'=>$_POST['username']]);
    
        if( $st->rowCount() !== 1)	// korisnik ne postoji ili ih je više -- ispisat grrešku
            return False;
 
        $row = $st->fetch();
        $password_hash = $row['password_hash'];

        if( password_verify( $_POST['password'], $password_hash) )
        {
            if( $_POST['log_in'] === 'login_user')
                $_SESSION['user'] = new User($row['id_user'], $row['username'], ' ',$row['email'], $row['address'], $row['registration_sequence'], $row['has_registered'] );
            else if($_POST['log_in'] === 'login_restaurants')
                $_SESSION['restaurants'] = new Restaurants($row['id_restaurant'], $row['username'], ' ', $row['name'], $row['address'], $row['email'], $row['registration_sequence'], $row['description'], $row['has_registered'] );
            else
                $_SESSION['deliverers'] = new Deliverers($row['id'], $row['username'], ' ',$row['email'], $row['registration_sequence'], $row['has_registered'] );
            return True;
        }
        else
            return False;
    }

    //                      F   -   je          za          REGISTER
    function registerUser($databaseName)
    {
        $reg_seq = '';
        for( $i = 0; $i < 20; ++$i )
            $reg_seq .= chr( rand(0, 25) + ord( 'a' ) );

        $db = DB::getConnection();

        if( $databaseName === 'spiza_users' )
		{
            try{
                $st = $db->prepare( 'INSERT INTO '.$databaseName.' (username, password_hash, email, registration_sequence, has_registered, address) VALUES (:val1,:val2,:val3,:val4,:val5, :val6)');
                $st->execute(['val1'=> $_POST['username'],'val2'=> password_hash( $_POST['password'], PASSWORD_DEFAULT ), 
                            'val3'=> $_POST['email'],'val4'=> $reg_seq,'val5'=> 0, 'val6' => $_POST['address'] ]);
            }catch( PDOException $e ) { exit( "PDO error [insert spiza_users]: " . $e->getMessage() ); }
        }
        elseif( $databaseName === 'spiza_restaurants' )
        {
            try{
                $st = $db->prepare( 'INSERT INTO '.$databaseName.' (username, password_hash, email, registration_sequence, has_registered, name, address, description) VALUES (:val1,:val2,:val3,:val4,:val5, :val6, :val7, :val8)');
                $st->execute(['val1'=> $_POST['username'],'val2'=> password_hash( $_POST['password'], PASSWORD_DEFAULT ), 
                            'val3'=> $_POST['email'],'val4'=> $reg_seq,'val5'=> 0, 'val6'=>$_POST['name'], 'val7'=>$_POST['address'], 'val8'=>$_POST['description'] ]);
            }catch( PDOException $e ) { exit( "PDO error [insert spiza_restaurants]: " . $e->getMessage() ); }
        }


        $to       = $_POST['email'];
        $subject  = 'Registracijski mail';
        $message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
        $message = 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/app/register.php?niz=' . $reg_seq . "\n";

        if( $databaseName === 'spiza_restaurants' )
            $message = 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/app/register_restaurant.php?niz=' . $reg_seq . "\n";

        $headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
                    'Reply-To: rp2@studenti.math.hr' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        $isOK = mail($to, $subject, $message, $headers);

        if( !$isOK )
            exit( 'Greška: ne mogu poslati mail. (Pokrenite na rp2 serveru.)' );
        
    }

    // funkcija jednostavno vraća sve restorane prisutne u bazi
    function getRestaurantList()
    {
        $restaurants =[];

        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT * FROM spiza_restaurants');
        $st->execute( );

        while( $row = $st->fetch() )
            $restaurants[] = new Restaurants($row['id_restaurant'], '', '', $row['name'], $row['address'], $row['email'], '', $row['description'], 1 );
        return $restaurants;
    }

    // funkcija prima id usera i vraća njegove feedbackove poredano silazno po njegovoj ocjeni,
    // ako taj korisnik nije ocijenio do sada nijedan restoran vraca null 
    function getMyFeedbackList( $id_user )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_orders WHERE id_user=:id_user ORDER BY rating DESC');
            $st->execute( ['id_user'=>$id_user] );
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()===0)
            return null;
        else{
            $feedbacks = [];
            
            while( $row = $st->fetch() )
            {
                $feedbacks[] = new Feedback( $row['id'], $row['id_user'], $row['id_restaurant'], $row['content'], $row['rating'], $row['thumbs_up'], $row['thumbs_down'] );
            }
            return $feedbacks;
        }
    }

    // funkcija prima id usera, poziva fju getMyFeedbackList koja vraca korisnikove recenzije sortirano silazno po ocjeni
    // te vraca popis restorana koje je korisnik ocijenio (silazno po ocjeni)
    // možemo još nekako ubaciti da se za svaki restoran koji je korisnik ocijenio gleda koliko puta je naručio iz istog pa se restoran koji ima
    // najveću ocjenu i iz kojeg je korisnik najvise puta narucio hranu nalazi na vrhu liste, zatim se redaju restorani s istom ocjenom,
    // ali silazno po broju narudzbi --> za ovo bi trebali ubaciti broj narudzbi u bazu jer bi upit za brojanje narudzbi nekog korisnika iz nekog restorana
    // i jos poredano silazno po tom broju bio jako kompliciran...
    function getRestaurantListByMyRating( $id_user ){
        $ls = new Service();
        $feedbacks = $ls->getMyFeedbackList( $id_user );
        if( $feedbacks === null )
            return null;

        foreach( $feedbacks as $feedback ){
            $id = $feedback->id_restaurant;

            try{
                $db = DB::getConnection();
                $st = $db->prepare( 'SELECT * FROM spiza_restaurants WHERE id=:id' );
                $st->execute( [ 'id' => $id ] );
            }
            catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
            $restaurants = [];
                    
             while( $row = $st->fetch() )
                $restaurants[] = new Restaurants($row['id'], '', '', $row['name'], $row['address'], $row['email'], '', $row['rating'], $row['food_type'], $row['description'], 1 );
        }
        return $restaurants;
    }

    //  Popravljeno za novu bazu
    function getRestaurantById( $id )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_restaurants WHERE id_restaurant=:rest');
            $st->execute(['rest'=>$id]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()!==1)
            return null;
        else{
            $row=$st->fetch();
            return new Restaurants( $row['id_restaurant'], '', '', $row['name'], $row['address'], $row['email'], '', $row['description'], 1  );
        }
    }

    //fje za prikaz narudžbi
    // Vraća listu svih narudžbi koje je podnio korisnik s $id_user
    function getOrderListByUserId( $id_user )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_orders WHERE id_user=:user ORDER BY id_order DESC');
            $st->execute(['user'=>$id_user]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()===0)
            return null;
        else{
            $arr = array();
            while( $row = $st->fetch() )
            {
                $arr[] = new Order( $row['id_order'], $row['id_user'], $row['id_restaurant'], $row['id_deliverer'], $row['active'], $row['order_time'], $row['delivery_time'], $row['price_total'], $row['discount'], $row['note'], $row['address'], $row['feedback'], $row['rating'], $row['thumbs_up'], $row['thumbs_down'] );
            }
            return $arr;
        }
    }

    // vraća listu svih narudžbi restorana
    function getOrderListByRestaurantId( $id_restaurant )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_orders WHERE id_restaurant=:res AND rating > :rat');
            $st->execute(['res'=>$id_restaurant, 'rat'=>'0']);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()===0)
            return null;
        else{
            $arr = array();
            while( $row = $st->fetch() )
            {
                $arr[] = new Order( $row['id_order'], $row['id_user'], $row['id_restaurant'], $row['id_deliverer'], $row['active'], $row['order_time'], $row['delivery_time'], $row['price_total'], $row['discount'], $row['note'], $row['address'], $row['feedback'], $row['rating'], $row['thumbs_up'], $row['thumbs_down'] );
            }
            return $arr;
        }
    }
    // vraća listu svih narudžbi restorana - za završene 
    function getOrderListByRestaurantId2( $id_restaurant )
    {
        try
        {
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_orders WHERE id_restaurant=:res AND active <= :rat');
            $st->execute(['res'=>$id_restaurant, 'rat'=> 1]);
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()===0)
            return null;
        else{
            $arr = array();
            while( $row = $st->fetch() )
            {
                $arr[] = new Order( $row['id_order'], $row['id_user'], $row['id_restaurant'], $row['id_deliverer'], $row['active'], $row['order_time'], $row['delivery_time'], 
                $row['price_total'], $row['discount'], $row['note'], $row['address'], $row['feedback'], $row['rating'], $row['thumbs_up'], $row['thumbs_down'] );
            }
            return $arr;
        }
    }

    //fje za prikaz hrane
    //popravljena za novu bazu
    function getFoodById( $id )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_food WHERE id_food=:hrana');
            $st->execute(['hrana'=>$id]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()!==1)
            return null;
        else{
            $row=$st->fetch();
            return new Food ( $row['id_food'], $row['name'], $row['description'], $row['waiting_time'], $row['price'], $row['in_offering'], $row['id_restaurant'], $row['image_path'] );
        }
    }
    //  Popravljeno za novu bazu 
    function getFoodListByRestaurantId( $id_restaurant )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_food WHERE id_restaurant=:rest AND in_offering=1' );
            $st->execute(['rest'=>$id_restaurant]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()===0)
            return null;
        else{
            $arr = array();
            while( $row = $st->fetch() )
            {
                $arr[] = new Food( $row['id_food'], $row['name'], $row['description'], $row['waiting_time'], $row['id_restaurant'], $row['price'], $row['in_offering'], $row['image_path'] );
            }
            return $arr;
        }
    }

    function getOrderById( $id )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_orders WHERE id_order=:order');
            $st->execute(['order'=>$id]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()!==1)
            return null;
        else{
            $row=$st->fetch();
            return new Order($row['id_order'], $row['id_user'], $row['id_restaurant'], $row['id_deliverer'], $row['active'], $row['order_time'], $row['delivery_time'], $row['price_total'], $row['discount'], $row['note'], $row['address'], $row['feedback'], $row['rating'], $row['thumbs_up'], $row['thumbs_down'] );
        }
    }

    function getFoodIdListByOrderId( $id_order )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_contains WHERE id_order=:ord' );
            $st->execute(['ord'=>$id_order]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()===0)
            return null;
        else{
            $arr = array();
            while( $row = $st->fetch() )
            {
                $arr[] = [$row['id_food'], $row['quantity']];
            }
            return $arr;
        }
    }

    //fje za prikaz korisnika
    function getUserById( $id )
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_users WHERE id_user=:user');
            $st->execute(['user'=>$id]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()!==1)
            return null;
        else{
            $row=$st->fetch();
            return new User( $row['id_user'], $row['username'], $row['password_hash'], $row['email'], $row['address'], $row['registration_sequence'], $row['has_registered'] );
        }
    }

    //      Dohvaća rating restorana po id-u 
    function getRestaurantRatingById( $id )
    {
        $sum = 0;
        $count = 0;
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT rating FROM spiza_orders WHERE id_restaurant=:restaurant' );
            $st->execute(['restaurant'=>$id]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount() === 0 )
            return 0;
        else{
            while( $row = $st->fetch() )
            {
                if( $row['rating'] != 0 ){
                    $sum += $row['rating'];
                    $count += 1;
                }
            }
            if( $count != 0) return $sum / $count;
            else return 0;
        }
    }

    function addCategory()
    {
        $db=DB::getConnection();
        try
        {
            $st = $db->prepare( 'INSERT INTO spiza_has_food_type(id_foodType, id_restaurant)  VALUES (:id_foodType, :id_restaurant)' );
            $st->execute( [ 'id_foodType' => intval($_POST['addCategory']), 'id_restaurant' => $_SESSION['restaurants']->id_restaurant ] );    
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    }
    function removeCategory()
    {
        $db=DB::getConnection();
        try
        {
            $st = $db->prepare( 'DELETE FROM spiza_has_food_type WHERE id_foodType=:id_foodType AND id_restaurant=:id_restaurant' );
            $st->execute( [ 'id_foodType' => intval($_POST['removeCategory']), 'id_restaurant' => $_SESSION['restaurants']->id_restaurant ] );    
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    }

    function getFoodTypeByRestaurantId( $restaurantId )
    {
        $typeid = [];
        $foodType = [];
        $db = DB::getConnection();

        try
        {
            $st = $db->prepare( 'SELECT * FROM spiza_has_food_type WHERE id_restaurant=:val');
            $st->execute( [ 'val' => $restaurantId ] );    
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        
        while( $row = $st->fetch() )
            $typeid[] = $row['id_foodType'];

        foreach( $typeid as $food )
        {
            try
            {
                $st = $db->prepare( 'SELECT * FROM spiza_food_type WHERE id_foodType=:val');
                $st->execute( [ 'val' => $food ] );    
            }
            catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
            
            $row = $st->fetch();
            $foodType[] = new FoodType( $row['id_foodType'], $row['name'], $row['image_path'] );
        }

        return $foodType;
    }

    function getFoodTypeList()
    {
        $foodType = [];
        try
        {
            $db = DB::getConnection();
            $st = $db->prepare( 'SELECT * FROM spiza_food_type');
            $st->execute( );    
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        
        while( $row = $st->fetch() )
            $foodType[] = new FoodType( $row['id_foodType'], $row['name'], $row['image_path'] );
        return $foodType;
    }

    // popis restorana prema tipu hrane
    // primi id tipa hrane i iz has_food_type nađe id restorana pa
    // pomoću getRestaurantById dobijemo restoran s traženim id
    function getRestaurantListByFoodType( $id_foodType )
    {
        $restaurants = [];
        $ls = new Service();
        try{
            $db = DB::getConnection();
            $st = $db->prepare( 'SELECT * FROM spiza_has_food_type WHERE id_foodType=:id_foodType');
            $st->execute( [ 'id_foodType' => $id_foodType ] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

        while( $row = $st->fetch() ){
            $id_restaurant = $row['id_restaurant'];
            $restaurants[]=$ls->getRestaurantById($id_restaurant);
        }
        return $restaurants;
    }

    function getRatingList()
    {
        try
		{
            $db=DB::getConnection();
            $st=$db->prepare('SELECT * FROM spiza_orders ORDER BY rating DESC');
            $st->execute( );
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        if ($st->rowCount()===0)
            return null;
        else{
            $ratingList = [];
            
            while( $row = $st->fetch() )
            {
                $ratingList[] = new Feedback( $row['id_order'], $row['id_user'], $row['id_restaurant'], $row['feedback'], $row['rating'], $row['thumbs_up'], $row['thumbs_down'] );
            }
            return $ratingList;
        }
    }


    function getRestaurantListByNeighborhood( $neighborhood )
    {
        $restaurants = [];
        $ls = new Service();
        try{
            $db = DB::getConnection();
            $st = $db->prepare( 'SELECT * FROM spiza_neighborhood WHERE neighborhood=:neighborhood');
            $st->execute( [ 'neighborhood' => $neighborhood ] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

        while( $row = $st->fetch() ){
            $id_restaurant = $row['id_restaurant'];
            $restaurants[]=$ls->getRestaurantById($id_restaurant);
        }
        return $restaurants;
    }

    
    function acceptOrder($id_narudzbe)
    {
        try{
            $db = DB::getConnection();
            $st2 = $db->prepare( 'UPDATE spiza_orders SET active=3 WHERE id_order=:id_order');
            $st2->execute( [ 'id_order' => $id_narudzbe ] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    }

    function addDeliverer($id_narudzbe,$id_deliverer)
    {
        try{
            $db = DB::getConnection();
            $st2 = $db->prepare( "UPDATE spiza_orders SET id_deliverer='$id_deliverer' WHERE id_order=:id_order");
            $st2->execute( [ 'id_order' => $id_narudzbe ] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    }

    function finish($id_narudzbe)
    {
        $date = date("Y-m-d H:i:s");
        try{
            $db = DB::getConnection();
            $st2 = $db->prepare( 'UPDATE spiza_orders SET active=0 WHERE id_order=:id_order');
            $st2->execute( [ 'id_order' => $id_narudzbe] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

        try{
            $db = DB::getConnection();
            $st2 = $db->prepare( 'UPDATE spiza_orders SET delivery_time=now() WHERE id_order=:id_order');
            $st2->execute( [ 'id_order' => $id_narudzbe] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    }

    function activeOrder($id_deliverer)
    {
        $ls = new Service();

        try{
            $db = DB::getConnection();
            $st = $db->prepare( 'SELECT * FROM spiza_orders WHERE id_deliverer=:id_deliverer AND active=:val');
            $st->execute( [ 'id_deliverer' => $id_deliverer, 'val' => 3] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        $row=$st->fetch();
  
        if ($st->rowCount()===0)
            return null;
        else
        {
            $id_order=$row['id_order'];
            $aktivna=$ls->getCurrentOrder($id_order);
            return $aktivna;
        }
    }

    function getCurrentOrder($id)
    {
        $ls = new Service();

        $hrana=[];
        
        $o=$ls->getOrderById($id);
        $user=$ls->getUserById($o->id_user);
        $restaurant=$ls->getRestaurantById($o->id_restaurant);
            
        try{
            $db = DB::getConnection();
            $st = $db->prepare( 'SELECT * FROM spiza_contains WHERE id_order=:id_order');
            $st->execute( [ 'id_order' => $id ] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        while($row=$st->fetch()){
            $h=$ls->getFoodById($row['id_food']);
            $hrana[]=[$h->name,$row['quantity']];
        }
        return [$o,$user->username,$restaurant->name,$hrana];
    }


    function addResturantPhotos(  )
    {

        $location = [];

        try{    // Pomoću transakcije ubacujemo u bazu info o hrani pa postavljamo ime slike
            // id_food.___ pa ubacujemo path u bazu, u slučaju da se ne uspiju u bacit
            // sve promjene u bazi se poništavaju
            $db=DB::getConnection();

            $db->beginTransaction();

            foreach( $_FILES['addPhotos']['name'] as $image)
            {

                $st=$db->prepare( 'INSERT INTO spiza_image(name, id_restaurant)  VALUES (:name, :id_restaurant)' );
                $st->execute( array( 'name' => '', 'id_restaurant' => intval( $_SESSION['restaurants']->id_restaurant ) ) );		
                
                $lastInsertedID = $db->lastInsertId();
    
                $tmp = explode( '.', $image);
                $location[] = 'images/restaurants/' . $lastInsertedID . '.'.end($tmp);

                $st2=$db->prepare( 'UPDATE spiza_image SET image=:val WHERE id_image=:val2' );
                $st2->execute( array(  'val' => '/app/' . end($location) , 'val2' =>  $lastInsertedID ) );		
            }
            $db->commit();
        }
        catch( PDOException $e ) { 
            $db->rollBack();
            return [ '','Greška u bazi!'];
        }

        $ubaceno='';
        $nijeubaceno='';
        for( $i = 0; $i < sizeof($_FILES['addPhotos']['name']); ++$i )
        {   
                //  Premještamo preimenovanu sliku u folder za slike 
            if( move_uploaded_file($_FILES['addPhotos']['tmp_name'][$i], __SITE_PATH.'/app/'. $location[$i]) ){
                $a =explode('/',$location[$i]);
                $ubaceno = $ubaceno . end($a) . ', ';
                //echo 'Food and image added for ' . $_FILES['addPhotos']['name'][$i] . '.';
            }   // U slučaju da premještanje nije uspjelo poništavamo unos hrane u bazu
            else{
                $a =explode('/',$location[$i]);
                $nijeubaceno = $nijeubaceno . end($a);
                //$st=$db->prepare( 'DELETE FROM spiza_food WHERE id_food=:val' );
                //$st->execute( array( 'val' => $lastInsertedID ) );		
                //echo 'ERROR: Moveing image! Changes not applied!';
            }

        }
        $_FILES['addPhotos']='';
        return [$ubaceno, $nijeubaceno];



    }

    function getRestaurantImagesById( $id )
    {
        $image['name'] = [];
        $image['image'] = [];
        $image['id_restaurant'] = [];

        try{
            $db = DB::getConnection();
            $st = $db->prepare( 'SELECT * FROM spiza_image WHERE id_restaurant=:id_restaurant');
            $st->execute( [ 'id_restaurant' => $id ] );
        }
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        while($row=$st->fetch()){
            $image['name'][] = $row['name'];
            $image['image'][] = $row['image'];
            $image['id_restaurant'][] = $row['id_restaurant'];
        }
        return $image;
    }
};



//  -------------------------------------------------------------


function editContent( $userList, $content){
    foreach( $userList as $user )
        if( strpos($content, '@'.$user) !== False )
            $content = str_replace('@'.$user,'<a href="'. __SITE_URL . '/index.php?rt=messeges/userMesseges/?name='.$user.'">@' . $user . '</a>', $content);
    return $content;
}
function stringToColorCode($str) {
    $code = dechex(crc32($str));
    $code = substr($code, 0, 6);
    return $code;
  }
?>