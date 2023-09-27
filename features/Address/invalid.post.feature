@addresses
Feature:
    In order to avoid invalid information
    As a developer 
    I want the application validate infos before saving into the database

    @tag
    Scenario: Players register with invalid email
        When I send a post request to "/api/users/c2ef4afb-fc3a-487d-818a-75af0a7e6819/addresses" with
        """
            {
                 "streetName": "rue garnier",
                 "streetNumber": "10",
                 "city": "Lyon",
                 "postalCode":"690089"
            }
        """
        Then I should receive a status code 400
        And the node "detail" of the reponse should be "postalCode: Le code postal doit contenir exactement 5 chiffres."

