<?php
/*
 * Renders HTML head, script, footer, etc.
 *      TODO: Maybe it's impossible but it would be nice to get total control of what Joomla outputs on its "head".
 */
class Alchemy_HTML
{
    protected static $_groups = array();
    protected static $_initialized = FALSE;
    
	public function initialize()
	{
        // Make sure this function isn't called twice
        if (self::$_initialized) 
        {
            return false;
        }
        
        $template_path = Alchemy::config('template_path');
        $media_path = Alchemy::config('media_path');
        
        $head = array('<jdoc:include type="head" />');
        
        
        if (Alchemy::config('use_chrome_frame')) 
        {
            // Disables IE8 Compatibility mode and uses Chrome Frame if it is installed
            $head[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
        }

        if (Alchemy::config('adjust_mobile_viewport')) 
        {
            // Adjust view port on mobile devices for optimal viewing
            $head[] = '<meta name="viewport" content="width=device-width; initial-scale=1.0">';
        }
        
        // Include Modernizr and browser selector
        $head[] = '<script type="text/javascript" src="'.$media_path.'/alchemy/js/initialize.js"></script>';

        if (Alchemy::config('development_mode')) 
        {
            $head[] = '<script type="text/javascript">';
            $head[] = 'var less = {"env": "development"};';
            $head[] = 'less.variables = {';
            $head[] = 'media_url: "'.$media_path.'",';
            $head[] = 'template_url: "'.$template_path.'"';
            $head[] = '};';
            $head[] = '</script>';
            
            $head[] = '<link rel="stylesheet/less" type="text/css" href="'.$template_path.'/css/development.less" title="mt-alchemy-css" />';
            $head[] = '<script type="text/javascript" src="'.$media_path.'/alchemy/js/less.js"></script>';
            $head[] = '<script type="text/javascript">less.watch()</script>';
        }
        else
        {
            $head[] = '<link rel="stylesheet" type="text/css" href="'.$template_path.'/css/template.css" />';
        }

        // IE7-js for improving IE
        $head[] = '<!--[if lt IE 8]>';
        $head[] = '<script type="text/javascript">var IE7_PNG_SUFFIX = ".png"</script>';
        $head[] = '<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>';
        $head[] = '<![endif]-->';
        
        self::$_groups['head'] = $head;
        
        self::$_initialized = TRUE;
	}
	
	public function render($group, $namespace = NULL)
	{
        if (!self::$_initialized) 
        {
            self::initialize();
        }

        if (isset(self::$_groups[$group]) AND !empty(self::$_groups[$group]))
        {
            return implode("\n", self::$_groups[$group]);
        }
	}
}