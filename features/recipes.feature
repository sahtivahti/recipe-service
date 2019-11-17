Feature: Recipe management

  Scenario: Can add new Recipe
    Given the request body is:
      """
      {
        "name": "My special beer",
        "author": "panomestari@sahtivahti.fi"
      }
      """
    When I request "/v1/recipe/" using HTTP POST
    Then the response code is 201
    And the response body contains JSON:
      """
      {
        "name": "My special beer",
        "author": "panomestari@sahtivahti.fi"
      }
      """

  Scenario: Can read a list of added recipes
    When I request "/v1/recipe/" using HTTP GET
    Then the response code is 200
    And the response body is a JSON array with a length of at least 1
