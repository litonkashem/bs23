<?php
class task2 extends DbConn
{
    
    public $debugmode = false;

    public function __construct(){
        parent::__construct();
        // calling parent constructor 
        // for getting connection
        // $this->conn = new DbConn;
    }
    public function __destruct(){

    }



    public function getTree(){
        $sql = "SELECT categoryId, ParentcategoryId FROM catetory_relations";
        $alldata = $this->sqlToJson($sql);
        $alldata =  json_decode( $alldata, true);


        $childs = array();
        $subchilds = array();

        foreach($alldata as $item){
            $item = (object) $item;
            $childs[$item->ParentcategoryId][] = $item;
        }

        // echo "string-----------".json_encode($childs);
        foreach($alldata as $item){
            $item = (object) $item;
            if (isset($childs[$item->categoryId])){
                $item->childs = $childs[$item->categoryId];
                $subchilds[$item->categoryId] = $childs[$item->categoryId];
            }
        }
                // echo "string-----------".json_encode($subchilds);

        foreach($childs as $fieldname => $child){
            $sql1 = "SELECT Name FROM category WHERE Id = '$fieldname'";
            $result1 = $this->query($sql1);
            $Name =  $result1->fetch_assoc()['Name'];

            echo $Name.'('.$main_cat_tot_number.')'.'</br>';
            $main_cat_tot_number = 0;

            foreach($child as $indx){

                foreach($subchilds as $subfieldname => $subchild){
                    $categoryId = $indx->categoryId;
                    if($subfieldname == $categoryId){
                        $ParentcategoryId = $indx->ParentcategoryId;
                        $sql2 = "SELECT Name FROM category WHERE Id = '$categoryId'";
                        $result2 = $this->query($sql2);
                        $Name =  $result2->fetch_assoc()['Name'];

                        $sql3 = "SELECT COUNT(itcat.ItemNumber) AS `Total_Items` FROM
                                `category` `cat`
                                    LEFT JOIN
                                `item_category_relations` `itcat` ON `cat`.`Id` = `itcat`.`categoryId`
                            WHERE `itcat`.`categoryId` = '$categoryId'";
                        $result3 = $this->query($sql3);
                        $Total_Items =  $result3->fetch_assoc()['Total_Items'];
                        echo '---'.$Name.'('.$Total_Items.')'. '</br>';
                        foreach($subchild as $indxw){
                            $subcategoryId = $indxw->categoryId;
                            $sql4 = "SELECT Name FROM category WHERE Id = '$subcategoryId'";
                            $result4 = $this->query($sql4);
                            $Name =  $result4->fetch_assoc()['Name'];

                            $sql5 = "SELECT COUNT(itcat.ItemNumber) AS `Total_Items` FROM
                                    `category` `cat`
                                        LEFT JOIN
                                    `item_category_relations` `itcat` ON `cat`.`Id` = `itcat`.`categoryId`
                                WHERE `itcat`.`categoryId` = '$subcategoryId'";
                            $result5 = $this->query($sql5);
                            $Total_Items =  $result5->fetch_assoc()['Total_Items'];
                            echo '----------'.$Name.'('.$Total_Items.')'. '</br>';
                        }
                    }
                }

                // $this->getsubcat();

                $main_cat_tot_number += $Total_Items;
            }
            
        }

    }   





}
?>