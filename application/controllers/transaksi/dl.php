<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dl extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_dl','record');
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
            $this->load->view('transaksi/v_dl');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fdl_tanggal    = addslashes($_POST['fdl_tanggal']);
        $fdl_nik        = addslashes($_POST['fdl_nik']);
        $fdl_bagian     = intval(addslashes($_POST['fdl_bagian']));
        $fdl_dinas      = addslashes($_POST['fdl_dinas']);
        $fdl_dari       = addslashes($_POST['fdl_dari']);
        $fdl_sampai     = addslashes($_POST['fdl_sampai']);
        $fdl_jam        = addslashes($_POST['fdl_jam']);
        $fdl_tujuan     = addslashes($_POST['fdl_tujuan']);
        $fdl_bersama    = addslashes($_POST['fdl_bersama']);
        $fdl_keperluan  = addslashes($_POST['fdl_keperluan']);
        
        if($this->record->create($fdl_tanggal, $fdl_nik, $fdl_bagian, 
                                 $fdl_dinas, $fdl_dari, $fdl_sampai, $fdl_jam,
                                 $fdl_tujuan, $fdl_bersama, $fdl_keperluan))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($fdl_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fdl_tanggal    = addslashes($_POST['fdl_tanggal']);
        $fdl_nik        = addslashes($_POST['fdl_nik']);
        $fdl_bagian     = intval(addslashes($_POST['fdl_bagian']));
        $fdl_dinas      = addslashes($_POST['fdl_dinas']);
        $fdl_dari       = addslashes($_POST['fdl_dari']);
        $fdl_sampai     = addslashes($_POST['fdl_sampai']);
        $fdl_jam        = addslashes($_POST['fdl_jam']);
        $fdl_tujuan     = addslashes($_POST['fdl_tujuan']);
        $fdl_bersama    = addslashes($_POST['fdl_bersama']);
        $fdl_keperluan  = addslashes($_POST['fdl_keperluan']);
        
        if($this->record->update($fdl_id, $fdl_tanggal, $fdl_nik, $fdl_bagian, 
                                 $fdl_dinas, $fdl_dari, $fdl_sampai, $fdl_jam,
                                 $fdl_tujuan, $fdl_bersama, $fdl_keperluan))
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

        $fdl_id       = intval(addslashes($_POST['fdl_id']));
        
        if($this->record->delete($fdl_id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
        
    function getKaryawan()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->getKaryawan();
    }
    
    function getDept()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->getDept();
    }
    
    function enumDinas()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->enumField('fdl', 'fdl_dinas');
    }
                
}

/* End of file dl.php */
/* Location: ./application/controllers/transaksi/dl.php */