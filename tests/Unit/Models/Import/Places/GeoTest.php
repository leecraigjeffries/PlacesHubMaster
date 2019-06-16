<?php

    namespace Tests\Unit\Models\Import\Places;

    use App\Models\Imports\GeoPlace;
    use Tests\TestCase;

    class GeoTest extends TestCase
    {
        /** @test */
        public function it_returns_junior_types(): void
        {
            $geoPlace = factory(GeoPlace::class)->create(['type' => 'adm1', 'geo_type' => 'ADM1']);

            $this->assertEquals(['adm2', 'adm3', 'adm4'], $geoPlace->juniorAdminTypes());

            $geoPlace = factory(GeoPlace::class)->create(['type' => 'adm2', 'geo_type' => 'ADM2']);

            $this->assertEquals(['adm3', 'adm4'], $geoPlace->juniorAdminTypes());

            $geoPlace = factory(GeoPlace::class)->create(['type' => 'adm3', 'geo_type' => 'ADM3']);

            $this->assertEquals(['adm4'], $geoPlace->juniorAdminTypes());

            $geoPlace = factory(GeoPlace::class)->create(['type' => 'adm4', 'geo_type' => 'ADM4']);

            $this->assertEquals([], $geoPlace->juniorAdminTypes());
        }
    }