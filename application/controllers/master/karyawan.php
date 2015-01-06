<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Karyawan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_karyawan','record');
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
            $this->load->view('master/v_karyawan'); 
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $nik    = addslashes($_POST['karyawan_nik']);
        $nama   = addslashes($_POST['karyawan_nama']);
        $bagian = intval(addslashes($_POST['karyawan_bagian']));
        
        if($this->record->create($nik, $nama, $bagian))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($nik=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $nama   = addslashes($_POST['karyawan_nama']);
        //$bagian = intval(addslashes($_POST['b_departemen_nama'])); // char (.) di convert otomatis menjadi (_)
        $bagian = intval(addslashes($_POST['karyawan_bagian']));
        
        if($this->record->update($nik, $nama, $bagian))
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

        $nik = addslashes($_POST['karyawan_nik']);
        
        if($this->record->delete($nik))
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

/* End of file karyawan.php */
/* Location: ./application/controllers/master/karyawan.php */