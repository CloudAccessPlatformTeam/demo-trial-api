'''
@name Demo Trial API Python SDK Example
@version: 1.0
@author: Cloudaccess.net 
'''

from demoapi import DemoApi, DemoApiException

dapi = DemoApi({
                    'client_id': 'user@cloudaccess.net',
                    'client_secret': 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                    'verify_ssl': True
                })

try:
	# Get the tokens
    print dapi.login()
    
    #  Refresh if token is close to expiry
    # dapi.refresh_token()
    
    # Make call
    print dapi.api('ListDatasets', {
                                'p_application': 'joomla'
    })
except DemoApiException as e:
    print 'Error:', e.code, '->', e.message