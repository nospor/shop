<?php

namespace Plane\Shop;

use Plane\Shop\PriceFormat\PriceFormatInterface;

/**
 * Description of Shipping
 *
 * @author Dariusz Korsak <dkorsak@gmail.com>
 * @package Plane/Shop
 */
class Shipping implements ShippingInterface
{
    private $id;
    
    private $name;
    
    private $description;
    
    private $cost;
    
    private $priceFormat;
    
    public function __construct(array $data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getCost()
    {
        if (!is_null($this->priceFormat)) {
            return $this->priceFormat->formatPrice($this->cost);
        }
        
        return $this->cost;
    }
    
    public function setCost($cost)
    {
        $this->cost = $cost;
    }
    
    public function setPriceFormat(PriceFormatInterface $priceFormat)
    {
        $this->priceFormat = $priceFormat;
    }
}