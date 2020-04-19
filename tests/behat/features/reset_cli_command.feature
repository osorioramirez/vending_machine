Feature: Reset cli command
  In order to debug the behavior of the vending machine
  As a system user
  I need to be able to reset the vending machine state

  Scenario: It should be empty after I reset it
    Given I am the system
    And I provision the vending machine with the following items:
      | name  | count |
      | WATER | 10    |
      | SODA  | 15    |
    And I provision the vending machine with the following coins:
      | coin  | count |
      | 0.05  | 10    |
      | 0.25  | 15    |
    When I execute the cli command "app:reset"
    Then the "WATER" stock must be equal to 0
    And the "SODA" stock must be equal to 0
    And the 0.10 coin stock must be equal to 0
    And the 0.25 coin stock must be equal to 0
    And the display should be equals to:
     """
     The vending machine has been successfully reset

     """
    And the exit status code should be equal to 0
