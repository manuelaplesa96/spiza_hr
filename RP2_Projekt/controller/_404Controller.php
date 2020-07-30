<?php 

class _404Controller extends BaseController
{
	public function index() 
	{
		// Popuni template potrebnim podacima
		$this->registry->template->title = 'Stranica nije pronađena.';
		session_destroy();
        $this->registry->template->show( '404_index' );
	}
}; 

?>
