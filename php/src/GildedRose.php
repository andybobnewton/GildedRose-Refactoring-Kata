<?php

declare(strict_types=1);

namespace GildedRose;
enum QualtityProcessCategory: string
{
    case BackStagePass = 'BackStagePass';
    case AgedProduce = 'AgedProduce';
    case Expirable = 'Expirable';
    case Conjured = 'Conjured';
    case Constant = 'Constant';
}

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->itemUpdate($item);
        }
    }
    #should ideally be a property on the item but told to leave that alone, parsing for now
    public function identifyCategory(Item $item): QualtityProcessCategory{
        if (preg_match("/^Aged\s/i", $item->name)){
            return QualtityProcessCategory::AgedProduce;
        }
        if (preg_match("/^Sulfuras/i", $item->name)){
            return QualtityProcessCategory::Constant;
        }
        if (preg_match("/^Backstage\spass/i", $item->name)){
            return QualtityProcessCategory::BackStagePass;
        }
        if (preg_match("/^Conjured/i", $item->name)){
            return QualtityProcessCategory::Conjured;
        }
        return QualtityProcessCategory::Expirable;
    }

    #should be on the item class but told to leave that alone
    public function itemUpdate ( Item $item, ): void 
    {
        #should really be a property of item but told to leave that alone, parsing name for now
        $item_category = $this->identifyCategory($item);

        $quality_decrement = $item->sell_in <= 0 ? 2 : 1; # default to degrade over time, use negative to increase quality
        $sell_in_decrement = 1; 
        #could move these rules into a db table linked to class of product in future revision
        switch ($item_category) {
            case QualtityProcessCategory::Constant:
                #quality does not degrade, can be sold at any point
                $quality_decrement = 0;
                $sell_in_decrement = 0;
                break;
            case QualtityProcessCategory::Conjured:
                $quality_decrement = $quality_decrement * 2;
                break;
            case QualtityProcessCategory::AgedProduce:
                $quality_decrement = ($item->sell_in <= 0) ? -2 : -1;
                break;
            case QualtityProcessCategory::BackStagePass:
                $quality_decrement = -1;
                if ($item->sell_in <= 0){
                    $quality_decrement = $item->quality; #wipe out quality, event is over
                } elseif ($item->sell_in <= 5){
                    $quality_decrement = -3;
                } elseif ($item->sell_in <= 10) {
                    $quality_decrement = -2;
                } 
                break;
            default:
                break;
        }

        $item->sell_in = $item->sell_in - $sell_in_decrement;
        $item->quality = $item->quality - $quality_decrement;
        if ($item->quality < 0) $item->quality = 0;
        if ($item->quality > 50 && $item_category != QualtityProcessCategory::Constant) $item->quality = 50 ;
    }
}
