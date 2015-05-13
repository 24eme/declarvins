# acDompdfPlugin (for symfony 1.3/1.4) #

Allows you to use easily the DOMPDF library (an HTML to PDF converter).

## Installation ##

  * Install the plugin (via a package)

        symfony plugin:install acDompdfPlugin

  * Install the plugin (via a git clone)
    
        git clone git://gitorious.org/acdompdfplugin/acdompdfplugin.git acDompdfPlugin

  * Activate the plugin in the `config/ProjectConfiguration.class.php`
  
        [php]
        class ProjectConfiguration extends sfProjectConfiguration
        {
          public function setup()
          {
            $this->enablePlugins(array(
              'sfDoctrinePlugin', 
              'acDompdfPlugin',
              '...'
            ));
          }
        }
        
  * Clear you cache

        symfony cc
        
  * Note : the DOMPDF library is included in the plugin (in its stable version 0.5.2)
        
## Use ##

  * The DOMPDF class is autoloaded now, you can use it easily
  
        [php]
        $pdf = new DOMPDF();
       
  * For more information on DOMPDF, see the project and documentation at [DOMPDF Project](http://code.google.com/p/dompdf/)