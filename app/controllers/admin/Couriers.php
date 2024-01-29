<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Couriers extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if ($this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->lang->admin_load('sales', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('sales_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
        $this->load->library('attachments', [
            'path' => $this->digital_upload_path,
            'types' => $this->digital_file_types,
            'max_size' => $this->allowed_file_size,
        ]);
    }

    public function courier_list() {
        //die("courier_list new");
        ///$this->sma->checkPermissions();

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => admin_url('sales'), 'page' => lang('sales')], ['link' => '#', 'page' => lang('list_courier_list')]];
        $meta = ['page_title' => lang('gift_cards'), 'bc' => $bc];
        $this->page_construct('couriers/courier_list', $meta, $this->data);
    }

    public function getcouriers22() {
        $this->load->library('datatables');
        $this->datatables
                ->select($this->db->dbprefix('couriers') . '.id as id, name, phone_number, address, is_active, created_at, update_at')
                ->from('couriers')
                ->add_column('Actions', "<div class=\"text-center\"><a href='" . admin_url('couriers/view_courier/$1') . "' class='tip' title='" . lang('view_courier') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-eye\"></i></a> <a href='" . admin_url('sales/topup_gift_card/$1') . "' class='tip' title='" . lang('topup_gift_card') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-dollar\"></i></a> <a href='" . admin_url('sales/edit_gift_card/$1') . "' class='tip' title='" . lang('edit_gift_card') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang('delete_gift_card') . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_gift_card/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", 'id');
        //->unset_column('id');
        die("hello");
        echo $this->datatables->generate();
    }

    public function getcouriers() {
        
        
        $aColumns = ['couriers.id', 'couriers.name', 'couriers.phone_number', 'couriers.address', 'couriers.is_active', 'couriers.created_at'];
        $this->db->select('couriers.id, couriers.name, couriers.phone_number,couriers.address,couriers.is_active,couriers.created_at');
        

        $iStart = $this->input->get('iDisplayStart');
        $iPageSize = $this->input->get('iDisplayLength');

        if ($this->input->get('iSortCol_0') != null) { //iSortingCols
            $sOrder = "ORDER BY  ";

            for ($i = 0; $i < intval($this->input->get('iSortingCols')); $i++) {
                if ($this->input->get('bSortable_' . intval($this->input->get('iSortCol_' . $i))) == "true") {
                    $sOrder .= $aColumns[intval($this->input->get('iSortCol_' . $i))] . "
				 	" . $this->input->get('sSortDir_' . $i) . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = " id ASC";
            }
        }
        $OrderArray = explode(' ', $sOrder);

        $sKeywords = $this->input->get('sSearch');
        if ($sKeywords != "") {                 
            $this->db->group_start();
            $this->db->like('id', $sKeywords);
            $this->db->or_like('name', $sKeywords);
            $this->db->or_like('phone_number', $sKeywords);
            $this->db->or_like('address', $sKeywords);        
            $this->db->group_end();
        }
        if ($iStart != null && $iPageSize != '-1') {            
            $this->db->limit($iPageSize, $iStart);
        }        

        $order = trim($OrderArray[3]);
        $sort = trim($OrderArray[4]);
        $this->db->order_by($order, trim($sort));
        $salesData = $this->db->get('couriers')->result();      
        $iFilteredTotal = $this->db->count_all_results(); 
        $iTotal = $iFilteredTotal;
        /*echo '<pre>';
        print_r($salesData);
        exit;
        */
        //$salesData = $this->db->get();
       // $salesData = $query->result();
        $output = array(
            "sEcho" => intval($this->input->get('sEcho')),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($salesData as $aRow) {
            $id = $aRow->id;
            
            $created_at = date("M j, Y, g:i a", strtotime($aRow->created_at));


            ///$sOptions = "<div class=\"text-center\"><a href='" . admin_url('sales/view_gift_card/$1') . "' class='tip' title='" . lang('view_gift_card') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-eye\"></i></a> <a href='" . admin_url('sales/topup_gift_card/$1') . "' class='tip' title='" . lang('topup_gift_card') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-dollar\"></i></a> <a href='" . admin_url('sales/edit_gift_card/$1') . "' class='tip' title='" . lang('edit_gift_card') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang('delete_gift_card') . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_gift_card/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>";
            $is_active = ($aRow->is_active) ? 'active':'Inactive';
                $output['aaData'][] = array(
                $id,                                
                @utf8_encode($aRow->name),                
                @utf8_encode($aRow->phone_number),
                @utf8_encode($aRow->address),
                @utf8_encode($is_active),
                @utf8_encode($created_at)
            );
            ///  $i++;
        }

        echo json_encode($output);
    }

}
