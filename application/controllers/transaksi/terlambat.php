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
        
        if (isset($_GET['grid']))
        {
            echo $this->record->index(); 
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

        $fterlambat_tanggal      = addslashes($_POST['fterlambat_tanggal']);
        $fterlambat_nik          = addslashes($_POST['fterlambat_nik']);
        $fterlambat_bagian       = intval(addslashes($_POST['fterlambat_bagian']));
        $fterlambat_shift        = addslashes($_POST['fterlambat_shift']);
        $fterlambat_waktu        = addslashes($_POST['fterlambat_waktu']);
        $fterlambat_alasan       = addslashes($_POST['fterlambat_alasan']);
        
        if($this->record->create($fterlambat_tanggal, $fterlambat_nik, $fterlambat_bagian, 
                                $fterlambat_shift, $fterlambat_waktu, $fterlambat_alasan))
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
        
        if($this->record->update($fterlambat_id, $fterlambat_tanggal, $fterlambat_nik, $fterlambat_bagian, 
                                $fterlambat_shift, $fterlambat_waktu, $fterlambat_alasan))
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
        
        echo $this->record->getKaryawan();
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
                
}

/* End of file terlambat.php */
/* Location: ./application/controllers/transaksi/terlambat.php */