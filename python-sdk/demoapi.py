'''
@name Demo Trial API Python SDK
@version: 1.0
@author: Cloudaccess.net 
'''

import urllib
import urllib2
import httplib
import json
import urlparse

class DemoApiException(Exception):
    def __init__(self, message, code=-1):
        super(DemoApiException, self).__init__(message)
        self.code = code

class DemoApi(object):
    def __init__(self, config):
        if not config.has_key('client_id'):
            raise DemoApiException('Client ID not specified in the config', 1)
        if not config.has_key('client_secret'):
            raise DemoApiException('Client Secret not specified in the config', 1)
        
        self._host = 'https://api.cloudaccess.net'
        self._client_id = config['client_id']
        self._client_secret = config['client_secret']
        self._verify_ssl = config['verify_ssl'] if config.has_key('verify_sll') else False
        self._code = None
        self._access_token = config['access_token'] if config.has_key('verify_sll') else None
        self._refresh_token = config['refresh_token'] if config.has_key('verify_sll') else None
    
    def get_code(self, redirect_uri=''):
        url = self._build_url('/oauth/authorization', {
                                                        'client_id': self._client_id,
                                                        'response_type': 'code',
                                                        'redirect_uri': redirect_uri,
                                                       })
        r = self._request(url)
        qs = urlparse.urlparse(r['headers']['location'])
        qs = urlparse.parse_qs(qs.query)
        self._code = qs['code'][0]
        return self._code
    
    def get_token(self, redirect_uri=''):
        url = self._build_url('/oauth/token')
        r = self._request(url, {
                                'client_id': self._client_id,
                                'client_secret': self._client_secret,
                                'code': self._code,
                                'scope': 'global',
                                'grant_type': 'authorization_code',
                                'redirect_uri': redirect_uri
                                })
        self._access_token = r['body']['access_token']
        self._refresh_token = r['body']['refresh_token']
        return r['body']
    
    def refresh_token(self, redirect_uri=''):
        url = self._build_url('/oauth/token')
        r = self._request(url, {
                                'client_id': self._client_id,
                                'client_secret': self._client_secret,
                                'refresh_token': self._refresh_token,
                                'scope': 'global',
                                'grant_type': 'refresh_token',
                                'redirect_uri': redirect_uri
                                })
        self._access_token = r['body']['access_token']
        self._refresh_token = r['body']['refresh_token']
        return r['body']
    
    def api(self, method, args=None):
        url = self._build_url('/api')
        if args is None:
            args = {}
        args.update({
                    'client_id': self._client_id,
                    'access_token': self._access_token,
                    'method': method,
                    'token_type': 'Bearer',
                    'scope': 'global',
                    })
        r = self._request(url, args)
        return r['body']
    
    def login(self, redirect_uri=''):
        self.get_code()
        return self.get_token(redirect_uri)
    
    def _build_url(self, endpoint, args=None):
        return '%s%s?%s' % (self._host, endpoint, urllib.urlencode(args, doseq=True)) if args else '%s%s' % (self._host, endpoint)
    
    def _request(self, url, args=None):
        r = self._curl(url, args)
        r['body'] = json.loads(r['body']) if len(r['body']) else None
        if r['info']['http_code'] not in [200, 302, 301]:
            raise DemoApiException(r['body']['error'], r['info']['http_code'])
        return r
        
    def _curl_header(self, response):
        headers = {}
        for item in response:
            item = item.split(':')
            headers[item[0].strip().lower()] = item[1].strip()
        return headers
    
    def _curl(self, url, args=None):
        class HTTPSClientAuthHandler(urllib2.HTTPSHandler):
            def __init__(self, key, cert):
                urllib2.HTTPSHandler.__init__(self)
                self.key = key
                self.cert = cert
        
            def https_open(self, req):
                return self.do_open(self.getConnection, req)
        
            def getConnection(self, host, timeout=300):
                return httplib.HTTPSConnection(host, key_file=self.key, cert_file=self.cert)
        
        class NoRedirection(urllib2.HTTPErrorProcessor):
            def http_response(self, request, response):
                return response
            https_response = http_response
        
        if self._verify_ssl:
            print 'ssl'
            opener = urllib2.build_opener(HTTPSClientAuthHandler('cloudaccess.net.pem','cloudaccess.net.pem'))
        else:
            opener = urllib2.build_opener(NoRedirection, urllib2.HTTPSHandler)
        r = opener.open(url, urllib.urlencode(args, doseq=True) if args else None)
        return {'info': {'http_code': r.getcode()}, 'headers': self._curl_header(r.info().headers), 'body': r.read()}