# demo-trial-api #


API Methods
==============

### CreateService (`client_details`, `demo_details`) ###
Creates the demo service 

 Name               | Type               | Required | Description
:-------------------|:-------------------|:---------|:-------------
 `p_client_details` | Array              | Yes      | Associative array representing client details
 `p_demo_details`   | Array              | Yes      | Associative array representing details for the demo instance
 
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
_Note: `*` indicates required keys_

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
### GetAsyncOptStatus (`taskid`) ###
Fetch the status for the `CreateService` task

 Name             | Type               | Required | Description  
:-----------------|:-------------------|:---------|:--------------
 `p_taskid`       | String             | Yes      | Id returned from the `CreateService` response

#### Response ####
```
    []
		result
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
