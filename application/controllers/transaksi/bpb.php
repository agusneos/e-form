<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bpb extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_bpb','record');
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
            $this->load->view('transaksi/v_bpb');      
        }
    } 
    
    function create()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fbpb_tanggal      = addslashes($_POST['fbpb_tanggal']);
        $fbpb_nik          = addslashes($_POST['fbpb_nik']);
        $fbpb_bagian       = intval(addslashes($_POST['fbpb_bagian']));
        
        if($this->record->create($fbpb_tanggal, $fbpb_nik, $fbpb_bagian))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function update($fbpb_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fbpb_tanggal      = addslashes($_POST['fbpb_tanggal']);
        $fbpb_nik          = addslashes($_POST['fbpb_nik']);
        $fbpb_bagian       = intval(addslashes($_POST['fbpb_bagian']));
        
        if($this->record->update($fbpb_id, $fbpb_tanggal, $fbpb_nik, $fbpb_bagian))
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

        $fbpb_id       = intval(addslashes($_POST['fbpb_id']));
        
        if($this->record->delete($fbpb_id) && $this->record->deleteChild($fbpb_id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    
//-------------DETAIL-------------//
    
    function index_detail()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid']))
        {
            echo $this->record->index_detail($_GET['nilai']); 
        }
        else 
        {
            $this->load->view('transaksi/v_bpb');            
        }
    }
    
    function detailCreate()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fbpb_detail_header     = intval(addslashes($_POST['fbpb_detail_header']));
        $fbpb_detail_barang     = addslashes($_POST['fbpb_detail_barang']);
        $fbpb_detail_qty        = addslashes($_POST['fbpb_detail_qty']);
        $fbpb_detail_digunakan  = addslashes($_POST['fbpb_detail_digunakan']);
        $fbpb_detail_stock      = addslashes($_POST['fbpb_detail_stock']);
        $fbpb_detail_pemakaian  = addslashes($_POST['fbpb_detail_pemakaian']);
        $fbpb_detail_ket        = addslashes($_POST['fbpb_detail_ket']);
        
        if($this->record->detailCreate($fbpb_detail_header, $fbpb_detail_barang, $fbpb_detail_qty, $fbpb_detail_digunakan,
                                 $fbpb_detail_stock, $fbpb_detail_pemakaian, $fbpb_detail_ket))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }     
    
    function detailUpdate($fbpb_detail_id=null)
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();
        
        $fbpb_detail_header     = intval(addslashes($_POST['fbpb_detail_header']));
        $fbpb_detail_barang     = addslashes($_POST['fbpb_detail_barang']);
        $fbpb_detail_qty        = addslashes($_POST['fbpb_detail_qty']);
        $fbpb_detail_digunakan  = addslashes($_POST['fbpb_detail_digunakan']);
        $fbpb_detail_stock      = addslashes($_POST['fbpb_detail_stock']);
        $fbpb_detail_pemakaian  = addslashes($_POST['fbpb_detail_pemakaian']);
        $fbpb_detail_ket        = addslashes($_POST['fbpb_detail_ket']);
        
        if($this->record->detailUpdate($fbpb_detail_id, $fbpb_detail_header, $fbpb_detail_barang, $fbpb_detail_qty,
                                 $fbpb_detail_digunakan, $fbpb_detail_stock, $fbpb_detail_pemakaian, $fbpb_detail_ket))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
        
    function detailDelete()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $fbpb_detail_id       = intval(addslashes($_POST['fbpb_detail_id']));
        
        if($this->record->detailDelete($fbpb_detail_id))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    /////////////////////    
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
        
        echo $this->record->enumField('fbpb', 'fbpb_jenis');
    }
                
}

/* End of file bpb.php */
/* Location: ./application/controllers/transaksi/bpb.php */