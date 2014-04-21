# demo-trial-api #


API Methods
==============

## CreateService (`client_details`, `demo_details`) ##
Creates the demo service 

 Name             | Type               | Required | Description
:-----------------|:-------------------|:---------|:-------------
 `client_details` | Array              | Yes      | Associative array representing client details
 `demo_details`   | Array              | Yes      | Associative array representing details for the demo instance
 
```
    client_details
        firstname*
        lastname*
        companyname
        email*
        address1
        address2
        city
        state
        postcode
        country
        phonenumber
        password*
    demo_details
        p_domain*
        p_application*
        p_datasetid

```

#### Response ####
```
    Error Code?
```

_Note: `*` indicates required keys_


## ListDatasets (`application`, `affid`) ##
Lists the allowed datasets for `CreateService`

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `application`    | String             | Yes      | String representing the application, `joomla` or `wordpress`
 `affid`          | Integer            | Yes      | Id signifying the affiliate to fetch the dataset list for

#### Response ####
```
    Error Code?
```

## GetAsyncOptStatus (`taskid`) ##
Fetch the status for the `CreateService` task

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `taskid`         | String             | Yes      | Id returned from the `CreateService` response

#### Response ####
```
    Error Code?
```


## CheckDomainExistance (`domain`) ##
Validates the existance of a domain for `CreateService`

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `domain`         | String             | Yes      | Domain name to check (excluding scheme and `www.`)

#### Response ####
```
    Error Code?
```

## CheckEmailExistance (`email`) ##
Validates the existance of an email for `CreateService`

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `email`          | String             | Yes      | Email ID to check

#### Response ####
```
    Error Code?
```
