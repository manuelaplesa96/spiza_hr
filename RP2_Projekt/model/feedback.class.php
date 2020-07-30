<?php 

class Feedback{

    protected $id, $id_user, $id_restaurant, $content, $rating, $thumbs_up, $thumbs_down;

    public function __construct($id, $id_user, $id_restaurant, $content, $rating, $thumbs_up, $thumbs_down)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_restaurant = $id_restaurant;
        $this->content = $content;
        $this->rating = $rating;
        $this->thumbs_up = $thumbs_up;
        $this->thumbs_down = $thumbs_down;
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