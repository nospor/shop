<?php

namespace Plane\Shop;

use Plane\Shop\PriceFormat\PriceFormatInterface;

/**
 * Interface for shipping classes
 *
 * @author Dariusz Korsak <dkorsak@gmail.com>
 * @package Plane\Shop
 */
interface ShippingInterface
{
    public function getId();
    
    public function getName();
    
    public function getDescription();
    
    public function getCost();
    
    public function setCost($cost);
    
    public function setPriceFormat(PriceFormatInterface $priceFormat);
}