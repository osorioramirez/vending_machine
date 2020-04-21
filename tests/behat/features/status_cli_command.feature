Feature: Status cli command
  In order to debug the behavior of the vending machine
  As a system user
  I need to be able to see the vending machine state

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
    And I insert a 0.10 coin
    When I execute the cli command "app:status"
    And the display should be equals to:
     """
     +------------+ Inventory -+------------+
     | Item       | Price      | Count      |
     +------------+------------+------------+
     | WATER      | 0.65       | 10         |
     | JUICE      | 1.00       | 0          |
     | SODA       | 1.50       | 15         |
     +------------+------------+------------+
     +----------- Cash Register ------------+
     | Coin             | Count             |
     +------------------+-------------------+
     | 0.05             | 10                |
     | 0.10             | 1                 |
     | 0.25             | 15                |
     | 1.00             | 0                 |
     +------------------+-------------------+
     | Total            | 4.35              |
     +------------------+-------------------+
     Amount: 0.10

     """
    And the exit status code should be equal to 0
