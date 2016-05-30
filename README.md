TYPO3 Extension cabag_langlink
================================
This extension makes use of hooks and xclassing to extend the Link Browser in TYPO3 CMS and the typolink handling so editors can set a link to a page/content element in another website language.

![Language link](https://raw.github.com/visol/ext-cabag_langlink/master/Resources/Public/Readme/cabag-langlink.png)

Installation
----------

Since we are not the owner of the extension key, you have to download the extension from Github or clone it using Git:

    git clone https://github.com/visol/ext-cabag_langlink.git cabag_langlink
    
You can also load it using Composer (but not via Packagist or TYPO3 TER) as follows:

    {
    	"repositories": [
    		{
    			"type": "composer",
    			"url": "https://composer.typo3.org/"
    		},
    		{
    			"type": "git",
    			"url": "https://github.com/visol/ext-cabag_langlink"
    		}
    	],
    	"license": "GPL-2.0+",
    	"config": {
    		"vendor-dir": "Packages/Libraries",
    		"bin-dir": "bin"
    	},
    	"require": {
    		"typo3/cms": "~6.2",
    		"cabag/cabag-langlink": "~1.0"
        }
    }

Configuration
-----------
It is possible to change the language parameter (default: L) to your own language parameter by changing the option in the extension manager configuration of this extension.

If you haven't set a flag for your default language in the backend you will see a general language icon for the link to default language. Use Page TSconfig to define the flag and label for the default language:

    mod.SHARED {
    	defaultLanguageFlag = de.gif
    	defaultLanguageLabel = Deutsch
    }

Requirements
-------------

The extension is currently compatible with TYPO3 6.2.

Due to major improvements of the element browser code, it is currently not compatible with TYPO3 7 and higher. This is planned for the near future. Feel free to create pull requests.

Authors
-----
The extension was originally created by Sonja Scholz at CAB Services AG, Basel.

This friendly fork is maintained by visol digitale Dienstleistungen GmbH, Luzern.