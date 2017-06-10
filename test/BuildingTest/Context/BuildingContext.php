<?php

declare(strict_types=1);

namespace BuildingTest\Context;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Building\Domain\Aggregate\Building;
use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Building\Domain\DomainEvent\UserCheckedIn;
use Prooph\EventSourcing\AggregateChanged;
use Rhumsaa\Uuid\Uuid;

final class BuildingContext implements Context
{
    /**
     * @var AggregateChanged[]
     */
    private $pastEvents     = [];

    /**
     * @var null|AggregateChanged[]
     */
    private $recordedEvents;

    /**
     * @var Building|null
     */
    private $building;

    /**
     * @var Uuid
     */
    private $aggregateId;

    public function __construct()
    {
        $this->aggregateId = Uuid::uuid4();
    }

    /**
     * @Given a building was registered
     */
    public function aBuildingWasRegistered() : void
    {
        $this->record(NewBuildingWasRegistered::occur(
            $this->aggregateId->toString(),
            ['name' => 'foo']
        ));
    }

    /**
     * @When a user checks into the building
     */
    public function aUserChecksIntoTheBuilding() : void
    {
        $this->building()->checkInUser('Mr. Magoo');
    }

    /**
     * @Then the user should have checked into the building
     *
     * @throws \Assert\AssertionFailedException
     */
    public function theUserShouldHaveCheckedIntoTheBuilding() : void
    {
        /* @var $event UserCheckedIn */
        $event = $this->getNextRecordedEvent();

        Assertion::isInstanceOf($event, UserCheckedIn::class);
        Assertion::same($event->username(), 'Mr. Magoo');
    }

    private function record(AggregateChanged $event) : void
    {
        $this->pastEvents[] = $event;
    }

    private function building() : Building
    {
        return $this->building
            ?? $this->building = \Closure::bind(function (array $events) {
                return Building::reconstituteFromHistory(new \ArrayIterator($events));
            }, null, Building::class)
                ->__invoke($this->pastEvents);
    }

    private function getNextRecordedEvent() : AggregateChanged
    {
        if (null === $this->recordedEvents) {
            $building = $this->building();
            $popEvents = new \ReflectionMethod($building, 'popRecordedEvents');

            $popEvents->setAccessible(true);

            $this->recordedEvents = $popEvents->invoke($building);
        }

        return array_shift($this->recordedEvents);
    }
}
