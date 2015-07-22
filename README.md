<h3>Drupal 8 menu based breadcrumbs</h3>

Created from ground up  without knowledge Drupal 7 )))
This module different from https://www.drupal.org/project/menu_breadcrumb 
because this module can create breadcrumbs from menu title not from node title so wb_breadcrumbs create breadcrumbs from any link type. All what module need created menu tree.

<h3>Features</h3>
Configurable from UX:
<ul>
<li>Breadcrumb separator</li>
<li>Base class for nav tag</li>
<li>Menu name from whom Breadcrumb will be created</li>
</ul>

<h3>How to use</h3>
<ol>
  <li>Create new menu or use default Drupal's menu</li>
<li>After module installation navigate to Configuration->System->Breadcrumbs configuration and type your menu name there
Default name there is "main".
</li>
<li>Profit ))</li>
</ol>
In theme you can override added "wb_breadcrumb.html.twig" template and copy from module.
<h3>To download</h3>
<code>
git clone --branch  8.x-1.x http://git.drupal.org/sandbox/oles89/2367919.git
cd wb_breadcrumbs
</code>

<h3>Maintainer</h3>
<p>
Codenator81   codenator81@gmail.com
</p>
