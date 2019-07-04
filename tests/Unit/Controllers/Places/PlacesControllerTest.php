<?php

    namespace Tests\Unit\Models\Import\Places;

    use App\Models\Place;
    use Tests\TestCase;

    class PlacesControllerTest extends TestCase
    {
        /** @test */
        public function users_can_view_show_page(): void
        {
            factory(Place::class)->create([
                'name' => 'England',
                'type' => 'macro_region',
                'slug' => 'england'
            ]);

            $response = $this->get('places/england');

            $response->assertStatus(200);
        }

        /** @test */
        public function a_place_can_be_created(): void
        {

        }
    }