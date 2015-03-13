<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_user extends CI_Model
{    
    static $table1 = 'user';
    static $table2 = 'departemen';
    
    public function __construct() {
        parent::__construct();
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
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
                    } 
                }
            }
	}
        
        $this->db->where($cond, NULL, FALSE);
        $this->db->from(self::$table1);
        $total  = $this->db->count_all_results();
        
        $this->db->where($cond, NULL, FALSE);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table1);
                   
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
    
    function create($name, $username, $pass, $level, $user_departemen)
    {
        $this->load->helper('security');
        $password = do_hash($pass,'md5');
        
        return $this->db->insert(self::$table1,array(
            'name'      => $name,
            'username'  => $username,
            'password'  => $password,
            'level'     => $level,
            'user_departemen'     => $user_departemen
        ));
    }
    
    function update($id, $name, $username, $level, $user_departemen)
    {        
        $this->db->where('id', $id);
        return $this->db->update(self::$table1,array(
            'name'      => $name,
            'username'  => $username,
            'level'     => $level,
            'user_departemen'     => $user_departemen
        ));
    }
    
    function reset($id, $pass)
    {
        $this->load->helper('security');
        $password = do_hash($pass, 'md5');
        
        $this->db->where('id', $id);
        return $this->db->update(self::$table1,array(
            'password'  => $password,            
        ));
    }
   
    function delete($id)
    {
        return $this->db->delete(self::$table1, array('id' => $id)); 
    }
    
    function getDept()
    {        
        $this->db->order_by('departemen_nama', 'asc');
        $this->db->where('departemen_induk', '0');
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
}

/* End of file m_user.php */
/* Location: ./application/models/admin/m_user.php */