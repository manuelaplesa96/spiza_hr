<?php 
// usklađeno sa novom bazom
class FoodType{

    protected $id_foodType, $name, $image_path ;

    public function __construct($id, $name, $image_path)
    {
        $this->id_foodType = $id;
        $this->name = $name;
        $this->image_path = $image_path;
    }
    

    public function __get( $property )
    {
        if( property_exists($this, $property))
            return $this->$property;
    }

    public function __set( $property, $value )
    {
        if( property_exists( $this, $property ) )
            $this->$property = $value;
        return $this;
    }

}

?>