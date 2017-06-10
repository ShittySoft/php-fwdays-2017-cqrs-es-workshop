<?php

declare(strict_types=1);

namespace BuildingTest\Context;

use Behat\Behat\Context\Context;

final class BuildingContext implements Context
{
    /**
     * @Given a building was registered
     */
    public function aBuildingWasRegistered() : void
    {
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * @When a user checks into the building
     */
    public function aUserChecksIntoTheBuilding() : void
    {
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * @Then the user should have checked into the building
     */
    public function theUserShouldHaveCheckedIntoTheBuilding() : void
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
