<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terlambat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_terlambat','record');
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
            $this->load->view('transaksi/v_terlambat');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $user_id = $this->session->userdata('id');
        
        $fterlambat_tanggal      = addslashes($_POST['fterlambat_tanggal']);
        $fterlambat_nik          = addslashes($_POST['fterlambat_nik']);
        $fterlambat_bagian       = intval(addslashes($_POST['fterlambat_bagian']));
        $fterlambat_shift        = addslashes($_POST['fterlambat_shift']);
        $fterlambat_waktu        = addslashes($_POST['fterlambat_waktu']);
        $fterlambat_alasan       = addslashes($_POST['fterlambat_alasan']);
        $fterlambat_keterangan   = addslashes($_POST['fterlambat_keterangan']);
        
        if($this->record->create($fterlambat_tanggal, $fterlambat_nik, $fterlambat_bagian, 
                                 $fterlambat_shift, $fterlambat_waktu, $fterlambat_alasan, 
                                 $fterlambat_keterangan, $user_id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($fterlambat_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fterlambat_tanggal      = addslashes($_POST['fterlambat_tanggal']);
        $fterlambat_nik          = addslashes($_POST['fterlambat_nik']);
        $fterlambat_bagian       = intval(addslashes($_POST['fterlambat_bagian']));
        $fterlambat_shift        = addslashes($_POST['fterlambat_shift']);
        $fterlambat_waktu        = addslashes($_POST['fterlambat_waktu']);
        $fterlambat_alasan       = addslashes($_POST['fterlambat_alasan']);
        $fterlambat_keterangan   = addslashes($_POST['fterlambat_keterangan']);
        
        if($this->record->update($fterlambat_id, $fterlambat_tanggal, $fterlambat_nik, 
                                 $fterlambat_bagian, $fterlambat_shift, $fterlambat_waktu, 
                                 $fterlambat_alasan, $fterlambat_keterangan))
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

        $fterlambat_id       = intval(addslashes($_POST['fterlambat_id']));
        
        if($this->record->delete($fterlambat_id))
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
    
    function enumShift()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->enumField('fterlambat', 'fterlambat_shift');
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

        $fterlambat_id           = intval(addslashes($_POST['fterlambat_id']));
        $fterlambat_disetujui    = intval(addslashes($_POST['fterlambat_disetujui']));
        
        if($this->record->disetujui($fterlambat_id, $fterlambat_disetujui))
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

        $fterlambat_id           = intval(addslashes($_POST['fterlambat_id']));
        $fterlambat_diketahui    = intval(addslashes($_POST['fterlambat_diketahui']));
        
        if($this->record->diketahui($fterlambat_id, $fterlambat_diketahui))
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

        $fterlambat_id           = intval(addslashes($_POST['fterlambat_id']));
        $fterlambat_ditolak      = intval(addslashes($_POST['fterlambat_ditolak']));
        $fterlambat_keterangan   = addslashes($_POST['fterlambat_keterangan']);
        
        if($this->record->ditolak($fterlambat_id, $fterlambat_ditolak, $fterlambat_keterangan))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }                
}

/* End of file terlambat.php */
/* Location: ./application/controllers/transaksi/terlambat.php */