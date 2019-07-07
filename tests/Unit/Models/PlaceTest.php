<?php

    namespace Tests\Unit\Models\Import\Places;

    use App\Models\Place;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class PlaceTest extends TestCase
    {
        use RefreshDatabase;

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

            $this->assertEqualsCanonicalizing($place->childTypes(), ['locality', 'hood']);
        }

        /** @test */
        public function it_can_have_siblings(): void
        {
            $firstSibling = factory(Place::class)->create([
                'country_id' => 1,
                'name' => 'England',
                'type' => 'macro_region'
            ]);

            $secondSibling = factory(Place::class)->create([
                'country_id' => 1,
                'name' => 'Scotland',
                'type' => 'macro_region'
            ]);

            factory(Place::class)->create([
                'country_id' => 2,
                'name' => 'Scotland',
                'type' => 'macro_region'
            ]);

            $this->assertCount(1, $firstSibling->siblings());

            $this->assertEquals($firstSibling->siblings()->first()->name, $secondSibling->name);
        }

        /** @test */
        public function it_can_have_junior_types(): void
        {
            $place = new Place([
                'name' => 'Bolton',
                'type' => 'local_admin'
            ]);

            $juniorTypes = $place->juniorTypes();

            $this->assertEquals(['locality', 'hood'], $juniorTypes);

            $juniorTypes = $place->juniorTypes(true);

            $this->assertEquals(['local_admin', 'locality', 'hood'], $juniorTypes);

            $place = new Place([
                'name' => 'Bolton',
                'type' => 'hood'
            ]);

            $this->assertEmpty($place->juniorColumns());
        }

        /** @test */
        public function it_can_have_junior_columns(): void
        {
            $place = new Place([
                'type' => 'local_admin'
            ]);

            $juniorColumns = $place->juniorColumns();

            $this->assertEquals(['locality_id'], $juniorColumns);

            $juniorColumns = $place->juniorColumns(true);

            $this->assertEquals(['local_admin_id', 'locality_id'], $juniorColumns);

            $place = new Place([
                'type' => 'hood'
            ]);

            $this->assertEmpty($place->juniorColumns());

            $place = new Place([
                'type' => 'locality'
            ]);

            $this->assertEmpty($place->juniorColumns());
        }

        /** @test */
        public function it_can_have_senior_columns(): void
        {
            $place = new Place([
                'type' => 'region'
            ]);

            $seniorColumns = $place->seniorColumns();

            $this->assertEquals(['country_id', 'macro_region_id'], $seniorColumns);

            $seniorColumns = $place->seniorColumns(true);

            $this->assertEquals(['country_id', 'macro_region_id', 'region_id'], $seniorColumns);

            $place = new Place([
                'type' => 'country'
            ]);

            $this->assertEmpty($place->seniorColumns());
        }

        /** @test */
        public function it_can_have_senior_columns_in_reverse_order(): void
        {
            $place = new Place([
                'type' => 'region'
            ]);

            $seniorColumns = $place->seniorColumnsReversed();

            $this->assertEquals(['macro_region_id', 'country_id'], $seniorColumns);
        }

        /** @test */
        public function it_can_have_column_attribute(): void
        {
            $place = new Place([
                'type' => 'region'
            ]);

            $this->assertEquals($place->type_column, 'region_id');
        }
    }