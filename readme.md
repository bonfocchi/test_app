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

1 - Setting up the environment. DONE
    (I had some issues in both my Mac and Linux machines with the provided VM, so I used Homestead)

2 - Authentication and Authorization. DONE
    (I used two guards 'auth' and 'admin' for both the users and admins tables. The routes are different for both)

3 - Deployment to a remote server. DONE
    (I started with Vagrant and was able to deploy to EC2, but had issues provisioning the VM with the same caracteristics of the local VM, ended up using forge due to the time limit.)
    NOTE: Usually I do this when I setup the environment, but in this case I wanted to move forward with the code first due to the time limit.   

4 - Models, Relations and CRUD. (STANDBY)
    (Note regarding adding pictures to pages, ideally I would use a drag and drop interface to add pictures to a page, to position and resize them)

5 - RESTfull API following the specs. DONE
    ( The API documentation can be found here: http://docs.catalogapp2.apiary.io )
    (Additionally an 'api_blueprint.md' file was added to the root of this project)
    NOTE: Catalogs or Catalog pages API calls where not included on this API.

6 - Frontend.
6.1 - Pictures thumbnail creation to be displayed on the frontend. DONE
6.2 - PDF creation for user download.
6.3 - Catalog listing and PDF download after login.



7 - Tune up.
