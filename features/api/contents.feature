Feature: Contents

  Scenario: Returning a collection of contents
    When I request "GET /api/v1/contents"
    Then I get a "200" response
    And scope into the first "data" property
      And the properties exist:
        """
        _id
        created_at
        title
        description
        nsfw
        eng
        user
        group
        uv
        dv
        """
        And the "nsfw" property is a boolean
        And the "eng" property is a boolean
        And the "uv" property is an integer
        And the "dv" property is an integer
        And the "user" property is an object
        And the "group" property is an object

  Scenario: Returning a collection of contents from given group
    When I request "GET /api/v1/contents?group=king"
    Then I get a "200" response
    And scope into the first "data" property
    And the properties exist:
        """
        _id
        created_at
        title
        description
        nsfw
        eng
        user
        group
        uv
        dv
        """
    And the "nsfw" property is a boolean
    And the "eng" property is a boolean
    And the "uv" property is an integer
    And the "dv" property is an integer
    And the "user" property is an object
    And the "group" property is an object
    And scope into the "data.0.group" property
      And the "name" property equals "Londontown"

  Scenario: Finding a specific content
    When I request "GET /api/v1/contents/1cb787"
    Then I get a "200" response
    And the properties exist:
      """
      _id
      created_at
      title
      description
      nsfw
      eng
      user
      group
      uv
      dv
      """

  Scenario: Finding a non-existing content
    When I request "GET /api/v1/contents/nosuchid"
    Then I get a "404" response