CONTENTS OF THIS FILE
---------------------
 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers

INTRODUCTION
------------
Drupal 8 menu based breadcrumbs

Created from ground up without knowledge Drupal 7 )))

This module creates breadcrumb from the menu created in admin panel.
Unlike Menu Breadcrumb, this module creates breadcrumb from the menu title
(not from the node title). Therefore, it will create breadcrumb from any
link type as long as there is a menu tree.

REQUIREMENTS
------------
This module requires the Drupal 8.

INSTALLATION
------------
 * To install download via Git
   git clone --branch  8.x-1.x http://git.drupal.org/sandbox/oles89/2367919.git
   cd wb_breadcrumbs


CONFIGURATION
-------------
Create new menu or use default Drupal's menu
After installing the module, navigate to the settings page at
Configuration->System->Breadcrumbs and type your menu name there.
The default menu name is "main".

MAINTAINERS
-----------
Current maintainers:
 * Aleksandrs Poltarjonoks (Codenator81) - https://www.drupal.org/user/2694025
