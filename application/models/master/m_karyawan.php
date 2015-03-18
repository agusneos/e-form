<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_karyawan extends CI_Model
{    
    static $table1 = 'karyawan';
    static $table2 = 'departemen';

    public function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'karyawan_nik';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            //print_r ($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'beginwith'){
                        $cond .= " and ($field like '$value%')";
                    } else if ($op == 'endwith'){
                        $cond .= " and ($field like '%$value')";
                    } else if ($op == 'equal'){
                        $cond .= " and $field = $value";
                    } else if ($op == 'notequal'){
                        $cond .= " and $field != $value";
                    } else if ($op == 'less'){
                        $cond .= " and $field < $value";
                    } else if ($op == 'lessorequal'){
                        $cond .= " and $field <= $value";
                    } else if ($op == 'greater'){
                        $cond .= " and $field > $value";
                    } else if ($op == 'greaterorequal'){
                        $cond .= " and $field >= $value";
                    } else if ($op == 'is'){
                        $cond .= " and $field IS $value";
                    }
                }
            }
	}
        
        $this->db->select('karyawan_nik, karyawan_nama, karyawan_bagian, 
                        c.departemen_nama as "c.departemen_nama", 
                        b.departemen_nama as "b.departemen_nama"', NULL);
        $this->db->join(self::$table2.' b', 'a.karyawan_bagian = b.departemen_id', 'left')
                 ->join(self::$table2.' c', 'b.departemen_induk = c.departemen_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        
        $total  = $this->db->count_all_results(self::$table1.' a');
        //---------------------------------------------------------//
        $this->db->select('karyawan_nik, karyawan_nama, karyawan_bagian, 
                        c.departemen_nama as "c.departemen_nama", 
                        b.departemen_nama as "b.departemen_nama"', NULL);
        $this->db->join(self::$table2.' b', 'a.karyawan_bagian = b.departemen_id', 'left')
                 ->join(self::$table2.' c', 'b.departemen_induk = c.departemen_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table1.' a');
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result["total"] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }   
        
    function create($nik, $nama, $bagian)
    {
        $this->db->where('karyawan_nik', $nik);
        $res = $this->db->get(self::$table1);
        
        if($res->num_rows == 0) //cek apakah nik sudah ada
        {            
            return $this->db->insert(self::$table1,array(
                'karyawan_nik'      => $nik,
                'karyawan_nama'     => $nama,
                'karyawan_bagian'   => $bagian         
            ));
        }
        else
        {
            return false;
        }
        
    }
    
    function update($nik, $nama, $bagian)
    {
        $this->db->where('karyawan_nik', $nik);
        return $this->db->update(self::$table1,array(
            'karyawan_nama'     => $nama,
            'karyawan_bagian'   => $bagian
        ));
    }
    
    function delete($nik)
    {
        return $this->db->delete(self::$table1, array('karyawan_nik' => $nik)); 
    }  
    
    function getDept()
    {        
        $this->db->select('a.departemen_id as id, b.departemen_nama as departemen, a.departemen_nama as bagian');
        $this->db->join(self::$table2.' b', 'a.departemen_induk = b.departemen_id', 'left');
        $this->db->where('a.departemen_induk > 0');
        $this->db->order_by('a.departemen_induk', 'asc')
                 ->order_by('a.departemen_nama', 'asc');
        $query  = $this->db->get(self::$table2.' a');
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
        
}

/* End of file m_karyawan.php */
/* Location: ./application/models/master/m_karyawan.php */