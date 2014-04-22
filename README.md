# demo-trial-api #


API Methods
==============

### CreateService (`firstname`, `lastname`, `email`, `password`, `phonenumber`, `city`, `companyname`, `city`, `state`, `postcode`, `country`, `domain`, `application`) ###
Creates the demo service 

 Name                | Type               | Required | Description
:--------------------|:-------------------|:---------|:-------------
 `p_firstname`       | String             | Yes      | User's first name
 `p_lastname`        | String             | Yes      | User's last name
 `p_email`           | String             | Yes      | User's email id
 `p_password`        | String             | Yes      | Plain text password for admin user
 `p_phonenumber`     | String             | Yes      | User's phone number
 `p_address1`        | String             | No       | User's address
 `p_companyname`     | String             | No       | User's company name
 `p_city`            | String             | Yes      | User's city of residence
 `p_state`           | String             | Yes      | User's state of residence
 `p_postcode`        | String             | Yes      | User's postal code
 `p_country`         | String             | Yes      | User's country of residence
 `p_domain`          | String             | Yes      | Cloudaccess sub-domain to use for the application (without scheme or `www.`)
 `p_application`     | String             | Yes      | Application to be created. Format `name-version`, eg: `joomla-3.2`. The supported versions/families can be obtained via `ListDatasets`
 `p_datasetid`       | Integer            | No       | Dataset Id to use while creating the application. The supported datasets can be obtained via `ListDatasets`
 
#### Response ####
```
    []
    	taskid
```
```
    []
		error
		err_code
```


### ListDatasets (`application`, `affid`) ###
Lists the allowed datasets for `CreateService`

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `p_application`  | String             | Yes      | String representing the application, `joomla` or `wordpress`
 `p_affid`        | Integer            | Yes      | Id signifying the affiliate to fetch the dataset list for

#### Response ####
```
	[]
		[result]
			[datasets]
				[joomla]
					[0]
						date_added
						version
						app_family
						datasetid
						name
					[1]
						date_added
						version
						app_family
						datasetid
						name
			[families]
				[joomla]
					0
					1
```
```
	[]
		error
		err_code
```
### GetAsyncOpStatus (`taskid`) ###
Fetch the status for the `CreateService` task

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `p_opid`         | String             | Yes      | Id returned from the `CreateService` response

#### Response ####
```
    []
		status (pending|running|succeeded|failed)
		error
```
```
    []
		error
		err_code
```


### CheckDomainExistance (`domain`) ###
Validates the existance of a domain for `CreateService`

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `p_domain`       | String             | Yes      | Domain name to check (excluding scheme and `www.`)

#### Response ####
```
    []
		result (bool)
```
```
	[]
		error
		err_code
```

### CheckEmailExistance (`email`) ###
Validates the existance of an email for `CreateService`

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `p_email`        | String             | Yes      | Email ID to check

#### Response ####
```
    []
		result (bool)
```
```
	[]
		error
		err_code
```
