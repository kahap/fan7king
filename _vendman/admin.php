<?php
    session_start();
   
    require_once('model/require_general.php');
    foreach($_GET as $key => $value){
        $$key = $value;
    }
    
    if(isset($_SESSION['supplieruserdata'])){
        
        include('view/index_header.html');      
        include('view/index_left.php');    
        include('view/index_top.html');
    
        if(isset($page)){
            switch($page){
                //變更密碼
                case "setup":
                      include "view/setup.php";
                      break ;
                      //變更確認碼
                case "changeCP":
                      include "view/changecp.php";
                      break ;
                //供應商管理 encore
                case "supplier":
                    if (isset($action)) {
                        switch ($action) {
                            //瀏覽
                            case "view":
                                include "view/page_supplier_view.php";
                                break;
                            //修改
                            case "edit":
                                include "view/page_supplier_edit.php";
                                break;
                            //新增
                            case "insert":
                                include "view/page_supplier_insert.php";
                                break;                              
                        }
                    } else {
                        include('view/page_supplier.php');
                    }
                    break;
                //商品管理
                case "product":                
                        if(isset($type)){
                            switch($type){
                                //分類管理
                                case "general":
                                    if(isset($which)){
                                        switch($which){
                                            case "category":
                                                if(isset($action)){
                                                    switch($action){
                                                        case "view":
                                                            include "view/page_category_view.php";
                                                            break;
                                                        case "edit":
                                                        case "insert":
                                                            include "view/page_category_edit.php";
                                                            break;
                                                    }
                                                }else{
                                                    include "view/page_category.php";
                                                }
                                                break;
                                            case "brand":
                                                include "view/page_brand.php";
                                                break;
                                        }
                                    }else{
                                        include "view/page_category.php";
                                        break;
                                    }
                                    break;
                                //商品上架管理
                                case "productManage":
                                    if(isset($action)){
                                        switch($action){
                                            //view
                                            case "view":
                                                include "view/page_productManage_view.php";
                                                break;
                                            //insert
                                            case "insert":
                                                include "view/page_productManage_edit.php";                                             
                                                break;
                                            //edit
                                            case "edit":
                                                include "view/page_productManage_edit.php";
                                                break;
                                        }
                                    }else if(isset($special)){
                                        include "view/page_productManage_order.php";
                                    }else{
                                        include "view/page_productManage.php";
                                    }
                                    break;
                                //商品管理
                                case "product":
                                    if(isset($action)){
                                        switch($action){
                                            //view
                                            case "view":
                                                include "view/page_product_view.php";
                                                break;
                                            //insert
                                            case "insert":
                                                include "view/page_product_edit.php";
                                                break;
                                            //edit
                                            case "edit":
                                                include "view/page_product_edit.php";
                                                break;
                                        }
                                    }else{
                                        include "view/page_product.php";
                                    }
                                    break;
                            }
                        }else{
                            include "view/page_product.php";
                        }
                
                    break;
            
                
                //訂單+進件
                case "order":
                
                        if(isset($action)){
                            switch($action){
                                case "view":
                                    
                                        include "view/page_order_view.php";
                                    
                                    break;
                                case "query":
                                    if(isset($method)){
                                        switch($method){
                                            case 0:
                                                
                                                    include "view/page_order_query_direct.php";
                                                
                                                break;
                                            case 1:
                                                
                                                    include "view/page_order_query.php";
                                                
                                                break;
                                        }
                                    }else{
                                        include "view/page_order_query.php";
                                    }
                                    break;
                            }
                        }else if(isset($method)){
                            switch($method){
                                case "1":
                                    
                                            
                                                include "view/page_order_period.php";
                                            
                                    
                                    
                                    break;
                                case "0":
                                    
                                        include "view/page_order_direct.php";
                                
                                    break;
                            }
                        }else{
                            include "view/page_order_period.php";
                        }
                    
                break;              
            
            }
        }else{
            include('view/plain_page.html');
        }
        include('view/index_footer.html');
    }else{
        echo "<script>location.href='index.php';</script>";
    }
?>