Feature: Testing RESTContext

  Scenario: Test list news
    When I send a GET request to "/news"
    Then the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the response status code should be 200

  Scenario: Test create news
    When I add "content-type" header equal to "application/json"
    And I send a POST request to "/news/" with body:
            """
            {"strange field":"strange value", "body":"first body","title":"first title","id":470840313}
            """
    Then the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the response status code should be 200
    And the response should be in JSON
    And the JSON node "root.body" should exist
    And the JSON node "root.title" should exist
    And the JSON node "root.id" should exist
    And the JSON node "root.created" should exist
    And the JSON node "root.modified" should exist
    And the JSON node "body" should contain "first body"
    And the JSON node "title" should contain "first title"

  Scenario: Test malformed create news
    When I send a POST request to "/news/" with body:
            """
            {"strange field":"strange value", "body":"","title":"first title","id":470840313}
            """
    Then the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the response status code should be 400

  Scenario: Test update news
    When I add "content-type" header equal to "application/json"
    And I send a PUT request to "/news/1" with body:
            """
            {"strange field":"ignored value", "body":"updated body","title":"updated title","id":470840313}
            """
    Then the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the response status code should be 200
    And the JSON node "root.body" should exist
    And the JSON node "root.title" should exist
    And the JSON node "root.id" should exist
    And the JSON node "root.created" should exist
    And the JSON node "root.modified" should exist
    And the JSON node "body" should contain "updated body"
    And the JSON node "title" should contain "updated title"
    And the JSON node "id" should contain "1"

  Scenario: Test malformed update request
    When I send a PUT request to "/news/1" with body:
            """
            {"body":"","title":""}
            """
    Then the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the response status code should be 400

  Scenario: Test delete news
    When I send a DELETE request to "/news/1"
    Then the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the response status code should be 200

  Scenario: Test delete non-existent news
    When I send a DELETE request to "/news/424242424242"
    Then the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the response status code should be 404
