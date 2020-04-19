Feature: Service cash cli command
  In order to use the vending machine
  As a system user
  I need to be able to stock the vending machine with some coins

  Scenario: It stock increases after service coins
    Given I am the system
    When I execute the cli command "app:service:cash" with arguments:
      | argument | value |
      | coin     | 0.25  |
      | count    | 10    |
    Then the 0.25 coin stock must be equal to 10
    And the display should be equals to:
     """
     The vending machine has been successfully serviced

     """
    And the exit status code should be equal to 0

  Scenario: It fails with invalid coin
    Given I am the system
    When I execute the cli command "app:service:cash" with arguments:
      | argument | value |
      | coin     | 0.35  |
      | count    | 10    |
    Then the display should be equals to:
     """
     The coin must be one of: "0.05", "0.10", "0.25", "1.00". Got: "0.35"

     """
    And the exit status code should be equal to 1

  Scenario: It fails with invalid count
    Given I am the system
    When I execute the cli command "app:service:cash" with arguments:
      | argument | value |
      | coin     | 0.25  |
      | count    | -10   |
    Then the display should be equals to:
     """
     The count must be greater than or equal to 0. Got: -10

     """
    And the exit status code should be equal to 1

  Scenario: It fails with non numeric count
    Given I am the system
    When I execute the cli command "app:service:cash" with arguments:
      | argument | value |
      | coin     | 0.25  |
      | count    | foo   |
    Then the display should be equals to:
     """
     The count argument must be an integer. Got: string

     """
    And the exit status code should be equal to 1
