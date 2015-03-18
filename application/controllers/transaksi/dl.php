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
        
        $dept = $this->session->userdata('user_departemen');
        
        if (isset($_GET['grid']))
        {
            echo $this->record->index($dept); 
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

        $user_id = $this->session->userdata('id');
        
        $fdl_tanggal    = addslashes($_POST['fdl_tanggal']);
        $fdl_nik        = addslashes($_POST['fdl_nik']);
        $fdl_bagian     = intval(addslashes($_POST['fdl_bagian']));
        $fdl_dari       = addslashes($_POST['fdl_dari']);
        $fdl_sampai     = addslashes($_POST['fdl_sampai']);
        $fdl_jam        = addslashes($_POST['fdl_jam']);
        $fdl_tujuan     = addslashes($_POST['fdl_tujuan']);
        $fdl_bersama    = addslashes($_POST['fdl_bersama']);
        $fdl_keperluan  = addslashes($_POST['fdl_keperluan']);
        $fdl_keterangan = addslashes($_POST['fdl_keterangan']);
        
        if($this->record->create($fdl_tanggal, $fdl_nik, $fdl_bagian, $fdl_dari, $fdl_sampai, 
                            $fdl_jam, $fdl_tujuan, $fdl_bersama, $fdl_keperluan, 
                            $fdl_keterangan, $user_id))
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
        $fdl_dari       = addslashes($_POST['fdl_dari']);
        $fdl_sampai     = addslashes($_POST['fdl_sampai']);
        $fdl_jam        = addslashes($_POST['fdl_jam']);
        $fdl_tujuan     = addslashes($_POST['fdl_tujuan']);
        $fdl_bersama    = addslashes($_POST['fdl_bersama']);
        $fdl_keperluan  = addslashes($_POST['fdl_keperluan']);
        $fdl_keterangan = addslashes($_POST['fdl_keterangan']);
        
        if($this->record->update($fdl_id, $fdl_tanggal, $fdl_nik, $fdl_bagian, $fdl_dari, 
                            $fdl_sampai, $fdl_jam, $fdl_tujuan, $fdl_bersama, 
                            $fdl_keperluan, $fdl_keterangan))
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
        
        $q = isset($_POST['q']) ? strval($_POST['q']) : '';
        
        $dept = $this->session->userdata('user_departemen');
        echo $this->record->getKaryawan($dept, $q);
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
    
    function getUser()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        $id = $this->session->userdata('id');
        echo $this->record->getUser($id);
    }
    
    function disetujui()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fdl_id           = intval(addslashes($_POST['fdl_id']));
        $fdl_disetujui    = intval(addslashes($_POST['fdl_disetujui']));
        
        if($this->record->disetujui($fdl_id, $fdl_disetujui))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    
    function diketahui()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fdl_id           = intval(addslashes($_POST['fdl_id']));
        $fdl_diketahui    = intval(addslashes($_POST['fdl_diketahui']));
        
        if($this->record->diketahui($fdl_id, $fdl_diketahui))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    
    function ditolak()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fdl_id           = intval(addslashes($_POST['fdl_id']));
        $fdl_ditolak      = intval(addslashes($_POST['fdl_ditolak']));
        $fdl_keterangan   = addslashes($_POST['fdl_keterangan']);
        
        if($this->record->ditolak($fdl_id, $fdl_ditolak, $fdl_keterangan))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }                
}

/* End of file dl.php */
/* Location: ./application/controllers/transaksi/dl.php */