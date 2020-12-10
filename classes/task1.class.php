<?php
class task1 extends DbConn
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



    public function getData(){
        $sql = "SELECT 
                        cat.Name AS `Category Name`, COUNT(itcat.ItemNumber) AS `Total Items`
                    FROM
                        `category` `cat`
                            LEFT JOIN
                        `item_category_relations` `itcat` ON `cat`.`Id` = `itcat`.`categoryId`
                    GROUP BY cat.Name
                    ORDER BY `Total Items` DESC";
        $result = $this->query($sql);
        
        return $result;

    }   

}
?>