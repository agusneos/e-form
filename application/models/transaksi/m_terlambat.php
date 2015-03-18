<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_terlambat extends CI_Model
{    
    static $table1      = 'fterlambat';
    static $table2      = 'karyawan';
    static $table3      = 'departemen';
    static $table4      = 'user';

    public function __construct() {
        parent::__construct();
        $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }

    function index($dept=null)
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'fterlambat_id';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        
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
        
        $pecah      = explode(',', $dept);
        $a          = $pecah[0];
        $b          = $pecah[1];
        $c          = $pecah[2];
        $d          = $pecah[3];
        $e          = $pecah[4];
        $f          = $pecah[5];
        $g          = $pecah[6];
        $h          = $pecah[7];
        $i          = $pecah[8];
        $j          = $pecah[9];
        $k          = $pecah[10];
        $aray_dept  = array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k);
        
        $this->db->select('a.*, c.departemen_nama as "c.departemen_nama", 
                        b.departemen_nama as "b.departemen_nama", 
                        d.karyawan_nama as "d.karyawan_nama", e.name as "e.name", 
                        f.name as "f.name", g.name as "g.name", h.name as "h.name"', NULL);
        $this->db->join(self::$table3.' b', 'a.fterlambat_bagian = b.departemen_id', 'left')
                 ->join(self::$table3.' c', 'b.departemen_induk = c.departemen_id', 'left')
                 ->join(self::$table2.' d', 'a.fterlambat_nik = d.karyawan_nik', 'left')
                 ->join(self::$table4.' e', 'a.fterlambat_disetujui = e.id', 'left')
                 ->join(self::$table4.' f', 'a.fterlambat_diketahui = f.id', 'left')
                 ->join(self::$table4.' g', 'a.fterlambat_ditolak = g.id', 'left')
                 ->join(self::$table4.' h', 'a.fterlambat_pembuat = h.id', 'left');
        $this->db->where_in('b.departemen_induk', $aray_dept);
        $this->db->where($cond, NULL, FALSE);
        
        $total  = $this->db->count_all_results(self::$table1.' a');
        //---------------------------------------------------------//
        $this->db->select('a.*, c.departemen_nama as "c.departemen_nama", 
                        b.departemen_nama as "b.departemen_nama", 
                        d.karyawan_nama as "d.karyawan_nama", e.name as "e.name", 
                        f.name as "f.name", g.name as "g.name", h.name as "h.name"', NULL);
        $this->db->join(self::$table3.' b', 'a.fterlambat_bagian = b.departemen_id', 'left')
                 ->join(self::$table3.' c', 'b.departemen_induk = c.departemen_id', 'left')
                 ->join(self::$table2.' d', 'a.fterlambat_nik = d.karyawan_nik', 'left')
                 ->join(self::$table4.' e', 'a.fterlambat_disetujui = e.id', 'left')
                 ->join(self::$table4.' f', 'a.fterlambat_diketahui = f.id', 'left')
                 ->join(self::$table4.' g', 'a.fterlambat_ditolak = g.id', 'left')
                 ->join(self::$table4.' h', 'a.fterlambat_pembuat = h.id', 'left');
        $this->db->where_in('b.departemen_induk', $aray_dept);
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
        
    function create($fterlambat_tanggal, $fterlambat_nik, $fterlambat_bagian, 
                    $fterlambat_shift, $fterlambat_waktu, $fterlambat_alasan, 
                    $fterlambat_keterangan, $user_id)
    {    
        return $this->db->insert(self::$table1,array(
            'fterlambat_tanggal'    => $fterlambat_tanggal,
            'fterlambat_nik'        => $fterlambat_nik,
            'fterlambat_bagian'     => $fterlambat_bagian,
            'fterlambat_shift'      => $fterlambat_shift,
            'fterlambat_waktu'      => $fterlambat_waktu,
            'fterlambat_alasan'     => $fterlambat_alasan,
            'fterlambat_keterangan' => $fterlambat_keterangan,
            'fterlambat_pembuat'    => $user_id
        ));
    }
    
    function update($fterlambat_id, $fterlambat_tanggal, $fterlambat_nik, 
                    $fterlambat_bagian, $fterlambat_shift, $fterlambat_waktu, 
                    $fterlambat_alasan, $fterlambat_keterangan)
    {
        $this->db->where('fterlambat_id', $fterlambat_id);
        return $this->db->update(self::$table1,array(
            'fterlambat_tanggal'    => $fterlambat_tanggal,
            'fterlambat_nik'        => $fterlambat_nik,
            'fterlambat_bagian'     => $fterlambat_bagian,
            'fterlambat_shift'      => $fterlambat_shift,
            'fterlambat_waktu'      => $fterlambat_waktu,
            'fterlambat_alasan'     => $fterlambat_alasan,
            'fterlambat_keterangan' => $fterlambat_keterangan
        ));
    }
    
    function delete($fterlambat_id)
    {
        return $this->db->delete(self::$table1, array('fterlambat_id' => $fterlambat_id)); 
    }
        
    function getKaryawan($dept=null, $q)
    {
        $pecah      = explode(',', $dept);
        $a          = $pecah[0];
        $b          = $pecah[1];
        $c          = $pecah[2];
        $d          = $pecah[3];
        $e          = $pecah[4];
        $f          = $pecah[5];
        $g          = $pecah[6];
        $h          = $pecah[7];
        $i          = $pecah[8];
        $j          = $pecah[9];
        $k          = $pecah[10];
        $aray_dept  = array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k);
        
        $this->db->select('karyawan_nik, karyawan_nama, karyawan_bagian, c.departemen_nama as "c.departemen_nama", b.departemen_nama as "b.departemen_nama"', NULL);
        $this->db->join(self::$table3.' b', 'a.karyawan_bagian = b.departemen_id', 'left')
                 ->join(self::$table3.' c', 'b.departemen_induk = c.departemen_id', 'left');
        $this->db->where_in('b.departemen_induk', $aray_dept);
        $this->db->like('karyawan_nama', $q);
        $this->db->order_by('karyawan_nama', 'asc');
        $query  = $this->db->get(self::$table2.' a');
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getDept()
    {        
        $this->db->select('a.departemen_id as id, b.departemen_nama as departemen, a.departemen_nama as bagian');
        $this->db->join(self::$table3.' b', 'a.departemen_induk = b.departemen_id', 'left');
        $this->db->where('a.departemen_induk > 0');
        $this->db->order_by('a.departemen_induk', 'asc');
        $this->db->order_by('a.departemen_nama', 'asc');
        $query  = $this->db->get(self::$table3.' a');
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function enumField($table, $field)
    {
        $enums = field_enums($table, $field);
        return json_encode($enums);
    }
    
    function getUser($id)
    {
        
        $this->db->where('id', $id);
        $query  = $this->db->get(self::$table4);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function disetujui($fterlambat_id, $fterlambat_disetujui)
    {
        $this->db->where('fterlambat_id', $fterlambat_id);
        return $this->db->update(self::$table1,array(
            'fterlambat_disetujui' => $fterlambat_disetujui
        ));
    }
    
    function diketahui($fterlambat_id, $fterlambat_diketahui)
    {
        $this->db->where('fterlambat_id', $fterlambat_id);
        return $this->db->update(self::$table1,array(
            'fterlambat_diketahui' => $fterlambat_diketahui
        ));
    }
    
    function ditolak($fterlambat_id, $fterlambat_ditolak, $fterlambat_keterangan)
    {
        $this->db->where('fterlambat_id', $fterlambat_id);
        return $this->db->update(self::$table1,array(
            'fterlambat_ditolak'     => $fterlambat_ditolak,
            'fterlambat_keterangan'  => $fterlambat_keterangan
        ));
    }
}

/* End of file m_ordcard.php */
/* Location: ./application/models/transaksi/terlambat.php */