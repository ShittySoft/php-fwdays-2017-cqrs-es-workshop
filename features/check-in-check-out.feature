Feature: Check in and out of a building

  Scenario: A user can check into a building
    Given a building was registered
    When a user checks into the building
    Then the user should have checked into the building

  Scenario: A user can check out of a building
    Given a building was registered
    And a user has checked into the building
    When a user checks out of the building
    Then the user should have checked out the building

  Scenario: A user that checks twice into a building causes an anomaly
    Given a building was registered
    And a user has checked into the building
    When a user checks into the building
    Then the user should have checked into the building
    And a check-in anomaly should have been detected

