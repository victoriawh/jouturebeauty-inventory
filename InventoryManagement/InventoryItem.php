<?php
namespace InventoryManagement;

class InventoryItem{
    // attributes
    private $itemID;
    private $name;
    private $description;
    private $quantity;
    private $price;
    private $created_by;
    private $created_at;
    private $updated_at;

    public function __construct($itemID, $name, $description, $quantity, $price, $created_by, $created_at, $updated_at){
        $this->itemID=$itemID;
        $this->name=$name;
        $this->description=$description;
        $this->quantity=$quantity;
        $this->price=$price;
        $this->created_by=$created_by;
        $this->created_at=$created_at;
        $this->updated_at=$updated_at;
    }

    public function getItemId(){
        return $this->itemID;
    }

    public function getName(){
        return $this->name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getCreatedBy(){
        return $this->created_by;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function getUpdatedAt(){
        return $this->updated_at;
    }


}

?>