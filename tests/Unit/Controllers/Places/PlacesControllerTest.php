<?php

    namespace Tests\Unit\Models\Import\Places;

    use App\Models\Place;
    use Tests\TestCase;

    class PlacesControllerTest extends TestCase
    {
        /** @test */
        public function users_can_view_show_page(): void
        {
            $response = $this->get('places/england');

            $response->assertStatus(200);
        }
    }