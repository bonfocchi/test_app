# Catalog Test App

This app is being developed following the provided specs.
This is my first app in Laravel, and the first time I'm using Vagrant.

## Notes

1 - I'm following the specs regarding the two tables for authentication (users and admins).
Personally I would use a single table with roles (user groups).

2 - Without having the full context of the app, it's difficult to know exactly how some parts should work in terms of UX.

3 - Usually I do tests first, in this case since I'm focusing on learning Laravel as I move forward, I will not add tests due to the time limit.



## My approach to this project

### Execution route:

1 - Setting up the environment.
    (I had some issues in both my Mac and Linux machines with the provided VM, so I used Homestead)

2 - Authentication and Authorization.
    (I used two guards 'auth' and 'admin' for both the users and admins tables. The routes are different for both)

3 - Models, Relations and CRUD. (CURRENT)
    (In progress...)

4 - RESTfull API following the specs.
    (NOTE: There is no API documentation regarding Catalogs or Catalog pages.)  

5 - Deployment to a remote server.
    (Usually I do this when I setup the environment, but in this case I wanted to move forward with the code first due to the time limit.)

6 - PDF creation for user download.

7 - Tune up.
