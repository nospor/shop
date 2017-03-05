<?php

namespace Plane\Shop;

use DomainException;
use Plane\Shop\Validator\QuantityValidatorInterface;
use Plane\Shop\PriceFormat\PriceFormatInterface;

/**
 * Description of CartItem
 *
 * @author Dariusz Korsak <dkorsak@gmail.com>
 * @package Plane\Shop
 */
class CartItem implements CartItemInterface
{
    /**
     * Product object
     * @var \Plane\Shop\ProductInterface
     */
    private $product;
    
    /**
     * Quantity validator object
     * @var \Plane\Shop\QuantityValidatorInterface
     */
    private $quantityValidator;
    
    /**
     * Item quantity
     * @var int
     */
    private $quantity;
    
    /**
     * Price format object
     * @var \Plane\Shop\PriceFormat\PriceFormatInterface
     */
    private $priceFormat;
    
    /**
     * Constructor
     * @param \Plane\Shop\ProductInterface $product
     * @param integer $quantity
     * @param QuantityValidatorInterface $quantityValidator
     * @throws \DomainException
     */
    public function __construct(
        ProductInterface $product,
        $quantity = 1,
        QuantityValidatorInterface $quantityValidator = null
    ) {
        $this->product = $product;
        $this->quantityValidator = $quantityValidator;
        $this->quantity = (int) $quantity;
    }
    
    /**
     * Return product object
     * @return \Plane\Shop\ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Return id
     * @return int
     */
    public function getId()
    {
        return $this->product->getId();
    }
        
    /**
     * Return name
     * @return string
     */
    public function getName()
    {
        return $this->product->getName();
    }
    
    /**
     * Return path to image
     * @return string
     */
    public function getImagePath()
    {
        return $this->product->getImagePath();
    }
    
    /**
     * Return cart item quantity
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /**
     * Set cart item quantity
     * @param int $quantity
     * @throws \DomainException
     */
    public function setQuantity($quantity)
    {
        if (!$this->validateQuantity($quantity)) {
            throw new DomainException('Quantity of' . $quantity . ' is invalid');
        }
        
        $this->quantity = (int) $quantity;
    }
    
    /**
     * Increase cart item quantity
     * @param int $quantity
     */
    public function increaseQuantity($quantity)
    {
        $newQuantity = $this->quantity + (int) $quantity;
        
        $this->setQuantity($newQuantity);
    }
    
    /**
     * Decrease cart item quantity
     * @param int $quantity
     */
    public function decreaseQuantity($quantity)
    {
        $newQuantity = $this->quantity - (int) $quantity;
        
        $this->setQuantity($newQuantity);
    }
    
    /**
     * Return tax for single item
     * @return float
     */
    public function getTax()
    {
        return $this->product->getTax();
    }
            
    /**
     * Return tax for all items
     * @return float
     */
    public function getTaxTotal()
    {
        return $this->formatPrice((float) $this->getTax() * $this->quantity);
    }
    
    /**
     * Return product weight
     * @return float
     */
    public function getWeight()
    {
        return (float) $this->product->getWeight();
    }
    
    /**
     * Return weight for all items
     * @return float
     */
    public function getWeightTotal()
    {
        return (float) ($this->getWeight() * $this->quantity);
    }
    
    /**
     * Return single cart item price
     * @return float
     */
    public function getPrice()
    {
        return $this->formatPrice((float) $this->product->getPrice());
    }
    
    /**
     * Set price for item
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->product->setPrice($price);
    }
    
    /**
     * Return price for all items
     * @return float
     */
    public function getPriceTotal()
    {
        return $this->formatPrice((float) $this->getPrice() * $this->quantity);
    }
    
    /**
     * Return price including tax for single item
     * @return float
     */
    public function getPriceWithTax()
    {
        return $this->product->getPriceWithTax();
    }
    
    /**
     * Return price including tax for all items
     * @return float
     */
    public function getPriceTotalWithTax()
    {
        return $this->formatPrice((float) $this->getPriceTotal() + $this->getTaxTotal());
    }
    
    /**
     * Set price format object
     * @param \Plane\Shop\PriceFormat\PriceFormatInterface $priceFormat
     */
    public function setPriceFormat(PriceFormatInterface $priceFormat)
    {
        $this->priceFormat = $priceFormat;
        $this->product->setPriceFormat($priceFormat);
    }
    
    /**
     * Return object array representation
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $array['quantity']          = $this->getQuantity();
        $array['totalTax']          = $this->getTaxTotal();
        $array['priceTotal']        = $this->getPriceTotal();
        $array['priceTotalWithTax'] = $this->getPriceTotalWithTax();
        $array['product']           = $this->product->toArray();
        
        return $array;
    }
    
    /**
     * Validate quantity
     * @param int $quantity
     * @return boolean
     */
    protected function validateQuantity($quantity)
    {
        if (is_null($this->quantityValidator)) {
            return true;
        }
        
        return $this->quantityValidator->validate($this->product, $quantity);
    }
    
    /**
     * Format price with set price format object
     * @param float $price
     * @return float
     */
    protected function formatPrice($price)
    {
        if (is_null($this->priceFormat)) {
            return $price;
        }
        
        return $this->priceFormat->formatPrice($price);
    }
}
