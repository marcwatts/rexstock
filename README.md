# Retail Express Helper Plugin for Magento 2

## What does it do?

This plugin is a helper plugin meant to be used with a Retail Express Magento sync.

## Installation

<ol>
	<li> Download the plugin files - Available as a .zip or tar.gz file from the GitHub directory. </li>
	<li> Unzip the file </li>
	<li> Create directory Marcwatts/Rexstock in: <br/> <em>[MAGENTO_INSTAL_DIR]/app/code/</em></li>
	<li> Copy the extracted files to this folder folder </li>
	<li> Open Command Line Interface / Shell </li>
	<li> In CLI, run the below command to enable the module: <br/> <em>php bin/magento module:enable Marcwatts_Rexstock</em> </li>
	<li> In CLI, run the Magento setup upgrade: <br/> <em>php bin/magento setup:upgrade</em> </li>
	<li> In CLI, run the Magento Dependencies Injection Compile: <br/> <em>php bin/magento setup:di:compile</em> </li>
	<li> In CLI, run the Magento Static Content deployment: <br/> <em>php bin/magento setup:static-content:deploy</em> </li>
	<li> Login to Magento Admin and navigate to System/Cache Management </li>
	<li> Flush the cache storage by selecting Flush Cache Storage </li>
</ol>
