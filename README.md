# SIWECOS.de Website repo

This repo holds all custom files for the siwecos.de website, which is based upon Joomla 3.x.
Currently that includes:
* the main website's template, specifically built for siwecos.de
* 3 content plugins
  * siwecosapp displayes the main, vue-based SPA, developed here https://github.com/SIWECOS/webapp
  * siwecosfreescan displays the vue-based SPA for the freescan on the website's homepage, developed here https://github.com/SIWECOS/webapp-freescan
  * sealoftrust generates a SVG for users to be embedded on their website
* mod_accountbox displays the module with account-specific links in the site's sidebar


## Template
The template is a custom built Joomla 3.x, optimized for super-fast pageloads. Therefore, as few resources as possible are used, a number of core-assets are unset (looking at you, jQuery!) and critical CSS is rendered inline in the head. SCSS is used for the styles, all required packages are found in the package.json in the repo's root, gulp is used to built the files.