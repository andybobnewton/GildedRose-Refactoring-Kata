<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
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
}
