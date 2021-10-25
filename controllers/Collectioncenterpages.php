<?php
  class Collectioncenterpages extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->productModel = $this->model('Products');
    }
    
    public function home(){
      $products = $this->productModel->getProducts($_SESSION['user_id']);

      $data = [
        'products' => $products,
      ];
     
      $this->view('collection center/home', $data);
    }
   public function addProduct(){
      $productList=$this->productModel->getProductList();
      $farmerList=$this->productModel->getFarmersList();
      
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $data = [
        'n' => $productList,
        'm' => $farmerList,
        'name'=> $_POST['product']
      ];
      $product=$this->productModel->getproductname($data['name']);
      $name1=$product->name;
      $data = [
        'n' => $productList,
        'm' => $farmerList,
        'name'=> $_POST['product'],
        'name1'=> $name1
      ];

      $this->view('collection center/addproduct', $data);
    }

    }
    public function addProductSubmit(){
      $productList=$this->productModel->getProductList();
      $farmerList=$this->productModel->getFarmersList();
     
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data =[
          'n' => $productList,
          'm' => $farmerList,
          'name'=> trim($_POST['product_name']),
          'quantity' => trim($_POST['quantity']),
          'farmer' => trim($_POST['farmer']),
          'rate' => trim($_POST['rate']),
          'date' => trim($_POST['date'])
        ];
        $max_rate=$this->productModel->getproductname($data['name']);
        $max_rate_n=$max_rate->maximum_buying_rate;
        $today=date("Y-m-d") ;
        if($max_rate_n<$data['rate']){
         $this->view('collection center/error');
        }
        else if($today!=$data['date']){
          $this->view('collection center/error');
        }
        else if($this->productModel->addBought($data) && $this->productModel->updateStock($data)){
          redirect('collectioncenterpages/home');
          //flash('add_success', 'We will serve for your requeest');
        }


      }
      else{
        $data =[    
          'n' => $productList,
          'm' => $farmerList,
          'name'=> $s,
          'quantity' => '',
          'farmer' =>'',
          'rate' => '',
          'date' => '',
          
          'quantity_err'=>'',
          'farmer_err'=>'',
          'rate_err'=>'',
        ];

        // Load view
        $this->view('collection center/addproduct', $data);
      }
  }
  
  public function assignorder(){
      $data =[
        'id'=> $_GET['id'],
        'order' =>'',
        'error'=>''
      ];
      $order=$this->productModel->getorder($data['id']);

      $data =[
        'id'=> $_GET['id'],
        'order' =>$order,
      ];
      $this->view('collection center/order_completion',$data);
  }   

  public function assignment(){
    $id=$_POST['order_id'];
    $delivery_date=$_POST['date'];
    if($delivery_date<=date("Y-m-d")){
      $this->view('collection center/error');
    }
    else{
    foreach ($_POST as $key=>$value):
      if($key!='order_id' || $key!='date'){
   // $stock = $this->productModel->checkstock($key);
  //  $stockn=$stock->quantity
      $this->productModel->updateorder($key,$id,$value);
      $this->productModel->reduceStock($key,$value);
     
     }
     
      
    endforeach;
  if($value=1){
    $assigned_date=date("Y/m/d");
    $this->productModel->updatedeliveryDate($id,$assigned_date,$delivery_date);
    $this->assignedordersmore($id);
  }
}
  }
  public function pendingorders(){
    $result= $this->productModel->pendingorders();

    $data = [
      'result'=>$result
    ];
   
    $this->view('collection center/pending_orders',$data);
  }
  public function completedorders(){
    $data = [
    ];
   
    $this->view('collection center/completed_orders');
  }
  public function ordercompletion(){
    $data = [
    ];
   
    $this->view('collection center/order_completion');
  }
   public function deliveredorders(){
    $data = [
    ];
   
    $this->view('collection center/delivered_orders');
  }
  
  public function deliveredordersmore(){
    $data = [
    ];
   
    $this->view('collection center/delivered_orders_more');
  }
  public function farmers(){
    $data = [
    ];
   
    $this->view('collection center/farmers');
  }
  public function addfarmers(){
    $data = [
    ];
   
    $this->view('collection center/add_farmer');
  }
  public function nonlistedboughts(){
    $data = [
    ];
   
    $this->view('collection center/non-listed-boughts');
  }
  public function paymentmanagement(){
    $data = [
    ];
   
    $this->view('collection center/payment_management');
  }
  public function employeeSalary(){
    $data = [
    ];
   
    $this->view('collection center/employee_salary');
  }
  public function assignedordersmore($id = 202){
    $order=$this->productModel->assignordermore($id);
    $data = [
      'order'=>$order
    ];
   
    $this->view('collection center/assignedordersmore',$data);
  
  }
  public function addExcess(){
    $data = [
    ];
   
    $this->view('collection center/add_excess');
  }
  public function excessAssignment(){
    $data = [
    ];
   
    $this->view('collection center/excess_assignment');
  }
  public function excessMore(){
    $data = [
    ];
   
    $this->view('collection center/excess_more');
  }
  public function orderNeccesity(){
    $data = [
    ];
   
    $this->view('collection center/order_neccesity');
  }
  public function pendingNeccesity(){
    $data = [
    ];
   
    $this->view('collection center/pending_neccesity');
  }
  public function neccesityMore(){
    $data = [
    ];
   
    $this->view('collection center/neccesity_more');
  }
  public function reject(){
    $data = [
    ];
   
    $this->view('collection center/reject');
  }
  public function employee(){
    $data = [
    ];
   
    $this->view('collection center/employee');
  }
  public function error(){
    $data = [
    ];
   
    $this->view('collection center/error');
  }
  public function edit(){
    $data = [
    ];
   
    $this->view('collection center/edit_farmer');
  }
  public function editEmployee(){
    $data = [
    ];
   
    $this->view('collection center/edit_employee');
  }
  public function addemployee(){
    $data = [
    ];
   
    $this->view('collection center/add_employee');
  }
  public function productRequest(){
    $data = [
    ];
   
    $this->view('collection center/product_request');
  }






  



   
  }
  ?>