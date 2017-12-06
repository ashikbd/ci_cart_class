<?php

/*
 * Simple cart library. Can be used with codeigniter 3 or other frameworks (modification needed. May be I will create another version). 
 * For codeigniter, place this file inside 'application/library' directory
 */

class Cart_lib {
    public $cart_items;
    public $CI;
    public function __construct() {
        //Assuming session is in autoload
        $this->CI = &get_instance();
        $cart_items = $this->CI->session->userdata("cart_items");
        $this->cart_items = $cart_items?$cart_items:array();
    }

    /*
     $product array should contain atleast these keys: product_code, product_name, product_qty, product_unit_price, product_price
     Extend array as you want. This is the main method for adding items in cart. If item already exists, it will update quanity
     */

    public function add_to_cart($product = array(), $replace = false){
        if(!$product)
            return false;

        $key = $this->check_product_exists($product['product_code']);

        if($key === FALSE){
            //Product doesn't exist in cart. So need to add it
            $this->save_item($product);
        }elseif($product['product_qty'] == 0 && $replace == true){
            //Product exists in cart. So need to update qty
            $this->remove_item_from_cart($key);
        }else{
            $this->update_qty($key, $product, $replace);
        }

    }
	/*
	* input product code to check if the product exists in cart and if exists return it's key in cart array
	*/
    public function check_product_exists($product_code){
        $key = array_search($product_code, array_column($this->cart_items, 'product_code'));
        return $key;
    }

    private function save_item($product){
        array_push($this->cart_items, $product);
        $this->CI->session->set_userdata("cart_items",$this->cart_items);
        return true;
    }

    private function update_qty($key, $updated_prod, $replace = false){
        $product = $this->cart_items[$key];
        if($replace){
            $product['product_qty'] = $updated_prod['product_qty'];
        }else{
            $product['product_qty'] = $product['product_qty'] + $updated_prod['product_qty'];
        }

        $product['product_unit_price'] = $updated_prod['product_unit_price']; //just to make sure incase unit price changes during this time

        //updating total product price
        $product['product_price'] = $product['product_qty'] * $product['product_unit_price'];

        $this->cart_items[$key] = $product;
        $this->CI->session->set_userdata("cart_items",$this->cart_items);
    }



    public function remove_item_from_cart($key){
        unset($this->cart_items[$key]);
        $this->CI->session->set_userdata("cart_items",$this->cart_items);
    }
	/*
	*	This method returns the whole cart array
	*/
    public function get_items(){
        return $this->cart_items;
    }

	/*
	*	This method returns total amount of all items
	*/
    public function get_total_price(){
        return array_sum(array_column($this->cart_items, 'product_price'));
    }
	
	/*
	* can be used for debugging purpose
	*/
    public function print_items(){
        print_r($this->cart_items);
    }
	
	/*
	*	This will clear the cart session
	*/
	
    public function empty_cart(){
        $this->CI->session->unset_userdata("cart_items");
        $this->cart_items = array();
    }
	
	/*
	*	This will return quantity of item. product code is the input
	*/
	
    public function get_product_qty($product_code){
        $key = $this->check_product_exists($product_code);
        if($key === FALSE){
            return 0;
        }else{
            $prod = $this->cart_items[$key];
            return $prod['product_qty'];
        }
    }

    //shipping not implemted yet
    public function get_total_shipping(){
        return 0;
    }
}
