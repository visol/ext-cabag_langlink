Extension: cabag_langlink
Autor: Sonja Scholz <ss@cabag.ch>
TYPO3 6.2 compatible version: Lorenz Ulrich <lorenz.ulrich@visol.ch>

Funtionality:
=============
The extension adds two xclasses and two hooks to extend the typolink page browser and the rte typolink page browser with flags for every language. So its possible to link to an explicity language.

To get the extension working just install it, save the extension manager default settings and delete the typo3conf cached files.

Configuration:
==============
It's possible to change the used language parameter L to your own language parameter by changing the option in the extension manager configuration of this extension.

If you haven't set a flag for your default language in the backend you will se an "unknown" flag for the link to default language. Only set the following option in the PageTS of the website rootpage:

mod.SHARED {
	defaultLanguageFlag = de.gif
}

It might be, that this option will be changed because, please check the core documentation.