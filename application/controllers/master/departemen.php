<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departemen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_departemen','record');
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
            $this->load->view('master/v_departemen'); 
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();
        
        $induk  = intval(addslashes($_POST['a_departemen_induk']));
        $bagian = addslashes($_POST['a_departemen_nama']);
        
        if($this->record->create($induk, $bagian))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();
        
        $induk  = intval(addslashes($_POST['a_departemen_induk']));
        $bagian = addslashes($_POST['a_departemen_nama']);
        
        if($this->record->update($id, $induk, $bagian))
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

        $id  = intval(addslashes($_POST['id']));
        
        if($this->record->delete($id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    
    function getDept()
    {
        echo $this->record->getDept();
    }
                            
}

/* End of file departemen.php */
/* Location: ./application/controllers/master/departemen.php */