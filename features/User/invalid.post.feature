@users
Feature:
    In order to avoid invalid information
    As a developer 
    I want the application validate infos before saving into the database

    Scenario: Players register with invalid email
        When I send a post request to "/api/users" with
        """
            {
                "email": "myEmail1",
                "pseudo": "BunBoHue"
            }
        """
        Then I should receive a status code 422

    Scenario: Players register with existing email
        Given there is an existant user with email "myEmail@gmail.com" and uuid "a245f890-accf-4295-a00a-522732682fdc"
        When I send a post request to "/api/users" with
        """
            {
                "email": "myEmail@gmail.com",
                "pseudo": "BunBoHue",
                "password": "Ab1234567?"
            }
        """
        Then I should receive a status code 409
