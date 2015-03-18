<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cuti extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_cuti','record');
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
            $this->load->view('transaksi/v_cuti');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();
        
        $user_id = $this->session->userdata('id');
        
        $fcuti_tanggal      = addslashes($_POST['fcuti_tanggal']);
        $fcuti_nik          = addslashes($_POST['fcuti_nik']);
        $fcuti_bagian       = intval(addslashes($_POST['fcuti_bagian']));
        $fcuti_dari         = addslashes($_POST['fcuti_dari']);
        $fcuti_sampai       = addslashes($_POST['fcuti_sampai']);
        $fcuti_keperluan    = addslashes($_POST['fcuti_keperluan']);
        $fcuti_keterangan   = addslashes($_POST['fcuti_keterangan']);
        
        if($this->record->create($fcuti_tanggal, $fcuti_nik, $fcuti_bagian, 
                                 $fcuti_dari, $fcuti_sampai, $fcuti_keperluan,
                                 $fcuti_keterangan, $user_id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($fcuti_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fcuti_tanggal      = addslashes($_POST['fcuti_tanggal']);
        $fcuti_nik          = addslashes($_POST['fcuti_nik']);
        $fcuti_bagian       = intval(addslashes($_POST['fcuti_bagian']));
        $fcuti_dari         = addslashes($_POST['fcuti_dari']);
        $fcuti_sampai       = addslashes($_POST['fcuti_sampai']);
        $fcuti_keperluan    = addslashes($_POST['fcuti_keperluan']);
        $fcuti_keterangan   = addslashes($_POST['fcuti_keterangan']);
        
        if($this->record->update($fcuti_id, $fcuti_tanggal, $fcuti_nik, $fcuti_bagian, 
                                 $fcuti_dari, $fcuti_sampai, $fcuti_keperluan, $fcuti_keterangan))
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

        $fcuti_id       = intval(addslashes($_POST['fcuti_id']));
        
        if($this->record->delete($fcuti_id))
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
    
    function enumJenis()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->enumField('fcuti', 'fcuti_jenis');
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

        $fcuti_id           = intval(addslashes($_POST['fcuti_id']));
        $fcuti_disetujui    = intval(addslashes($_POST['fcuti_disetujui']));
        
        if($this->record->disetujui($fcuti_id, $fcuti_disetujui))
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

        $fcuti_id           = intval(addslashes($_POST['fcuti_id']));
        $fcuti_diketahui    = intval(addslashes($_POST['fcuti_diketahui']));
        
        if($this->record->diketahui($fcuti_id, $fcuti_diketahui))
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

        $fcuti_id           = intval(addslashes($_POST['fcuti_id']));
        $fcuti_ditolak      = intval(addslashes($_POST['fcuti_ditolak']));
        $fcuti_keterangan   = addslashes($_POST['fcuti_keterangan']);
        
        if($this->record->ditolak($fcuti_id, $fcuti_ditolak, $fcuti_keterangan))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
                
}

/* End of file cuti.php */
/* Location: ./application/controllers/transaksi/cuti.php */