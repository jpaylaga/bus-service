<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\BusRepositoryInterface;
use App\Models\Bus;
use App\Objects\BusRoute;

class BusRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $busRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->busRepository = resolve(BusRepositoryInterface::class);
    }

    /** @test */
    public function it_can_get_bus_routes()
    {
        $bus = Bus::factory()->create();
        $routes = $this->busRepository->getRoutes($bus);
        $this->assertInstanceOf(BusRoute::class, $routes);
    }
}
