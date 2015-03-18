<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pjken extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_pjken','record');
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
            $this->load->view('transaksi/v_pjken');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();
        
        $user_id = $this->session->userdata('id');
        
        $fpjken_tanggal      = addslashes($_POST['fpjken_tanggal']);
        $fpjken_nik          = addslashes($_POST['fpjken_nik']);
        $fpjken_bagian       = intval(addslashes($_POST['fpjken_bagian']));
        $fpjken_pinjam       = addslashes($_POST['fpjken_pinjam']);
        $fpjken_mobil        = addslashes($_POST['fpjken_mobil']);
        $fpjken_keperluan    = addslashes($_POST['fpjken_keperluan']);
        $fpjken_keterangan   = addslashes($_POST['fpjken_keterangan']);
        
        if($this->record->create($fpjken_tanggal, $fpjken_nik, $fpjken_bagian, 
                                $fpjken_pinjam, $fpjken_mobil, $fpjken_keperluan,
                                $fpjken_keterangan, $user_id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($fpjken_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fpjken_tanggal      = addslashes($_POST['fpjken_tanggal']);
        $fpjken_nik          = addslashes($_POST['fpjken_nik']);
        $fpjken_bagian       = intval(addslashes($_POST['fpjken_bagian']));
        $fpjken_pinjam       = addslashes($_POST['fpjken_pinjam']);
        $fpjken_mobil        = addslashes($_POST['fpjken_mobil']);
        $fpjken_keperluan    = addslashes($_POST['fpjken_keperluan']);
        $fpjken_keterangan   = addslashes($_POST['fpjken_keterangan']);
        
        if($this->record->update($fpjken_id, $fpjken_tanggal, $fpjken_nik, $fpjken_bagian, 
                                $fpjken_pinjam, $fpjken_mobil, $fpjken_keperluan, $fpjken_keterangan))
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

        $fpjken_id       = intval(addslashes($_POST['fpjken_id']));
        
        if($this->record->delete($fpjken_id))
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
        
        echo $this->record->enumField('fpjken', 'fpjken_jenis');
    }
    
    function getMobil()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        echo $this->record->getMobil();
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

        $fpjken_id           = intval(addslashes($_POST['fpjken_id']));
        $fpjken_disetujui    = intval(addslashes($_POST['fpjken_disetujui']));
        
        if($this->record->disetujui($fpjken_id, $fpjken_disetujui))
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

        $fpjken_id           = intval(addslashes($_POST['fpjken_id']));
        $fpjken_diketahui    = intval(addslashes($_POST['fpjken_diketahui']));
        
        if($this->record->diketahui($fpjken_id, $fpjken_diketahui))
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

        $fpjken_id           = intval(addslashes($_POST['fpjken_id']));
        $fpjken_ditolak      = intval(addslashes($_POST['fpjken_ditolak']));
        $fpjken_keterangan   = addslashes($_POST['fpjken_keterangan']);
        
        if($this->record->ditolak($fpjken_id, $fpjken_ditolak, $fpjken_keterangan))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }                
}

/* End of file pjken.php */
/* Location: ./application/controllers/transaksi/pjken.php */