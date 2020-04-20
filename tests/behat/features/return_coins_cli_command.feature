Feature: Expend cli command
  In order to use the vending machine
  As a system user
  I need to be able to get an item

  Scenario: It should return insert coins
    Given I am the system
    And I insert a 1.00 coin
    And I insert a 0.25 coin
    And I insert a 0.25 coin
    When I execute the cli command "app:return-coins"
    Then the display should be equals to:
      """
      Change: 1.50
      Coins: 1.00, 0.25, 0.25

      """
    And the exit status code should be equal to 0
    And the 0.25 coin stock must be equal to 0
    And the 1.00 coin stock must be equal to 0
    And the amount should be equal to 0

  Scenario: It should return a change equals to insert coins
    Given I am the system
    And I provision the vending machine with the following coins:
      | coin  | count |
      | 0.05  | 10    |
      | 0.10  | 10    |
      | 0.25  | 10    |
      | 1.00  | 10    |
    And I insert a 0.25 coin
    And I insert a 0.25 coin
    And I insert a 0.25 coin
    And I insert a 0.25 coin
    And I insert a 0.10 coin
    And I insert a 0.10 coin
    And I insert a 0.10 coin
    And I insert a 0.10 coin
    And I insert a 0.05 coin
    And I insert a 0.05 coin
    When I execute the cli command "app:return-coins"
    Then the display should be equals to:
      """
      Change: 1.50
      Coins: 1.00, 0.25, 0.25

      """
    And the exit status code should be equal to 0
    And the 1.00 coin stock must be equal to 9
    And the 0.25 coin stock must be equal to 12
    And the 0.10 coin stock must be equal to 14
    And the 0.05 coin stock must be equal to 12
    And the amount should be equal to 0

  Scenario: No free money
    Given I am the system
    And I provision the vending machine with the following coins:
      | coin  | count |
      | 0.05  | 10    |
      | 0.10  | 10    |
      | 0.25  | 10    |
      | 1.00  | 10    |
    When I execute the cli command "app:return-coins"
    Then the display should be equals to:
      """
      Change: 0.00
      Coins: None

      """
    And the exit status code should be equal to 0
    And the 1.00 coin stock must be equal to 10
    And the 0.25 coin stock must be equal to 10
    And the 0.10 coin stock must be equal to 10
    And the 0.05 coin stock must be equal to 10
    And the amount should be equal to 0
