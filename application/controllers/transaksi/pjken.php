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
        
        if (isset($_GET['grid']))
        {
            echo $this->record->index(); 
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

        $fpjken_tanggal      = addslashes($_POST['fpjken_tanggal']);
        $fpjken_nik          = addslashes($_POST['fpjken_nik']);
        $fpjken_bagian       = intval(addslashes($_POST['fpjken_bagian']));
        $fpjken_pinjam       = addslashes($_POST['fpjken_pinjam']);
        $fpjken_mobil        = addslashes($_POST['fpjken_mobil']);
        $fpjken_keperluan    = addslashes($_POST['fpjken_keperluan']);
        
        if($this->record->create($fpjken_tanggal, $fpjken_nik, $fpjken_bagian, 
                                $fpjken_pinjam, $fpjken_mobil, $fpjken_keperluan))
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
        
        if($this->record->update($fpjken_id, $fpjken_tanggal, $fpjken_nik, $fpjken_bagian, 
                                $fpjken_pinjam, $fpjken_mobil, $fpjken_keperluan))
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
        
        echo $this->record->enumField('fpjken', 'fpjken_jenis');
    }
                
}

/* End of file pjken.php */
/* Location: ./application/controllers/transaksi/pjken.php */