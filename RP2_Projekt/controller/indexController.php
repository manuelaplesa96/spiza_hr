<?php 
class IndexController extends BaseController
{
	public function index() 
	{
		debug();

		$this->registry->template->title = 'Log in';

		$this->registry->template->show( 'index_login');
	}
	
	public function logout()
	{
		session_destroy();
		header( 'Location: ' . __SITE_URL . '/index.php');
	}

	public function login()
	{	
		$ls = new Service();
		debug();
		if( !isset( $_POST['username']) || !isset( $_POST['password'] ) || !isset( $_POST['log_in'] ) 
			/*|| preg_match()*/)	//	username ili pasword pogrešno uneseni ++++	dodati pregmatch da izbazi ako je maliciozan unos
		{	//	ispisat grešku pri login-u
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Poreška pri unosu!';
			$this->index();
			return;
		}

		if( $_POST['log_in'] === 'login_user')
			$database = 'spiza_users';		//preko posta odlucit na koju se bazu spaja i tj je li restoran, korisnik ili dostavljac
		else if($_POST['log_in'] === 'login_restaurants')
			$database = 'spiza_restaurants';
		else
			$database = 'spiza_deliverers';			
		if( !$ls->userExsists( $database, $_POST['username']) )
		{
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Korisnik ne postoji!';
			if( $database === 'spiza_users' )
				$this->index();
			elseif( $database === 'spiza_restaurants' )
				$this->loginRestaurants();
			elseif( $database === 'spiza_deliverers' )
				$this->loginDeliverers();
			return;
		}
		if( !$ls->emailConfirmed( $database, $_POST['username']) ){
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Registracija nije dovršena! E-mail nije potvrđen.';
			if( $database === 'spiza_users' )
				$this->index();
			elseif( $database === 'spiza_restaurants' )
				$this->loginRestaurants();
			elseif( $database === 'spiza_deliverers' )
				$this->loginDeliverers();
			return;
		}
		else{
			if( $ls->loginToDatabase( $database ) ){
				if( $database === 'spiza_users')
					header( 'Location: ' . __SITE_URL . '/index.php?rt=user' );
				else if($database === 'spiza_restaurants')
					header( 'Location: ' . __SITE_URL . '/index.php?rt=restaurants' );
				else
					header( 'Location: ' . __SITE_URL . '/index.php?rt=deliverers' );
			}
			else{
				$this->registry->template->errorFlag = True;
				$this->registry->template->errorMsg = 'Krivi username ili password!';
				if( $database === 'spiza_users' )
					$this->index();
				elseif( $database === 'spiza_restaurants' )
					$this->loginRestaurants();
				elseif( $database === 'spiza_deliverers' )
					$this->loginDeliverers();	
				return;
			}
		}
		
	}

	public function registerForward()
	{
		$this->registry->template->title = 'Register';
		$this->registry->template->show( 'register');
	}

	public function registerForward_restaurants()
	{
		$this->registry->template->title = 'Registracija restorani';
		$this->registry->template->show( 'register_restaurant');
	}

	public function register()			//		Trenutno SAMO za KORISNIKE - NE i za restorane
	{
		$ls = new Service();
		if( isset( $_POST['Register_user'] ) )
				$database = 'spiza_users';
		elseif( isset( $_POST['Register_restaurant'] ) )
				$database = 'spiza_restaurants';
		else{
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Greška kod odabira baze!';
			return;
		}
		if( $database === 'spiza_restaurants ')		//	provjera samo za restorane jesu li unjeta ostala polja
			if( !isset( $_POST['name']) || !isset( $_POST['address'] ) || !isset( $_POST['description'] ) )
			{
				$this->registry->template->errorFlag = True;
				$this->registry->template->errorMsg = 'Forma nije popunjena!';
				$this->registerForward_restaurants();
			}
		if( !isset( $_POST['username']) || !isset( $_POST['password'] ) || !isset( $_POST['email'] )  )	//nisu postavljeni
		{
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Krivi unos!';
			if( $database === 'spiza_users' )
				$this->registerForward();
			elseif( $database === 'spiza_restaurants' )
				$this->registerForward_restaurants();
		}
		elseif( $ls->userExsists( $database, $_POST['username']) )
		{
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Username se već koristi!';
			if( $database === 'spiza_users' )
				$this->registerForward();
			elseif( $database === 'spiza_restaurants' )
				$this->registerForward_restaurants();
		}
		else
		{
			$ls->registerUser($database);		

			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Kreiran račun, potvrdite registracijski e-mail!';
			if( $database === 'spiza_users' )
				$this->index();
			elseif( $database === 'spiza_restaurants' )
				$this->loginRestaurants();
		}

	}

	function loginRestaurants()
	{
		debug();
		$this->registry->template->title = 'Restorani';
		$this->registry->template->show( 'index_loginRestaurants');

	}

	function loginDeliverers()
	{
		debug();
		$this->registry->template->title = 'Dostavljači';
		$this->registry->template->show( 'index_loginDeliverers');

	}
}; 




// ------



function debug()
{
	echo '<pre>$_POST=';
	print_r( $_POST );
	if (session_status() !== PHP_SESSION_NONE) {
		
	echo '$_SESSION=';
	print_r( $_SESSION );
}
	echo '</pre>';
}

?>
