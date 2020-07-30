
<?php

class UserController extends BaseController{

    // nakon logina se prikazuje popis omiljenih restorana
    public function index(){
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Vaši omiljeni restorani';
        $narudzbe = $ls->getOrderListByUserId( $_SESSION['user']->id ); // dohvaćamo sve userove narudzbe
        $restorani = [];
        if( $narudzbe != null ){
            foreach ( $narudzbe as $narudzba ){
                if( $narudzba->active == 0 && $narudzba->rating != 0 ){ //Pobrinemo se da promatramo samo dostavljene narudzbe
                    //echo $narudzba->rating;
                    $rest = $ls->getRestaurantById( $narudzba->id_restaurant );
                    if ( !in_array( $rest, array_column( $restorani, 0 ) ) ){ // ako već nije u $restorani, stavljamo ga
                        $i = 0;
                        $s = 0;
                        foreach( $narudzbe as $nar ){ // pregledavamo sve narudzbe istog restorana da bi došli do svih ocijena
                            if( $nar->id_restaurant == $rest->id_restaurant && $nar->active == 0 && $nar->rating != 0 ){
                                $s = $s + $nar->rating;
                                $i++;
                            }
                        }
                        $ocjena = $s/$i;
                        $restorani[] = [$rest, number_format((float)$ocjena, 2, '.', '')]; // za svaki restoran koji je korisnik ocijenio, računamo prosječnu ocijenu
                    }
                }
            }
        }
        $this->registry->template->restaurantList = $restorani;
        
        $this->registry->template->show( 'user_index' );
    }

    // popis svih restorana
    public function restaurants(){
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Svi restorani';
        $ocjene = [];
        $restaurants = $ls->getRestaurantList();
        
        foreach( $restaurants as $restaurant ){
            $res_id = $restaurant->id_restaurant;
            $res_rating = $ls->getRestaurantRatingById( $res_id );
            
            $ocjene[] = number_format((float)$res_rating, 2, '.', '');
        }

        $this->registry->template->ratings = $ocjene;
        $this->registry->template->restaurantList = $ls->getRestaurantList();
        $this->registry->template->show( 'user_restaurants' );
    }

    // popis restorana prema tipu hrane --> NEDOVRŠENO
    public function restaurantsByFoodType(){
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Restorani prema vrsti hrane';

        $ocjene = [];
        $restaurants = $ls->getRestaurantListByFoodType( $_GET['id_foodType']);
        
        foreach( $restaurants as $restaurant ){
            $res_id = $restaurant->id_restaurant;
            $res_rating = $ls->getRestaurantRatingById( $res_id );
            
            $ocjene[] = number_format((float)$res_rating, 2, '.', '');
        }

        $this->registry->template->ratings = $ocjene;
        $this->registry->template->restaurantList = $ls->getRestaurantListByFoodType( $_GET['id_foodType']);
        $this->registry->template->show( 'user_restaurants' );
    }

    public function foodType(){
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Restorani prema vrsti hrane';
        $this->registry->template->foodType = $ls->getFoodTypeList();
        $this->registry->template->show( 'user_foodType' );
    }

    // Ispisuje sve korisnikove narudžbe i daje mogućnost ocjenjivanja dovršenih
    public function orders(){
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = 'Moje narudžbe';
        $_SESSION['tab'] = 'User orders';
        $narudzbe = $ls->getOrderListByUserId( $_SESSION['user']->id );
        $pomocni = [];
        if( $narudzbe != null ){
            foreach ( $narudzbe as $narudzba ){
                $id_restaurant = $narudzba->id_restaurant;
                $narudzba->id_restaurant = ( $ls->getRestaurantById ( $narudzba->id_restaurant ) )->name;
                $hrana = $ls->getFoodIdListByOrderId( $narudzba->id_order );
                $spiza = [];
                for ( $i=0; $i < count( $hrana ); $i++ ){
                    $spiza[] = [$ls->getFoodById( $hrana[$i][0] ), $hrana[$i][1]];
                }
                $pomocni[] = [$narudzba, $spiza, $id_restaurant];
            }
        }
        $this->registry->template->orderList = $pomocni;

        $this->registry->template->show( 'user_orders' );
    }

    // Za ispis stranice restorana s menijem i recenzijama i košaricom
    public function restaurant(){
        $ls = new Service();
        error404();
        //debug();

        $restaurant = $ls->getRestaurantById ( $_GET['id_restaurant'] );
        $this->registry->template->title = $restaurant->name;
        $ocjena = number_format((float)$ls->getRestaurantRatingById( $_GET['id_restaurant'] ), 2, '.', '');
        if( $ocjena == 'nan' )
            $ocjena = (float)0.00;
        $this->registry->template->rating = $ocjena;
        $_SESSION['tab'] = 'User restaurant';
        $this->registry->template->foodList = $ls->getFoodListByRestaurantId( $restaurant->id_restaurant );
        $pomocni = $ls->getOrderListByRestaurantId( $restaurant->id_restaurant );
        
        $this->registry->template->restaurantImages = $ls->getRestaurantImagesById(  $_GET['id_restaurant'] );


        if( $pomocni != null ){
            foreach ( $pomocni as $pom )
                $pom->id_user = ( $ls->getUserById( $pom->id_user ) )->username;
        }

        $this->registry->template->orderList = $pomocni;

        $this->registry->template->show( 'user_restaurant' );
    }

    // prema preporukama drugih
    public function popular()
    {
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Popularni restorani';
        $ratingList = $ls->getRatingList();
        
        /*foreach($ratingList as $rat)
            echo $rat->rating . " ";*/
        

        $restorani = [];
        if( $ratingList != null ){
            foreach ( $ratingList as $rating ){
                if( $rating->rating != 0 ){
                    $rest = $ls->getRestaurantById( $rating->id_restaurant );
                    if ( !in_array( $rest, array_column( $restorani, 0 ) ) ){
                        $i = 0;
                        $s = 0;
                        foreach( $ratingList as $nar ){
                            if( $nar->id_restaurant == $rest->id_restaurant && $nar->rating != 0 ){
                                $s = $s + $nar->rating;
                                $i++;
                            }
                        }
                        $ocjena = $s/$i;
                        $restorani[] = [$rest, number_format((float)$ocjena, 2, '.', '')];
                        //echo max(array_column($restorani, 1));
                    }
                }
            }
        }
        
        $this->registry->template->restaurantList = $restorani;
        $this->registry->template->show( 'user_popular' );
    }

    public function neighborhood()
    {
        $this->registry->template->title = $_SESSION['tab'] = 'Kvartovi';
        $this->registry->template->show( 'user_neighborhood' );
    }

    public function searchNeighborhood()
    {
        if(isset($_POST['kvart']))
        {
            $ls = new Service();
            error404();
            //debug();
    
            $this->registry->template->title = $_SESSION['tab'] = 'Restorani u kvartu ' . $_POST['kvart'];

            $ocjene = [];
            $restaurants = $ls->getRestaurantListByNeighborhood( $_POST['kvart']);
            
            foreach( $restaurants as $restaurant ){
                $res_id = $restaurant->id_restaurant;
                $res_rating = $ls->getRestaurantRatingById( $res_id );
                
                $ocjene[] = number_format((float)$res_rating, 2, '.', '');
            }

            $this->registry->template->ratings = $ocjene;
            $this->registry->template->restaurantList = $ls->getRestaurantListByNeighborhood( $_POST['kvart']);
            $this->registry->template->show( 'user_restaurants' ); 
        }
    }
};


//  -----------------
function error404(){        //  provjerava ako se korisnik odlogirao pa ga preusmjerava na 404
    if( !isset($_SESSION['user']) ){
        header( 'Location: ' . __SITE_URL . '/index.php?rt=404' );
    }
}

/*function debug()
{
	echo '<pre>$_POST=';
	print_r( $_POST );
	if (session_status() !== PHP_SESSION_NONE) {
		
	echo '$_SESSION=';
	print_r( $_SESSION );
    }
	echo '</pre>';
}*/




?>