<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Upload extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model_csv');
            $this->load->model('model_stores');
            $this->load->model('model_products');
            $this->load->library('csvimport');
        }

        public function index()
        {
                $this->load->view('products/index', array('error' => ' ' ));
        }

        public function do_upload()
        {
                $config['upload_path'] = 'assets/csv/';
                $config['allowed_types'] = 'csv';

                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile'))
                {
                    $error = $this->upload->display_errors();
                    //var_dump($error);
                    $this->session->set_flashdata('error', $error);
                    redirect('products/', 'refresh');
                }
                else
                {
                    $file_data = $this->upload->data();
                    $file_path =  './assets/csv/'.$file_data['file_name'];
                    if($this->csvimport->get_array($file_path, "", TRUE)){
                        $csv_array = $this->csvimport->get_array($file_path);
                        foreach ($csv_array as $row) {
                            switch($row['status']){
                                case 'discounted':
                                    $row['status'] = 3;
                                    break;
                                case 'sold out':
                                    $row['status'] = 2;
                                    break;
                                default:
                                    $row['status'] = 1;
                                    break;
                            }
                            $storeId = $this->model_stores->getStoreId($row['storeName']);
                            $storeId = $storeId[0]['id'];
                            //var_dump($row['image'] == null || $row['image'] == '');
                            $img = $row['image'];
                            if($img == ''){
                                 $img = "assets/images/tempPhoto.png";
                            }
                            $test1 = $this->model_products->isDuplicateName($row['Name']);
                            $test2 = $this->model_products->isDuplicateSku($row['sku']);
                            
                            if($test1 < 1 && $test2 < 1){
                                $insert_data = array(
                                    'name' => $row['Name'],  
                                    'sku' => $row['sku'],  
                                    'price'  => $row['price'],  
                                    'qty' => $row['qty'],  
                                    'image' => $img,  
                                    'description' => $row['Desc'],  
                                    'attribute_value_id' => $row['attribute_value_id'],  
                                    'brand_id' => $row['brand_id'], 
                                    'category_id' => $row['category_id'],  
                                    'store_id' => $storeId,  
                                    'availability' => $row['status']
                                );
                                $this->model_csv->insert_csv($insert_data);
                            }else{
                                $error = 'Cannot have duplicate item names or product codes.';
                                $this->session->set_flashdata('error', $error);
                                redirect('products/', 'refresh');
                            }   
                                                 
                        }
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                    }
                    
                    $this->session->set_flashdata('success', 'Successfully updated');
                    redirect('products/', 'refresh');
                }
        }
}

