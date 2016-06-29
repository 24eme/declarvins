<?php
class CielService
{
	private static $_instance = null;
	protected $configuration;
	const TOKEN_CACHE_FILENAME = 'ciel_access_token';
	const TOKEN_TIME_VALIDITY = 2700;
	
	public function __construct()
	{
		$this->configuration = sfConfig::get('app_ciel_oauth');
		if (!$this->configuration) {
			throw new sfException('CielService Error : Yml configuration not found for CIEL');
		}
	}
	
	public static function getInstance()
    {
       	if(is_null(self::$_instance)) {
       		self::$_instance = new CielService();
		}
		return self::$_instance;
    }
    
    public function getToken()
    {
    	if ($this->needNewToken()) {
    		$token = $this->sign();
    		$this->setTokenCache($token);   		
    	} else {
    		$file = $this->getTokenCacheFilename();
    		$token = file_get_contents($file);
    		if ($token === false) {
    			throw new sfException('CielService Error : cannot read '.self::TOKEN_CACHE_FILENAME);
    		}
    	}
    	return $token;
    }
	
	public function sign()
	{
		$encrypted = '';
		$key = openssl_pkey_get_private('file://'.$this->configuration['keypath']);
		if (!$key) {
			throw new sfException('CielService Error : Openssl get private key failed');
		}
		$datas = $this->getDatas();
		if (!openssl_sign($datas, $encrypted, $key, 'SHA256')) {
			throw new sfException('CielService Error : '.openssl_error_string());
		}
		$oauth = $datas.'.'.$this->base64SafeEncode($encrypted);
		$data = array('grant_type'=> 'urn:ietf:params:oauth:grant-type:jwt-bearer' , 'assertion' => $oauth);
		$result = json_decode(file_get_contents($this->configuration['urltoken'], false, stream_context_create(array('http' => $this->getOauthHttpRequest($data)))), true);
		if (isset($result['error'])) {
			throw new sfException('CielService Error : '.$result['error'].' : '.$result['message']);
		}
		if (!isset($result['access_token'])) {
			throw new sfException('CielService Error : '.json_encode($result));
		}
		return $result['access_token'];
	}
	
	public function transfer($datas = null, $token = null)
	{
		if (!$token) {
			$token = $this->getToken();
		}
		$result = file_get_contents($this->configuration['urlapp'], false, stream_context_create(array('http' => $this->getTransferHttpRequest($token, $datas))));
		return $result;
	}
	
	protected function needNewToken()
	{
		$file = $this->getTokenCacheFilename();
		if (file_exists($file)) {
			$timestamp = filemtime($file);
			if (($timestamp + self::TOKEN_TIME_VALIDITY) >= time()) {
				return false;
			}
		}
		return true;
	}
	
	protected function setTokenCache($token) 
	{
		$file = $this->getTokenCacheFilename();
		$result = file_put_contents($file, $token, LOCK_EX);
		if ($result === false) {
			throw new sfException('CielService Error : cannot write in '.self::TOKEN_CACHE_FILENAME);
		}
	}
	
	protected function getTokenCacheFilename()
	{
		return sfConfig::get('sf_data_dir').'/ciel/'.self::TOKEN_CACHE_FILENAME;
	}
	
	protected function getOauthHttpRequest($content)
	{
		return array('header'  => "Host: ".$this->configuration['host']."\nContent-Type: application/x-www-form-urlencoded\n",
					 'method'  => 'POST',
					 'protocol_version' => 1.1,
					 'ignore_errors' => true,
					 'content' => http_build_query($content));
	}
	
	protected function getTransferHttpRequest($token, $content = null)
	{
		return array('header'  => "Host: ".$this->configuration['host']."\nContent-Type: application/xml;charset=UTF-8\nAuthorization: Bearer $token\n",
				'method'  => 'POST',
				'protocol_version' => 1.1,
				'ignore_errors' => true,
				'content' => $content);
	}
	
	protected function getDatas()
	{
		$entete = '{"alg":"RS256"}';
		$corps = '{"iss":"'.$this->configuration['iss'].'","scope":"'.$this->configuration['service'].'","aud":"'.$this->configuration['url'].'","iat":'.time().'000}';
		return $this->base64SafeEncode($entete).'.'.$this->base64SafeEncode($corps);
	}
	
	protected function base64SafeEncode($input)
	{
		return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
	}
}