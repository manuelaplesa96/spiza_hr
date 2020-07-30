
<?php

class DeliverersController extends BaseController{

    public function index(){
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Dostavljači';

        
        $this->registry->template->show( 'deliverers_index' );
    }

    public function order()
    {
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Prihvatili ste narudžbu';


        $narudzba=$_GET['id_order'];

        $ls->acceptOrder($narudzba);
        $ls->addDeliverer($narudzba,$_SESSION['deliverers']->id);
        $aktulna_narudzba=$ls->getCurrentOrder($narudzba);
        $this->registry->template->currentOrder=$aktulna_narudzba;
        $this->registry->template->show( 'deliverers_accepted' );
    }

    public function active()
    {
        $ls = new Service();
        error404();
        //debug();

        $this->registry->template->title = $_SESSION['tab'] = 'Aktivna narudžba';
        $aktivna=$ls->activeOrder($_SESSION['deliverers']->id);
        
        $this->registry->template->currentOrder=$aktivna;
        $this->registry->template->show( 'deliverers_accepted' );
    }

    public function delivered()
    {
        $ls = new Service();
        error404();
        //debug();

        if(isset($_POST['dostavljeno']))
            $ls->finish($_POST['btn_dostavljeno']);
        else
        {
            $redirect='Location: index.php?rt=deliverers/order&id_order=' . $_POST['btn_dostavljeno'];
            header($redirect);
        }

        $this->registry->template->title = $_SESSION['tab'] = 'Dostavljači';

        $this->registry->template->show( 'deliverers_index' );
    }

};


//  -----------------
function error404(){        //  provjerava ako se korisnik odlogirao pa ga preusmjerava na 404
    if( !isset($_SESSION['deliverers']) ){
        header( 'Location: ' . __SITE_URL . '/index.php?rt=404' );
    }
}

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