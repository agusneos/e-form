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
        
        if (isset($_GET['grid']))
        {
            echo $this->record->index(); 
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

        $fcuti_tanggal      = addslashes($_POST['fcuti_tanggal']);
        $fcuti_nik          = addslashes($_POST['fcuti_nik']);
        $fcuti_bagian       = intval(addslashes($_POST['fcuti_bagian']));
        $fcuti_dari         = addslashes($_POST['fcuti_dari']);
        $fcuti_sampai       = addslashes($_POST['fcuti_sampai']);
        $fcuti_keperluan    = addslashes($_POST['fcuti_keperluan']);
        
        if($this->record->create($fcuti_tanggal, $fcuti_nik, $fcuti_bagian, 
                                $fcuti_dari, $fcuti_sampai, $fcuti_keperluan))
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
        
        if($this->record->update($fcuti_id, $fcuti_tanggal, $fcuti_nik, $fcuti_bagian, 
                                $fcuti_dari, $fcuti_sampai, $fcuti_keperluan))
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
        
        echo $this->record->enumField('fcuti', 'fcuti_jenis');
    }
                
}

/* End of file cuti.php */
/* Location: ./application/controllers/transaksi/cuti.php */