FORMAT: 1A
HOST: http://52.29.114.68/api/v1

# An API Documentation of the catalog application

Catalog application is a service that providers catalogs of pictures to users.

Users can browse the available catalogs and their pictures, and they can also purchase catalogs or individual pictures.
But users must have to login into the service for a purchase.
After a purchase, an user can download a PDF version of the catalog or picture.

__NOTES: The scope is only users, login and pictures resources. Downloading PDF, purchase features, and managing pages and catalogs will not be considered.__

The API is supported some features by REST API as a JSON.

## Overview

The API is a REST API using the following HTTP methods:

* GET: To retrieve a list of items or a item. By providing an ID as the last segment of the url, only the item corresponding to that ID is returned.
* POST: To create an item.
* PUT: To update an item. The item's ID is required as the last segment of the url.
* DELETE: To delete items. By providing an ID as the last segment of the url, only the item corresponding to that ID is deleted.

__NOTE: When making a PUT request with parameters `x-www-form-urlencoded` should be used. Alternatively a additional parameter `_method` with the value `PUT` can be used.__

The domain being used is:
(in production HTTPS should be used)

```
http://52.29.114.68/api/v1
```

You need to consider about the versioning of API, please include the version number on the URI segument.

# group API authorization

### User authorization

This authorization is made by providing the Admin token as a header key `Authorization` and the value for that key should start with `Bearer `.  
This is required by controlling all resources.

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL2hvbWVzdGVhZC5hcHBcL2FwaVwvdjFcL2xvZ2luIiwiaWF0IjoxNDcyMTM1OTY0LCJleHAiOjE0NzIxMzk1NjQsIm5iZiI6MTQ3MjEzNTk2NCwianRpIjoiNTQ4MzcyMWMyNTZmYzIwNzJhN2Y2ZDc0ODZjODYzZTkifQ.HWEZt9srcnx8027BA37tei2h_PN32e97_P5Z8BKw5Ho
```

#### How to acquire a session ID

The user's session ID is acquired by making a POST request to `/login` and providing the user's email and password.  
If the login is successful, it returns the sessionID in the `data` container, see the [/login]() endpoint.

### Example

```bash
curl -S http://52.29.114.68/api/v1/login \
     -X GET \
     -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL2hvbWVzdGVhZC5hcHBcL2FwaVwvdjFcL2xvZ2luIiwiaWF0IjoxNDcyMTM1OTY0LCJleHAiOjE0NzIxMzk1NjQsIm5iZiI6MTQ3MjEzNTk2NCwianRpIjoiNTQ4MzcyMWMyNTZmYzIwNzJhN2Y2ZDc0ODZjODYzZTkifQ.HWEZt9srcnx8027BA37tei2h_PN32e97_P5Z8BKw5Ho"
```

# group Request flow

#### A flow of making a picture.

1. Creating a picture resource. [|>](/#page:pictures,header:pictures-creates-a-picture)
2. Uploading a image that corresponds to the picture resource by the ID. [|>](/#page:pictures,header:pictures-uploads-a-picture-image)

# Group Login

The login resource can be used to create and update and destroy a session, and retrives the user data by session.

## Retrives a logged in data [/login]

Retrives a logged in data includs a user resource.

### GET

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "GET",
                "endpoint": "api/v1/login"
              },
              "data": {
                "id": 1,
                "hubsynch_id": 404,
                "email": "bonfocchi@gmail.com",
                "created_at": "2016-08-19 23:35:56",
                "updated_at": "2016-08-25 21:00:14"
              },
              "errors": [],
              "duration": 0.358
            }

+ Response 401 (application/json)

   When user did not login.

    + Body

            {
              "success": 0,
              "code": 401,
              "meta": {
                "method": "GET",
                "endpoint": "api/v1/login"
              },
              "data": [],
              "errors": {
                "0": "message => The request requires user authentication.",
                "code": "401001"
              },
              "duration": 0.316
            }

## Login [/login]

Login a user on the HiCat.

### POST

+ Parameters
    + email: `hicat@hivelocity.co.jp` (string) - The user's email.
    + password: `Qalekrnfmm9m4ak` (string) - The user's password.

+ Response 201 (application/json)

    + Body

            {
              "success": 1,
              "code": 201,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/login"
              },
              "data": {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cLzUyLjI5LjExNC42OFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE0NzIxNTg0MzUsImV4cCI6MTQ3MjE2MjAzNSwibmJmIjoxNDcyMTU4NDM1LCJqdGkiOiIzODE5ZGEzMTcwYTBhY2ZlNGE4MGY0NDE0OWYxYzNmOSJ9.vmte357kRuV4dsXYYQit_nF4ozB7XSVZuSV941KGkzM"
              },
              "errors": [],
              "duration": 1.646
            }

+ Response 400 (application/json)

    A error has occurred while validation.

    + Body

            {
              "success": 0,
              "code": 400,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/login"
              },
              "data": [],
              "errors": {
                "message": "The request parameters are incorrect, please make sure to follow the HiCat document.",
                "code": 400002,
                "validation": {
                  "email": [
                    {
                      "key": "required",
                      "message": "The email field is required."
                    }
                  ],
                  "password": [
                    {
                      "key": "required",
                      "message": "The password field is required."
                    }
                  ]
                }
              },
              "duration": 0.163
            }

+ Response 400 (application/json)

    When user that matches the email and password does not exist.

    + Body

            {
              "success": 0,
              "code": 400,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/login"
              },
              "data": [],
              "errors": {
                "message": "The user did not subscribe the application.",
                "code": 400003
              },
              "duration": 0.325
            }

## Regenerates the session ID [/login]

Regenerates a session ID as new.

### PUT

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "PUT",
                "endpoint": "api/v1/login"
              },
              "data": {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cLzUyLjI5LjExNC42OFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE0NzIxNTk1MTEsImV4cCI6MTQ3MjE2MzEyOCwibmJmIjoxNDcyMTU5NTI4LCJqdGkiOiJmMDk0ZWFiNmZlZWFjYzA1OTE2OWU2ODJjZDBmNjUzYSJ9.v9LQGhByw7l-BE_4Qw1OKEuUgFNSEisWihjxt4WEs40"
              },
              "errors": [],
              "duration": 0.284
            }

+ Response 401 (application/json)

   When user did not login.

    + Body

            {
              "success": 0,
              "code": 401,
              "meta": {
                "method": "PUT",
                "endpoint": "api/v1/login"
              },
              "data": [],
              "errors": {
                "0": "message => The request requires user authentication.",
                "code": "401001"
              },
              "duration": 0.166
            }

## Logout [/login]

Logout a user on the HiCat.

### DELETE

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "DELETE",
                "endpoint": "api/v1/login"
              },
              "data": {
                "deleted_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cLzUyLjI5LjExNC42OFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE0NzIxNTk2NjEsImV4cCI6MTQ3MjE2MzI2MSwibmJmIjoxNDcyMTU5NjYxLCJqdGkiOiI4MDI3ZjEzNDQ2YTUxNTQ1MTRhOTg4ZjQwOWFkZWM1MiJ9.SHJEfc-5FbujQgZdEV2d3DSNjwZy0dVu4lafYhGZaus"
              },
              "errors": [],
              "duration": 0.27
            }

+ Response 401 (application/json)

   When user did not login.

    + Body

            {
              "success": 0,
              "code": 401,
              "meta": {
                "method": "DELETE",
                "endpoint": "api/v1/login"
              },
              "data": [],
              "errors": {
                "0": "message => The request requires user authentication.",
                "code": "401001"
              },
              "duration": 0.123
            }

# Group Users

This endpoint controls the users resource.

## Checks to exist a user [/users/{id}]

Checks a user was exist.

### GET

+ Parameters
  + id: `7` (required, int) - The user's primary ID.

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "GET",
                "endpoint": "api/v1/users/1"
              },
              "data": {
                "exists": 1
              },
              "errors": [],
              "duration": 0.208
            }

## Creates a user [/users]

Creates a new user.

### POST

+ Parameters
    + hubsynch_id: `2` (required, int) - A user's hubsynch ID.
    + email: `yharikita@hivelocity.co.jp` (required, string) - A user's email.
    + password: `ewmJK94jnca0a` (required, string) - A user's password.

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/users"
              },
              "data": {
                "users": {
                  "id": 2
                },
                "subscriptions": {
                  "id": 404
                }
              },
              "errors": [],
              "duration": 1.929
            }

+ Response 400 (application/json)

    A error has occurred while validation.

    + Body

            {
              "success": 0,
              "code": 400,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/users"
              },
              "data": [],
              "errors": {
                "message": "The request parameters are incorrect, please make sure to follow the HiCat document.",
                "code": 400002,
                "validation": {
                  "hubsynch_id": {
                    "key": "required",
                    "message": "The hubsynch_id field is required."
                  },
                  "email": {
                    "key": "required",
                    "message": "The email field is required."
                  },
                  "password": {
                    "key": "required",
                    "message": "The password field is required."
                  }
                }
              },
              "duration": 0.205
            }

## Deletes a user [/users/{id}]

Deletes a user that matches the ID as the last segment of the url.

### DELETE

+ Parameters
    + id: `2` (required, int) - The user's primary ID.

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "DELETE",
                "endpoint": "api/v1/users/2"
              },
              "data": {
                "deleted": 1
              },
              "errors": [],
              "duration": 0.263
            }

+ Response 403

    When the user does not exist.

    + Body

            {
              "success": 0,
              "code": 403,
              "meta": {
                "method": "DELETE",
                "endpoint": "api/v1/users/2"
              },
              "data": [],
              "errors": {
                "message": "The resource that matches ID:2 does not found.",
                "code": 403001
              },
              "duration": 0.233
            }

# Group Pictures

This endpoint controls the pictures resource.

## Retrives a list of pictures [/pictures]

Retrives a list of the pictures that is related to the admin.

### GET

+ Response 200 (application/json)

    + Body
            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "GET",
                "endpoint": "api/v1/pictures",
                "limit": 30,
                "offset": 0,
                "total": 2
              },
              "data": [
                {
                  "id": 3,
                  "admin_id": 1,
                  "title": "Planet views",
                  "description": "Views from four planets",
                  "wh_ratio": 0,
                  "cached_file_name": "",
                  "storage_file_name": "44979b965e9ae6fe03d2fa5deea3a8da_img_MhDfscC.jpg",
                  "created_at": "2016-08-20 22:33:47",
                  "updated_at": "2016-08-20 22:33:47",
                  "download_url": "https://s3.eu-central-1.amazonaws.com/catalogpictures/44979b965e9ae6fe03d2fa5deea3a8da_img_MhDfscC.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJ27MFPYZQ4ISRAWQ%2F20160825%2Feu-central-1%2Fs3%2Faws4_request&X-Amz-Date=20160825T214753Z&X-Amz-SignedHeaders=host&X-Amz-Expires=600&X-Amz-Signature=cb921088ea2a268fcf9e9cba5952755bfa4eccc06acc0cc105e8199ccb51ce0a"
                },
                {
                  "id": 4,
                  "admin_id": 1,
                  "title": "Tokyo by night",
                  "description": "Calm picture of Tokyo at night",
                  "wh_ratio": 0,
                  "cached_file_name": "",
                  "storage_file_name": "a02b6ef7d2eca014a9ef4f61c4229c43_img_17294466806_a36aa88968_h.jpg",
                  "created_at": "2016-08-20 22:33:47",
                  "updated_at": "2016-08-20 22:33:47",
                  "download_url": "https://s3.eu-central-1.amazonaws.com/catalogpictures/a02b6ef7d2eca014a9ef4f61c4229c43_img_17294466806_a36aa88968_h.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJ27MFPYZQ4ISRAWQ%2F20160825%2Feu-central-1%2Fs3%2Faws4_request&X-Amz-Date=20160825T214753Z&X-Amz-SignedHeaders=host&X-Amz-Expires=600&X-Amz-Signature=2a849f2e94aee97557dc05aa715f903997b9c5eeff6b9851ce48cff40788a87d"
                }
              ],
              "errors": [],
              "duration": 0.509
            }

## Retrives a picture [/pictures/{id}]

Retrives a picture that matches the ID as the last segment of the url.

### GET

+ Parameters
    + id: `4` (required, int) - The picture's primary ID.

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "GET",
                "endpoint": "api/v1/pictures/4"
              },
              "data": {
                "id": 4,
                "admin_id": 1,
                "title": "Tokyo by night",
                "description": "Calm picture of Tokyo at night",
                "wh_ratio": 0,
                "cached_file_name": "",
                "storage_file_name": "a02b6ef7d2eca014a9ef4f61c4229c43_img_17294466806_a36aa88968_h.jpg",
                "created_at": "2016-08-20 22:33:47",
                "updated_at": "2016-08-20 22:33:47",
                "download_url": "https://s3.eu-central-1.amazonaws.com/catalogpictures/a02b6ef7d2eca014a9ef4f61c4229c43_img_17294466806_a36aa88968_h.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJ27MFPYZQ4ISRAWQ%2F20160825%2Feu-central-1%2Fs3%2Faws4_request&X-Amz-Date=20160825T220317Z&X-Amz-SignedHeaders=host&X-Amz-Expires=600&X-Amz-Signature=0f3c8f5e948ff430549c44cf63de340c533bf1e79faec9ae1af8b495c186c23f"
              },
              "errors": [],
              "duration": 0.236
            }

## Creates a picture [/pictures]

Creates a new picture.

### POST

+ Parameters
    + title: `Sample picture` (required, string) - The picture's name.
    + description: `This is description of Sample picture` (optinal, string) - The picture's description.

+ Response 201 (application/json)

    + Body

            {
              "success": 1,
              "code": 201,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/pictures"
              },
              "data": {
                "id": 5
              },
              "errors": [],
              "duration": 0.29
            }

+ Response 400 (application/json)

    A error has occurred while validation.

    + Body

            {
              "success": 0,
              "code": 400,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/pictures"
              },
              "data": [],
              "errors": {
                "message": "The request parameters are incorrect, please make sure to follow the HiCat document.",
                "code": 400002,
                "validation": {
                  "title": {
                    "key": "required",
                    "message": "The title field is required."
                  },
                  "description": {
                    "key": "required",
                    "message": "The description field is required."
                  }
                }
              },
              "duration": 0.245
            }

## Updates a picture [/pictures/{id}]

Updates a picture that macthes the ID as the last segment of the url.

__NOTE: When making a PUT request with parameters `x-www-form-urlencoded` should be used. Alternatively a additional parameter `_method` with the value `PUT` can be used.__

### PUT

+ Parameters
    + id: `4` (required, int) - The picture's primary ID.
    + title: `picture_1_modified` (optional, string) - A modifies picture title.
    + description: `This is description for picture_1_modified` (optional, string) - A modifies picture description.

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 201,
              "meta": {
                "method": "PUT",
                "endpoint": "api/v1/pictures/4"
              },
              "data": {
                "id": 4
              },
              "errors": [],
              "duration": 0.29
            }

+ Response 400 (application/json)

    A error has occurred while validation.

    + Body

            {
              "success": 0,
              "code": 400,
              "meta": {
                "method": "PUT",
                "endpoint": "api/v1/pictures/4"
              },
              "data": [],
              "errors": {
                "message": "The request parameters are incorrect, please make sure to follow the HiCat document.",
                "code": 400002,
                "validation": {
                  "title": {
                    "key": "max:64",
                    "message": "The name may not be greater than 64 characters."
                  }
                }
              },
              "duration": 0.249
            }

+ Response 403

    When the picture does not exist.

    + Body

            {
              "success": 0,
              "code": 403,
              "meta": {
                "method": "PUT",
                "endpoint": "api/v1/pictures/25"
              },
              "data": [],
              "errors": {
                "message": "The resource that matches ID:25 does not found.",
                "code": 403001
              },
              "duration": 0.165
            }

## Deletes a picture [/pictures/{id}]

Deletes a picture that matches the ID as the last segment of the url.

### DELETE

+ Parameters
    + id: `25` (required, int) - The picture's primary ID.

+ Response 200 (application/json)

    + Body

            {
              "success": 1,
              "code": 200,
              "meta": {
                "method": "DELETE",
                "endpoint": "api/v1/pictures/4"
              },
              "data": {
                "deleted": 1
              },
              "errors": [],
              "duration": 0.365
            }

+ Response 403

    When the picture does not exist.

    + Body

            {
              "success": 0,
              "code": 403,
              "meta": {
                "method": "DELETE",
                "endpoint": "api/v1/pictures/25"
              },
              "data": [],
              "errors": {
                "message": "The resource that matches ID:25 does not found.",
                "code": 403001
              },
              "duration": 0.168
            }

## Uploads a picture image [/pictures/{id}/upload]

Uploads a image file and associates it to the picture resource that matches the ID, and then returns the associated picture resource's ID.    

### POST

+ Parameters
    + id: `30` (required, int) - The picture's primary ID.
    + file: `image.jpg` (required, file) - A uploading picture image.

+ Response 201 (application/json)

    + Body

            {
              "success": 1,
              "code": 201,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/pictures/3/upload"
              },
              "data": {
                "updated": 1,
                "updated_id": "3"
              },
              "errors": [],
              "duration": 5.359
            }

+ Response 400 (application/json)

    A error has occurred while validation.

    + Body

            {
              "success": 0,
              "code": 400,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/pictures/3/upload"
              },
              "data": [],
              "errors": {
                "validation": {
                  "file": [
                      {
                        "key": "required",
                        "message": "The file is required."
                      },
                      {
                        "key": "mimes:jpeg,jpg,png,bmp,gif",
                        "message": "The file must be a file of type: jpeg, jpg, png, bmp, gif."
                      }
                  ]
                },
                "message": "The request parameters are incorrect, please make sure to follow the HiCat document.",
                "code": 400002
              },
              "duration": 0.245
            }

+ Response 403

    When the picture does not exist.

    + Body

            {
              "success": 0,
              "code": 403,
              "meta": {
                "method": "POST",
                "endpoint": "api/v1/pictures/31/upload"
              },
              "data": [],
              "errors": {
                "message": "The resource that matches ID:31 does not found.",
                "code": 403001
              },
              "duration": 0.257
            }

+ Response 500

    A error has occurred while uploading a file.

    + Body

            {
                "success": 0,
                "code": 500,
                "meta": {
                    "method": "PUT",
                    "endpoint": "api/v1/pictures/31/upload"
                },
                "data": {},
                "errors": {
                    "message": "A fatal error has occurred while creating the files to storage, please try again.",
                    "code": "500010"
                },
                "duration": 0.593
            }


## Downloads a picture image [/pictures/{id}/download]

Downloads a picture image by a specified size.

### GET

+ Parameters
    + id: `30` (required, int) - The picture's primary ID.
    + size: `full` (optinal, string) - A downloading picture's size that can be specified from `full`, `medium`, `thumbnail`.

__NOTE: I left the `application/image` on the `200` response, but to test I had to use `image/*`.__


+ Response 200 (application/image)

+ Response 403 (application/json)

    When the picture does not exist.

    + Body

            {
              "success": 0,
              "code": 403,
              "meta": {
                "method": "GET",
                "endpoint": "api/v1/pictures/31/download"
              },
              "data": [],
              "errors": {
                "message": "The resource that matches ID:31 does not found.",
                "code": 403001
              },
              "duration": 0.164
            }
