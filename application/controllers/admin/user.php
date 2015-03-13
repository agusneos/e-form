<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/m_user','record');
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
            $this->load->view('admin/v_user');     
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();
        //$this->load->helper('array');
        $name               = addslashes($_POST['name']);
        $username           = addslashes($_POST['username']);
        $pass               = addslashes($_POST['password']);
        $level              = addslashes($_POST['level']);
        $user_departemen    = implode(',', $_POST['user_departemen']);
                
        if($this->record->create($name, $username, $pass, $level, $user_departemen))
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

        $name               = addslashes($_POST['name']);
        $username           = addslashes($_POST['username']);
        $level              = addslashes($_POST['level']);
        $user_departemen    = implode(',', $_POST['user_departemen']);
                
        if($this->record->update($id, $name, $username, $level, $user_departemen))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    
    function reset($id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $pass       = addslashes($_POST['password']);
        
        if($this->record->reset($id, $pass))
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

        $id = intval(addslashes($_POST['id']));
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
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->getDept();
    }
               
}

/* End of file user.php */
/* Location: ./application/controllers/admin/user.php */