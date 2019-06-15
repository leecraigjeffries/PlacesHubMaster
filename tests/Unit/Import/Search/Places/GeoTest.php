<?php

namespace Tests\Unit\Import\Search\Places;

use App\Services\Import\Search\Places\GeoSearch;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeoTest extends TestCase
{
    /** @test */
    public function it_returns_the_opposite_order(): void
    {
        $geoSearch = new GeoSearch(['order' => 'asc']);

        $this->assertEquals('desc', $geoSearch->getOrderOpposite());
    }

    /** @test */
    public function it_returns_inputs(): void
    {
        $geoSearch = new GeoSearch(['adm1_name' => 'England']);

        $this->assertEquals('England', $geoSearch->getInput('adm1_name'));
    }

    /** @test */
    public function it_overrides_appends(): void
    {
        $geoSearch = new GeoSearch(['type' => 'PPL']);

        $this->assertEquals('PPL', $geoSearch->getInput('type'));

        $appends = $geoSearch->getAppends(['type' => 'ADM1']);

        $this->assertEquals('ADM1', $appends['type']);
    }
}
