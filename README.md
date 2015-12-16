# Gravity Forms Pre-Populate
### Based on [Sean Tierney's](https://github.com/scrollinondubs/Gravity-Forms-Prepopulate) Work
This plugin is a Gravity Forms add-on that allows you to automatically cookie parameters passed in via the querystring and preserve them until a lead form is submitted. It's especially useful if you're tracking UTM Parameters and hoping to store the originating UTM params even if the user signs up on a subsequent page (or even subsequent visit). Cookied params can be passed as hidden fields via Gravity Forms.

Here is a screencast serving as loose documentation showing how to install, configure and use this plugin: http://www.screencast.com/t/KDV0Tato
- Screencast was prior to my edits

## Setup Instructions
* First install the plugin by copying the contents to the wp-content/plugins folder
* Go to Forms > Settings > Prepopulate
* In here, type in your query parameters seperated by commas. I.e; utm_source,utm_campaign,random_query
* Click Update Settings

Once you have set this up, whenever a user gets to your website with any of those query parameters attached to the url, it will be stored as a cookie. Next, you will need to populate fields with the specific parameters:

* Go to the form you would like to edit
* Add an input field. This can be any input type including hidden fields
* Click on the Advanced tab of the input settings
* Check "Allow field to be populated dynamically"
* Input the parameter exactly the way you typed it before. I.e; utm_source
* Click Update Form

## Changelog
### 0.1.1
* Works in MultiSite environment where there is no dropdown for plugin menu
* Removed Active Campaign input to make form more generic
* Moved settings under Gravity Forms instead of Plugins submenu