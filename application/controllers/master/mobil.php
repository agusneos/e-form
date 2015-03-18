<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_mobil','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid']))
        {
            echo $this->record->index(); 
        }
        else 
        {
            $this->load->view('master/v_mobil');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $mobil_no      = addslashes($_POST['mobil_no']);
        
        if($this->record->create($mobil_no))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($mobil_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $mobil_no      = addslashes($_POST['mobil_no']);
        
        if($this->record->update($mobil_id, $mobil_no))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
        
    function delete()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $mobil_id      = addslashes($_POST['mobil_id']);
        
        if($this->record->delete($mobil_id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
                
}

/* End of file izin.php */
/* Location: ./application/controllers/master/izin.php */