<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankModel extends CI_Model  
{
	public function add_whatsapp_messages($data) {
        // Check if the message already exists
		$db_bank = $this->load->database('bank_db', TRUE);

        $db_bank->where('message_id', $data['message_id']);
        $query = $db_bank->get('messages');
        if ($query->num_rows() == 0) {
            // Insert new message
            return $db_bank->insert('messages', $data);
        } else {
            return false; // Duplicate entry
        }
    }

	function select_query($query)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		return $db_bank->query($query);	
	}

	function select_fun($tbl,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($where!="")
		{
			$db_bank->where($where);
		}
		return $db_bank->get($tbl);	
	}
	function insert_fun($tbl,$dt)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->insert($tbl,$dt))
		{
			return $db_bank->insert_id();
		}
		else
		{
			return false;
		}
	}
	function edit_fun($tbl,$dt,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->update($tbl,$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function delete_fun($tbl,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->delete($tbl,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function select_fun_limit($tbl,$where,$get_limit='',$order_by='')
	{
		$db_bank = $this->load->database('db_bank', TRUE);
		if(!empty($where))
		{
			$db_bank->where($where);
		}
		if(!empty($order_by))
		{
			$db_bank->order_by($order_by[0],$order_by[1]);
		}
		if(!empty($get_limit))
		{
			$db_bank->limit($get_limit[0],$get_limit[1]);
		}
		return $db_bank->get($tbl);	
	}
}	