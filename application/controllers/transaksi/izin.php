<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Izin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_izin','record');
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
            $this->load->view('transaksi/v_izin');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $user_id = $this->session->userdata('id');
        
        $fizin_tanggal      = addslashes($_POST['fizin_tanggal']);
        $fizin_nik          = addslashes($_POST['fizin_nik']);
        $fizin_bagian       = intval(addslashes($_POST['fizin_bagian']));
        $fizin_jenis        = addslashes($_POST['fizin_jenis']);
        $fizin_dari         = addslashes($_POST['fizin_dari']);
        $fizin_sampai       = addslashes($_POST['fizin_sampai']);
        $fizin_keperluan    = addslashes($_POST['fizin_keperluan']);
        $fizin_keterangan   = addslashes($_POST['fizin_keterangan']);
        
        if($this->record->create($fizin_tanggal, $fizin_nik, $fizin_bagian, $user_id, $fizin_keterangan,
                                $fizin_jenis, $fizin_dari, $fizin_sampai, $fizin_keperluan))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($fizin_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fizin_tanggal      = addslashes($_POST['fizin_tanggal']);
        $fizin_nik          = addslashes($_POST['fizin_nik']);
        $fizin_bagian       = intval(addslashes($_POST['fizin_bagian']));
        $fizin_jenis        = addslashes($_POST['fizin_jenis']);
        $fizin_dari         = addslashes($_POST['fizin_dari']);
        $fizin_sampai       = addslashes($_POST['fizin_sampai']);
        $fizin_keperluan    = addslashes($_POST['fizin_keperluan']);
        $fizin_keterangan   = addslashes($_POST['fizin_keterangan']);
        
        if($this->record->update($fizin_id, $fizin_tanggal, $fizin_nik, $fizin_bagian, $fizin_keterangan, 
                                $fizin_jenis, $fizin_dari, $fizin_sampai, $fizin_keperluan))
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

        $fizin_id       = intval(addslashes($_POST['fizin_id']));
        
        if($this->record->delete($fizin_id))
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
    
    function enumJenis()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->enumField('fizin', 'fizin_jenis');
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

        $fizin_id           = intval(addslashes($_POST['fizin_id']));
        $fizin_disetujui    = intval(addslashes($_POST['fizin_disetujui']));
        
        if($this->record->disetujui($fizin_id, $fizin_disetujui))
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

        $fizin_id           = intval(addslashes($_POST['fizin_id']));
        $fizin_diketahui    = intval(addslashes($_POST['fizin_diketahui']));
        
        if($this->record->diketahui($fizin_id, $fizin_diketahui))
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

        $fizin_id           = intval(addslashes($_POST['fizin_id']));
        $fizin_ditolak      = intval(addslashes($_POST['fizin_ditolak']));
        $fizin_keterangan   = addslashes($_POST['fizin_keterangan']);
        
        if($this->record->ditolak($fizin_id, $fizin_ditolak, $fizin_keterangan))
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
/* Location: ./application/controllers/transaksi/izin.php */