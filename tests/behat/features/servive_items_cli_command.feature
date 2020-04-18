Feature: Service items cli command
  In order to use the vending machine
  As a system user
  I need to be able to stock the vending machine with some items

  Scenario: It stock increases after service items
    Given I am the system
    When I execute the cli command "app:service:items" with arguments:
      | argument | value |
      | name     | WATER |
      | count    | 10    |
    Then the "WATER" stock must be equal to 10
    And the display should be equals to:
     """
     The vending machine has been successfully serviced

     """
    And the exit status code should be equal to 0

  Scenario: It fails with invalid item name
    Given I am the system
    When I execute the cli command "app:service:items" with arguments:
      | argument | value   |
      | name     | COOKIES |
      | count    | 10      |
    Then the display should be equals to:
     """
     The item name must be one of: "WATER", "JUICE", "SODA". Got: "COOKIES"

     """
    And the exit status code should be equal to 1

  Scenario: It fails with invalid count
    Given I am the system
    When I execute the cli command "app:service:items" with arguments:
      | argument | value |
      | name     | JUICE |
      | count    | -10   |
    Then the display should be equals to:
     """
     The count must be greater than or equal to 0. Got: -10

     """
    And the exit status code should be equal to 1

  Scenario: It fails with non numeric count
    Given I am the system
    When I execute the cli command "app:service:items" with arguments:
      | argument | value |
      | name     | JUICE |
      | count    | foo   |
    Then the display should be equals to:
     """
     The count argument must be an integer. Got: string

     """
    And the exit status code should be equal to 1