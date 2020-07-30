<?php 
//  usklađeno sa novom bazom
class Restaurants{

    protected $id_restaurant, $username, $password, $name, $address, $email, $registration_sequance, $description, $has_registered;

    public function __construct($id, $username, $password, $name, $address, $email, $registration_sequance, $description, $has_registered)
    {
        $this->id_restaurant = $id;
        $this->username = $username;
        $this->password = $password; 
        $this->name = $name;
        $this->address = $address;
        $this->email = $email;
        $this->registration_sequance = $registration_sequance;
        $this->description = $description;
        $this->has_registerd = $has_registered;
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