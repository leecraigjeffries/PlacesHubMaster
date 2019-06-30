<?php

    namespace Tests\Unit\Models\Import\Places;

    use App\Models\Place;
    use Tests\TestCase;

    class PlaceTest extends TestCase
    {
        /** @test */
        public function it_can_have_a_name(): void
        {
            $place = new Place(['name' => 'England']);

            $this->assertEquals('England', $place->name);
        }

        /** @test */
        public function it_can_have_child_types(): void
        {
            $place = new Place(['type' => 'local_admin']);

            $this->assertEqualsCanonicalizing($place->getChildTypes(), ['locality', 'hood']);
        }
    }