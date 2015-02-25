<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Absenman extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_absenman','record');
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
            $this->load->view('transaksi/v_absenman');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fabsenman_tanggal      = addslashes($_POST['fabsenman_tanggal']);
        $fabsenman_nik          = addslashes($_POST['fabsenman_nik']);
        $fabsenman_bagian       = intval(addslashes($_POST['fabsenman_bagian']));
        $fabsenman_datang       = addslashes($_POST['fabsenman_datang']);
        $fabsenman_pulang       = addslashes($_POST['fabsenman_pulang']);
        $fabsenman_alasan       = addslashes($_POST['fabsenman_alasan']);
        
        if($this->record->create($fabsenman_tanggal, $fabsenman_nik, $fabsenman_bagian, 
                                 $fabsenman_datang, $fabsenman_pulang, $fabsenman_alasan))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($fabsenman_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fabsenman_tanggal      = addslashes($_POST['fabsenman_tanggal']);
        $fabsenman_nik          = addslashes($_POST['fabsenman_nik']);
        $fabsenman_bagian       = intval(addslashes($_POST['fabsenman_bagian']));
        $fabsenman_datang       = addslashes($_POST['fabsenman_datang']);
        $fabsenman_pulang       = addslashes($_POST['fabsenman_pulang']);
        $fabsenman_alasan       = addslashes($_POST['fabsenman_alasan']);
        
        if($this->record->update($fabsenman_id, $fabsenman_tanggal, $fabsenman_nik, $fabsenman_bagian, 
                                 $fabsenman_datang, $fabsenman_pulang, $fabsenman_alasan))
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

        $fabsenman_id       = intval(addslashes($_POST['fabsenman_id']));
        
        if($this->record->delete($fabsenman_id))
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
        
        echo $this->record->enumField('fabsenman', 'fabsenman_shift');
    }
                
}

/* End of file absenman.php */
/* Location: ./application/controllers/transaksi/absenman.php */