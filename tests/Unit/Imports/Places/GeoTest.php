<?php

    namespace Tests\Unit\Import\Places;

    use App\Models\Imports\GeoPlace;
    use App\Services\Imports\Search\Places\GeoSearch;
    use Tests\TestCase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    class GeoTest extends TestCase
    {
        /** @test */
        public function it_returns_junior_columns_of_a_geoplace()
        {
            $geoPlaceAdm = factory(GeoPlace::class)->create(['type' => 'adm2']);

            dd($geoPlaceAdm);
        }
    }