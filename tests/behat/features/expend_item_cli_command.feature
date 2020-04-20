Feature: Expend cli command
  In order to use the vending machine
  As a system user
  I need to be able to get an item

  Scenario: It should expend an item
    Given I am the system
    And I provision the vending machine with the following items:
      | name  | count |
      | WATER | 10    |
    And I provision the vending machine with the following coins:
      | coin  | count |
      | 0.05  | 10    |
      | 0.10  | 10    |
      | 0.25  | 10    |
      | 1.00  | 10    |
    And I insert a 1.00 coin
    When I execute the cli command "app:get" with arguments:
      | argument | value |
      | name     | WATER |
    Then the display should be equals to:
      """
      Enjoy your WATER!
      Change: 0.35
      Coins: 0.25, 0.10

      """
    And the exit status code should be equal to 0
    And the "WATER" stock must be equal to 9
    And the 0.05 coin stock must be equal to 10
    And the 0.10 coin stock must be equal to 9
    And the 0.25 coin stock must be equal to 9
    And the 1.00 coin stock must be equal to 11
    And the amount should be equal to 0

  Scenario: It cannot expend an item when it is not available
    Given I am the system
    And I insert a 1.00 coin
    When I execute the cli command "app:get" with arguments:
      | argument | value |
      | name     | WATER |
    Then the display should be equals to:
      """
      Sorry, WATER not available

      """
    And the exit status code should be equal to 0
    And the 1.00 coin stock must be equal to 1
    And the amount should be equal to 1.00

  Scenario: It cannot expend an item when amount is not enough
    Given I am the system
    And I provision the vending machine with the following items:
      | name  | count |
      | WATER | 10    |
    And I insert a 0.25 coin
    When I execute the cli command "app:get" with arguments:
      | argument | value |
      | name     | WATER |
    Then the display should be equals to:
      """
      Not enough amount. Insert more coins

      """
    And the exit status code should be equal to 0
    And the "WATER" stock must be equal to 10
    And the 0.25 coin stock must be equal to 1
    And the amount should be equal to 0.25

  Scenario: It cannot expend an item when there is no change
    Given I am the system
    And I provision the vending machine with the following items:
      | name  | count |
      | WATER | 10    |
    And I insert a 1.00 coin
    When I execute the cli command "app:get" with arguments:
      | argument | value |
      | name     | WATER |
    Then the display should be equals to:
      """
      Sorry, not change

      """
    And the exit status code should be equal to 0
    And the "WATER" stock must be equal to 10
    And the 1.00 coin stock must be equal to 1
    And the amount should be equal to 1.00
