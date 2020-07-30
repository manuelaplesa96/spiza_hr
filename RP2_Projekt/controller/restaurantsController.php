
<?php

class RestaurantsController extends BaseController{

    public function index(){
        $ls = new Service();
        error404();
        debug();
        
        $this->registry->template->title = $_SESSION['tab'] = 'Restaurants index';
        $this->registry->template->FoodList = $ls->getFoodListByRestaurantId( $_SESSION['restaurants']->id_restaurant );
        $this->registry->template->restaurantRating = $ls->getRestaurantRatingById( $_SESSION['restaurants']->id_restaurant );
        $this->registry->template->restaurantInfo = $ls->getRestaurantById( $_SESSION['restaurants']->id_restaurant );
        $this->registry->template->restaurantImages = $ls->getRestaurantImagesById( $_SESSION['restaurants']->id_restaurant );
        $this->registry->template->foodType = $ls->getFoodTypeByRestaurantId( $_SESSION['restaurants']->id_restaurant);
        $this->registry->template->foodTypeList = $ls->getFoodTypeList( );

        $this->registry->template->show( 'restaurants_index' );
    }

    public function pastOrders(){
        $ls = new Service();
        error404();
        debug();

        $this->registry->template->title = 'Prošle narudžbe';
        $_SESSION['tab'] = 'Prošle narudžbe';
        
        ////////////////////////////////
        $narudzbe = $ls->getOrderListByRestaurantId2( $_SESSION['restaurants']->id_restaurant );
        $pomocni = [];
        if($narudzbe!=null)
        {
            foreach ( $narudzbe as $narudzba ){
                $narudzba->id_restaurant = ( $ls->getRestaurantById ( $narudzba->id_restaurant ) )->name;
                $hrana = $ls->getFoodIdListByOrderId( $narudzba->id_order );
                $spiza = [];
                for ( $i=0; $i < count( $hrana ); $i++ ){
                    $spiza[] = $ls->getFoodById( $hrana[$i][0] );
                }
                $pomocni[] = [$narudzba, $spiza];
            }
        }
        $this->registry->template->orderList = $pomocni;
        $this->registry->template->show( 'restaurants_pastOrders' );
    }

    public function addPhotos(){
        $ls = new Service();
        error404();
        //debug();
      
        ////////////////////////////////
        $polje = $ls->addResturantPhotos( );

        $this->registry->template->errorFlag = True;
        $poruka='';
        if( $polje[1] !== '' )
         {
			$poruka = 'ERROR: Nisu ubačene slike: '+$polje[1];
         }
         $this->registry->template->errorMsg = $poruka . ' Ubačene su slike: '. $polje[0];

         //$this->index();
         header( 'Location: ' . __SITE_URL . '/index.php?rt=restaurants/index');
    }

    public function addCategory(){
        $ls = new Service();
        error404();
        debug();
        
        $ls->addCategory();

         header( 'Location: ' . __SITE_URL . '/index.php?rt=restaurants/index');
         
    }
    public function removeCategory(){
        $ls = new Service();
        error404();
        debug();
        
        $ls->removeCategory();

         header( 'Location: ' . __SITE_URL . '/index.php?rt=restaurants/index');
         
    }
    
    

};


//  -----------------
function error404(){        //  provjerava ako se korisnik odlogirao pa ga preusmjerava na 404
    if( !isset($_SESSION['restaurants']) ){
        header( 'Location: ' . __SITE_URL . '/index.php?rt=404' );
    }
}


function debug()
{
	echo '<pre>$_POST=';
    print_r( $_POST );
    if( isset( $_GET ) )
    {
        echo '$_GET=';
        print_r( $_GET );
    }
    if( isset( $_FILES ) )
    {
        echo '$_FILES=';
        print_r( $_FILES );
    }
	if (session_status() !== PHP_SESSION_NONE) {
		
	echo '$_SESSION=';
	print_r( $_SESSION );
    }
	echo '</pre>';
}





?>