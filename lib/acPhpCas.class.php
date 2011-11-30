<?php
/* This file is part of the acPhpCasPlugin package.
 * (c) Actualys
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * acPhpCas allows you to use the phpCas library
 *
 * @package    acPhpCasPlugin
 * @subpackage lib
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @version    0.1
 */
class acPhpCas
{	

	/**
	 * phpCAS client initializer.
	 * @note Only one of the phpCAS::client() and phpCAS::proxy functions should be
	 * called, only once, and before all other methods (except phpCAS::getVersion()
	 * and phpCAS::setDebug()).
	 *
	 * @param $server_version the version of the CAS server
	 * @param $server_hostname the hostname of the CAS server
	 * @param $server_port the port the CAS server is running on
	 * @param $server_uri the URI the CAS server is responding on
	 * @param $start_session Have phpCAS start PHP sessions (default true)
	 *
	 * @return a newly created CASClient object
	 */
	public static function client($start_session = false) 
	{
		error_reporting(E_ALL);
		phpCAS::client(CAS_VERSION_2_0, sfConfig::get('app_ac_php_cas_domain'), sfConfig::get('app_ac_php_cas_port'), sfConfig::get('app_ac_php_cas_path'), $start_session);
	}

	/**
	 * phpCAS proxy initializer.
	 * @note Only one of the phpCAS::client() and phpCAS::proxy functions should be
	 * called, only once, and before all other methods (except phpCAS::getVersion()
	 * and phpCAS::setDebug()).
	 *
	 * @param $server_version the version of the CAS server
	 * @param $server_hostname the hostname of the CAS server
	 * @param $server_port the port the CAS server is running on
	 * @param $server_uri the URI the CAS server is responding on
	 * @param $start_session Have phpCAS start PHP sessions (default true)
	 *
	 * @return a newly created CASClient object
	 */
	public static function proxy($start_session = false) 
	{
		error_reporting(E_ALL);
		phpCAS::proxy(CAS_VERSION_2_0, sfConfig::get('app_ac_php_cas_domain'), sfConfig::get('app_ac_php_cas_port'), sfConfig::get('app_ac_php_cas_path'), $start_session);		
	}

	/**
	 * Set/unset debug mode
	 *
	 * @param $filename the name of the file used for logging, or FALSE to stop debugging.
	 */
	public static function setDebug($filename = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setDebug($filename);
	}

	/**
	 * This method is a wrapper for debug_backtrace() that is not available 
	 * in all PHP versions (>= 4.3.0 only)
	 */
	public static function backtrace() 
	{
		error_reporting(E_ALL);
		return phpCAS::backtrace();
	}

	/**
	 * Logs a string in debug mode.
	 *
	 * @param $str the string to write
	 *
	 * @private
	 */
	public static function log($str) 
	{
		error_reporting(E_ALL);
		phpCAS::log($str);
	}

	/**
	 * This method is used by interface methods to print an error and where the function
	 * was originally called from.
	 *
	 * @param $msg the message to print
	 *
	 * @private
	 */
	public static function error($msg) 
	{
		error_reporting(E_ALL);
		phpCAS::error($msg);		
	}

	/**
	 * This method is used to log something in debug mode.
	 */
	public static function trace($str) 
	{
		error_reporting(E_ALL);
		phpCAS::trace($str);		
	}

	/**
	 * This method is used to indicate the start of the execution of a function in debug mode.
	 */
	public static function traceBegin() 
	{
		error_reporting(E_ALL);
		phpCAS::traceBegin();		
	}

	/**
	 * This method is used to indicate the end of the execution of a function in debug mode.
	 *
	 * @param $res the result of the function
	 */
	public static function traceEnd($res = '') 
	{
		error_reporting(E_ALL);
		phpCAS::traceEnd($res = '');		
	}

	/**
	 * This method is used to indicate the end of the execution of the program
	 */
	public static function traceExit() 
	{
		error_reporting(E_ALL);
		phpCAS::traceExit();		
	}

	/**
	 * This method is used to set the language used by phpCAS. 
	 * @note Can be called only once.
	 *
	 * @param $lang a string representing the language.
	 *
	 * @sa PHPCAS_LANG_FRENCH, PHPCAS_LANG_ENGLISH
	 */
	public static function setLang($lang) 
	{
		error_reporting(E_ALL);
		phpCAS::setLang($lang);		
	}

	/**
	 * This method returns the phpCAS version.
	 *
	 * @return the phpCAS version.
	 */
	public static function getVersion() 
	{
		error_reporting(E_ALL);
		return phpCAS::getVersion();		
	}

	/**
	 * This method sets the HTML header used for all outputs.
	 *
	 * @param $header the HTML header.
	 */
	public static function setHTMLHeader($header) 
	{
		error_reporting(E_ALL);
		phpCAS::setHTMLHeader($header);		
	}

	/**
	 * This method sets the HTML footer used for all outputs.
	 *
	 * @param $footer the HTML footer.
	 */
	public static function setHTMLFooter($footer) 
	{
		error_reporting(E_ALL);
		phpCAS::setHTMLFooter($footer);		
	}

	/**
	 * This method is used to tell phpCAS to store the response of the
	 * CAS server to PGT requests onto the filesystem. 
	 *
	 * @param $format the format used to store the PGT's (`plain' and `xml' allowed)
	 * @param $path the path where the PGT's should be stored
	 */
	public static function setPGTStorageFile($format = '', $path = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setPGTStorageFile($format, $path);		
	}

	/**
	 * This method is used to tell phpCAS to store the response of the
	 * CAS server to PGT requests into a database. 
	 * @note The connection to the database is done only when needed. 
	 * As a consequence, bad parameters are detected only when 
	 * initializing PGT storage, except in debug mode.
	 *
	 * @param $user the user to access the data with
	 * @param $password the user's password
	 * @param $database_type the type of the database hosting the data
	 * @param $hostname the server hosting the database
	 * @param $port the port the server is listening on
	 * @param $database the name of the database
	 * @param $table the name of the table storing the data
	 */
	public static function setPGTStorageDB($user, $password, $database_type = '', $hostname = '', $port = 0, $database = '', $table = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setPGTStorageDB($user, $password, $database_type, $hostname, $port, $database, $table);
	}

	/**
	 * This method is used to access an HTTP[S] service.
	 * 
	 * @param $url the service to access.
	 * @param $err_code an error code Possible values are PHPCAS_SERVICE_OK (on
	 * success), PHPCAS_SERVICE_PT_NO_SERVER_RESPONSE, PHPCAS_SERVICE_PT_BAD_SERVER_RESPONSE,
	 * PHPCAS_SERVICE_PT_FAILURE, PHPCAS_SERVICE_NOT AVAILABLE.
	 * @param $output the output of the service (also used to give an error
	 * message on failure).
	 *
	 * @return TRUE on success, FALSE otherwise (in this later case, $err_code
	 * gives the reason why it failed and $output contains an error message).
	 */
	public static function serviceWeb($url, & $err_code, & $output) 
	{
		error_reporting(E_ALL);
		return phpCAS::serviceWeb($url, $err_code, $output);		
	}

	/**
	 * This method is used to access an IMAP/POP3/NNTP service.
	 * 
	 * @param $url a string giving the URL of the service, including the mailing box
	 * for IMAP URLs, as accepted by imap_open().
	 * @param $service a string giving for CAS retrieve Proxy ticket
	 * @param $flags options given to imap_open().
	 * @param $err_code an error code Possible values are PHPCAS_SERVICE_OK (on
	 * success), PHPCAS_SERVICE_PT_NO_SERVER_RESPONSE, PHPCAS_SERVICE_PT_BAD_SERVER_RESPONSE,
	 * PHPCAS_SERVICE_PT_FAILURE, PHPCAS_SERVICE_NOT AVAILABLE.
	 * @param $err_msg an error message on failure
	 * @param $pt the Proxy Ticket (PT) retrieved from the CAS server to access the URL
	 * on success, FALSE on error).
	 *
	 * @return an IMAP stream on success, FALSE otherwise (in this later case, $err_code
	 * gives the reason why it failed and $err_msg contains an error message).
	 */
	public static function serviceMail($url, $service, $flags, & $err_code, & $err_msg, & $pt) 
	{
		error_reporting(E_ALL);
		return phpCAS::serviceMail($url, $service, $flags, $err_code, $err_msg, $pt);		
	}

	/**
	 * Set the times authentication will be cached before really accessing the CAS server in gateway mode: 
	 * - -1: check only once, and then never again (until you pree login)
	 * - 0: always check
	 * - n: check every "n" time
	 *
	 * @param $n an integer.
	 */
	public static function setCacheTimesForAuthRecheck($n) 
	{
		error_reporting(E_ALL);
		phpCAS::setCacheTimesForAuthRecheck($n);		
	}

	/**
	 * This method is called to check if the user is authenticated (use the gateway feature).
	 * @return TRUE when the user is authenticated; otherwise FALSE.
	 */
	public static function checkAuthentication() 
	{
		error_reporting(E_ALL);
		return phpCAS::checkAuthentication();		
	}

	/**
	 * This method is called to force authentication if the user was not already 
	 * authenticated. If the user is not authenticated, halt by redirecting to 
	 * the CAS server.
	 */
	public static function forceAuthentication() 
	{
		error_reporting(E_ALL);
		return phpCAS::forceAuthentication();		
	}

	/**
	 * This method is called to renew the authentication.
	 **/
	public static function renewAuthentication() 
	{
		error_reporting(E_ALL);
		phpCAS::renewAuthentication();		
	}

	/**
	 * This method has been left from version 0.4.1 for compatibility reasons.
	 */
	public static function authenticate() 
	{
		error_reporting(E_ALL);
		phpCAS::authenticate();		
	}

	/**
	 * This method is called to check if the user is authenticated (previously or by
	 * tickets given in the URL).
	 *
	 * @return TRUE when the user is authenticated.
	 */
	public static function isAuthenticated() 
	{
		error_reporting(E_ALL);
		return phpCAS::isAuthenticated();		
	}

	/**
	 * Checks whether authenticated based on $_SESSION. Useful to avoid
	 * server calls.
	 * @return true if authenticated, false otherwise.
	 */
	public static function isSessionAuthenticated() 
	{
		error_reporting(E_ALL);
		return phpCAS::isSessionAuthenticated();		
	}

	/**
	 * This method returns the CAS user's login name.
	 * @warning should not be called only after phpCAS::forceAuthentication()
	 * or phpCAS::checkAuthentication().
	 *
	 * @return the login name of the authenticated user
	 */
	public static function getUser() 
	{
		error_reporting(E_ALL);
		return phpCAS::getUser();		
	}

	/**
	 * This method returns the CAS user's login name.
	 * @warning should not be called only after phpCAS::forceAuthentication()
	 * or phpCAS::checkAuthentication().
	 *
	 * @return the login name of the authenticated user
	 */
	public static function getAttributes() 
	{
		error_reporting(E_ALL);
		return phpCAS::getAttributes();		
	}
	
	/**
	 * Handle logout requests.
	 */
	public static function handleLogoutRequests($check_client = true, $allowed_clients = false) 
	{
		error_reporting(E_ALL);
		return phpCAS::handleLogoutRequests($check_client, $allowed_clients);		
	}

	/**
	 * This method returns the URL to be used to login.
	 * or phpCAS::isAuthenticated().
	 *
	 * @return the login name of the authenticated user
	 */
	public static function getServerLoginURL() 
	{
		error_reporting(E_ALL);
		return phpCAS::getServerLoginURL();		
	}

	/**
	 * Set the login URL of the CAS server.
	 * @param $url the login URL
	 */
	public static function setServerLoginURL($url = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setServerLoginURL($url);		
	}

	/**
	 * Set the serviceValidate URL of the CAS server.
	 * Used only in CAS 1.0 validations
	 * @param $url the serviceValidate URL
	 */
	public static function setServerServiceValidateURL($url = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setServerServiceValidateURL($url);		
	}

	/**
	 * Set the proxyValidate URL of the CAS server.
	 * Used for all CAS 2.0 validations
	 * @param $url the proxyValidate URL
	 */
	public static function setServerProxyValidateURL($url = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setServerProxyValidateURL($url);		
	}

	/**
	 * Set the samlValidate URL of the CAS server.
	 * @param $url the samlValidate URL
	 */
	public static function setServerSamlValidateURL($url = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setServerSamlValidateURL($url);		
	}

	/**
	 * This method returns the URL to be used to login.
	 * or phpCAS::isAuthenticated().
	 *
	 * @return the login name of the authenticated user
	 */
	public static function getServerLogoutURL() 
	{
		error_reporting(E_ALL);
		return phpCAS::getServerLogoutURL();		
	}

	/**
	 * Set the logout URL of the CAS server.
	 * @param $url the logout URL
	 */
	public static function setServerLogoutURL($url = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setServerLogoutURL($url);		
	}

	/**
	 * This method is used to logout from CAS.
	 * @params $params an array that contains the optional url and service parameters that will be passed to the CAS server
	 * @public
	 */
	public static function logout($params = "") 
	{
		error_reporting(E_ALL);
		phpCAS::logout($params);		
	}

	/**
	 * This method is used to logout from CAS. Halts by redirecting to the CAS server.
	 * @param $service a URL that will be transmitted to the CAS server
	 */
	public static function logoutWithRedirectService($service) 
	{
		error_reporting(E_ALL);
		phpCAS::logoutWithRedirectService($service);		
	}

	/**
	 * This method is used to logout from CAS. Halts by redirecting to the CAS server.
	 * @param $url a URL that will be transmitted to the CAS server
	 */
	public static function logoutWithUrl($url) 
	{
		error_reporting(E_ALL);
		phpCAS::logoutWithUrl($url);		
	}

	/**
	 * This method is used to logout from CAS. Halts by redirecting to the CAS server.
	 * @param $service a URL that will be transmitted to the CAS server
	 * @param $url a URL that will be transmitted to the CAS server
	 */
	public static function logoutWithRedirectServiceAndUrl($service, $url) 
	{
		error_reporting(E_ALL);
		phpCAS::logoutWithRedirectServiceAndUrl($service, $url);		
	}

	/**
	 * Set the fixed URL that will be used by the CAS server to transmit the PGT.
	 * When this method is not called, a phpCAS script uses its own URL for the callback.
	 *
	 * @param $url the URL
	 */
	public static function setFixedCallbackURL($url = '') 
	{
		error_reporting(E_ALL);
		phpCAS::setFixedCallbackURL($url);
		
	}

	/**
	 * Set the fixed URL that will be set as the CAS service parameter. When this
	 * method is not called, a phpCAS script uses its own URL.
	 *
	 * @param $url the URL
	 */
	public static function setFixedServiceURL($url) 
	{
		error_reporting(E_ALL);
		phpCAS::setFixedServiceURL($url);		
	}

	/**
	 * Get the URL that is set as the CAS service parameter.
	 */
	public static function getServiceURL() 
	{
		error_reporting(E_ALL);
		return phpCAS::getServiceURL();
	}

	/**
	 * Retrieve a Proxy Ticket from the CAS server.
	 */
	public static function retrievePT($target_service, & $err_code, & $err_msg) 
	{
		error_reporting(E_ALL);
		return phpCAS::retrievePT($target_service, $err_code, $err_msg);
	}

	/**
	 * Set the certificate of the CAS server.
	 *
	 * @param $cert the PEM certificate
	 */
	public static function setCasServerCert($cert) 
	{
		error_reporting(E_ALL);
		phpCAS::setCasServerCert($cert);
	}

	/**
	 * Set the certificate of the CAS server CA.
	 *
	 * @param $cert the CA certificate
	 */
	public static function setCasServerCACert($cert) 
	{
		error_reporting(E_ALL);
		phpCAS::setCasServerCACert($cert);		
	}

	/**
	 * Set no SSL validation for the CAS server.
	 */
	public static function setNoCasServerValidation() 
	{
		error_reporting(E_ALL);
		phpCAS::setNoCasServerValidation();		
	}

	/**
	 * Change CURL options.
	 * CURL is used to connect through HTTPS to CAS server
	 * @param $key the option key
	 * @param $value the value to set
	 */
	public static function setExtraCurlOption($key, $value) 
	{
		error_reporting(E_ALL);
		phpCAS::setExtraCurlOption($key, $value);		
	}
}