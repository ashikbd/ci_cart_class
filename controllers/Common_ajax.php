<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_ajax extends CI_Controller {
	
	/*
	* This method can be used using ajax call.
	*/
    public function add_to_cart(){
        $product_code = $this->input->post('product_code');
        $qty = $this->input->post('qty');
        $replace = $this->input->post('replace');  
		// If $replace is true, item will be replaced with new one. Otherwise, only the quantity will be updated (if already exists in cart)
		// replace=false useful incase item is being added to cart with a button, without letting user to write quantity number manually
        if($product_code){
            $product = array();
			//  You can make a db query to retreive product related data. or use model
            $q = $this->db->select('*')->where("product_code",$product_code)->get("products");
            if($q->num_rows()){
                $prod = $q->row();
                $product = array(
                   "product_code" => $product_code,
                   "product_name" => $prod->name,
                   "product_qty" => $qty,
                   "product_unit_price" => $prod->price,
                   "product_price" => $prod->price * $qty
                );
				$this->load->library('Cart_lib','cart'); //Alternatively you can autoload
                $this->cart->add_to_cart($product, $replace);
            }else{
                return false;
            }
        }
    }
	
	/**
	*	This is a sample method to load mini cart that usually is loaded in header area of website. You can use an ajax call the load this cart to right place
	*/ 
    public function minicart(){
        $data['items'] = $this->cart->get_items();
        $data['total_price'] = $this->cart->get_total_price();
        $this->load->view('minicart',$data);
    }



}
