```mermaid
---
title: MCD du site AubEsport
---
erDiagram
    USER {
        string uuid PK
        string email UK "type email, unique"
        string password "hashed"
        string role "admin, player, user"
        string pseudo
        string bio
        string firstName
        string lastName
        date birthday
        string gender "autre, femme, homme"
        date modifiedAt
        date createdAt
        date deletedAt
    }

    USER |o--o{ FAVORITE_GAMES : like
    FAVORITE_GAMES {
        string uuid PK
        string name
    }

    USER ||--o{ ADDRESS : have
    
    ADDRESS {
        string uuid PK
        string streetNumber
        string streetName
        string postalCode
        string city
    }

    USER ||--|| IMAGE : own
    IMAGE {
        string uuid PK
        string link
    }


    TOURNAMENT ||--o{ TOURNAMENT-REGISTRATION : register  
    TOURNAMENT {
        string uuid PK
        string name
        date startingDate
        date endingDate
        date createdAt
        date modifiedAt 
        date deletedAt 
    }

    USER ||--o{ TOURNAMENT-REGISTRATION : register    
    TOURNAMENT-REGISTRATION {
        string uuid PK
        User user
        Tournament tournament
        string status "pending, refused, accepted"
        date createdAt
        date modifiedAt 
        date deletedAt    
    }

```

<!-- RELATIONSHIP
    |o	o|	Zero or one
    ||	||	Exactly one
    }o	o{	Zero or more (no upper limit)
    }|	|{	One or more (no upper limit) 

        CONTACT {
        int id
        string email "user or not ?"
        string name
        string message
        date date
    }
-->