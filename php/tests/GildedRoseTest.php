<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    // case AgedProduce = 'AgedProduce';
    // case Expirable = 'Expirable';
    public function testFirstStepMany(): void
    {
        $items = [
            new Item('Aged Brie', 10, 10),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 10),
            new Item('Backstage passes to a BMTH2022 concert', 8, 40),
            new Item('Sulfuras, Hand of Ragnaros', 1, 80),
            new Item('Cunjured fruit salad', 5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(9, $items[0]->sell_in);
        $this->assertSame(11, $items[0]->quality);

        $this->assertSame(14, $items[1]->sell_in);
        $this->assertSame(11, $items[1]->quality);
        

        $this->assertSame(7, $items[2]->sell_in);
        $this->assertSame(42, $items[2]->quality);
        

        $this->assertSame(1, $items[3]->sell_in);
        $this->assertSame(80, $items[3]->quality);
        

        $this->assertSame(4, $items[4]->sell_in);
        $this->assertSame(18, $items[4]->quality);

    }
    public function testBackStagePassesManyStages(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 10),
            new Item('Backstage passes to a BMTH2022 concert', 8, 40),
            new Item('Backstage passes to a MP2022 concert', 3, 45),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        #check first step
        $this->assertSame(14, $items[0]->sell_in);
        $this->assertSame(11, $items[0]->quality);

        $this->assertSame(7, $items[1]->sell_in);
        $this->assertSame(42, $items[1]->quality);

        $this->assertSame(2, $items[2]->sell_in);
        $this->assertSame(48, $items[2]->quality);
        
        for ($iteration=0; $iteration < 4; $iteration++) { 
            $gildedRose->updateQuality();
        }
        #check final step
        $this->assertSame(10, $items[0]->sell_in);
        $this->assertSame(15, $items[0]->quality);

        $this->assertSame(3, $items[1]->sell_in);
        $this->assertSame(50, $items[1]->quality);

        $this->assertSame(0, $items[2]->sell_in);
        $this->assertSame(0, $items[2]->quality);
    }
    public function testCunjuredManyStages(): void
    {
        $items = [
            new Item('Cunjured fruit salad', 8, 20),
            new Item('Cunjured fruit salad', 2, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        #check first step
        $this->assertSame(7, $items[0]->sell_in);
        $this->assertSame(18, $items[0]->quality);

        $this->assertSame(1, $items[1]->sell_in);
        $this->assertSame(18, $items[1]->quality);

        
        for ($iteration=0; $iteration < 4; $iteration++) { 
            $gildedRose->updateQuality();
        }
        #check final step
        $this->assertSame(3, $items[0]->sell_in);
        $this->assertSame(10, $items[0]->quality);

        $this->assertSame(0, $items[1]->sell_in);
        $this->assertSame(4, $items[1]->quality);

    }
    public function testConstantManyStages(): void
    {
        $items = [
            new Item('Sulfuras, Hand of Ragnaros', 1, 80),
        ];
        $gildedRose = new GildedRose($items);
        
        for ($iteration=0; $iteration < 10; $iteration++) { 
            $gildedRose->updateQuality();
        }

        $this->assertSame(1, $items[0]->sell_in);
        $this->assertSame(80, $items[0]->quality);

    }
    public function testAgedProduceManyStages(): void
    {
        $items = [
            new Item('Aged Brie', 5, 40),
        ];
        $gildedRose = new GildedRose($items);
        
        for ($iteration=0; $iteration < 5; $iteration++) { 
            $gildedRose->updateQuality();
        }

        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(45, $items[0]->quality);

        for ($iteration=0; $iteration < 10; $iteration++) { 
            $gildedRose->updateQuality();
        }

        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(50, $items[0]->quality);
    }
    public function testExpirableManyStages(): void
    {
        $items = [
            new Item('Apples', 6, 9),
        ];
        $gildedRose = new GildedRose($items);
        
        for ($iteration=0; $iteration < 5; $iteration++) { 
            $gildedRose->updateQuality();
        }

        $this->assertSame(1, $items[0]->sell_in);
        $this->assertSame(4, $items[0]->quality);

        $gildedRose->updateQuality();

        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(3, $items[0]->quality);

        $gildedRose->updateQuality();

        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(1, $items[0]->quality);
        $gildedRose->updateQuality();

        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(0, $items[0]->quality);

    }
}
