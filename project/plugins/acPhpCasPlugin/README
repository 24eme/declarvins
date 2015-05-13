# acPhpCasPlugin (for symfony 1.3/1.4) #

Allows you to use easily the phpCAS library (Central Authentication Service).

## Installation ##

  * Install the plugin (via a package)

        symfony plugin:install acPhpCasPlugin

  * Install the plugin (via a git clone)
    
        git clone git://gitorious.org/accasplugin/accasplugin.git acPhpCasPlugin

  * Activate the plugin in the `config/ProjectConfiguration.class.php`
  
        [php]
        class ProjectConfiguration extends sfProjectConfiguration
        {
          public function setup()
          {
            $this->enablePlugins(array(
              'sfDoctrinePlugin', 
              'acPhpCasPlugin',
              '...'
            ));
          }
        }

  * Configure the plugin in the `config/app.yml` of the application
  
        [yaml]
        all:
          ...
          ac_php_cas:
            domain: "**********.***"
            port: 443
            path: "****"
            url : "https://**********.***"
          ...
        
  * Clear your cache

        symfony cc
        
  * Note : the phpCAS library is included in the plugin (in its stable version 1.2.2)
        
## Use ##

  * You have access to the phpCAS methods with the acPhpCas class (all methods are static and compatible PHP 5.x), for example :
  
        [php]
        acPhpCas::client();
        acPhpCas::forceAuthentication();
       
  * For more information on phpCAS, see the project and documentation at [phpCAS Project](https://wiki.jasig.org/display/CASC/phpCAS)