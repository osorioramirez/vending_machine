Feature: Insert coin cli command
  In order to use the vending machine
  As a system user
  I need to be able to insert a coin

  Scenario: It should accept a coin
    Given I am the system
    When I execute the cli command "app:insert" with arguments:
      | argument | value |
      | coin     | 0.25  |
    Then the amount should be equal to 0.25
    And the display should be equals to:
     """
     Amount: 0.25

     """
    And the exit status code should be equal to 0

  Scenario: It should accept multiple coins
    Given I am the system
    And I insert a 1.00 coin
    When I execute the cli command "app:insert" with arguments:
      | argument | value |
      | coin     | 0.25  |
    Then the amount should be equal to 1.25
    And the display should be equals to:
     """
     Amount: 1.25

     """
    And the exit status code should be equal to 0

  Scenario: It fails with invalid coin
    Given I am the system
    When I execute the cli command "app:insert" with arguments:
      | argument | value |
      | coin     | 0.35  |
    Then the display should be equals to:
     """
     The coin must be one of: "0.05", "0.10", "0.25", "1.00". Got: "0.35"

     """
    And the exit status code should be equal to 1