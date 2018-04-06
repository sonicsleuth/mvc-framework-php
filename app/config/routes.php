<?php
/*
 * URL Routing
 * Typically there is a one-to-one relationship between a URL string and its corresponding controller class/method.
 * The segments in a URI normally follow this pattern:
 * example.com/class/function/id/
 *
 * In some instances, however, you may want to remap this relationship so that a different class/method can be
 * called instead of the one corresponding to the URL. For example, let’s say you want your URLs to have this prototype:
 * example.com/product/1/
 * example.com/product/2/
 * example.com/product/3/
 * example.com/product/4/
 *
 * Normally the second segment of the URL is reserved for the method name, but in the example above it instead
 * has a product ID. To overcome routes allow you to remap the URI handler.
 *
 * WILDCARDS
 * A typical wildcard route might look something like this:
 * $route['product/:num'] = 'catalog/product_lookup/123';
 *
 * In a route, the array key contains the URI to be matched, while the array value contains the destination it should
 * be re-routed to. In the above example, if the literal word “product” is found in the first segment of the URL, and a
 * number is found in the second segment, the “catalog” class and the “product_lookup” method are instead used.
 *
 * You can match literal values or you can use two wildcard types:
 * (:num) will match a segment containing only numbers. (:any) will match a segment containing any character
 * (except for ‘/’, which is the segment delimiter).
 *
 * REGULAR EXPRESSIONS
 * If you prefer you can use regular expressions to define your routing rules. Any valid regular expression is allowed,
 * as are back-references. If you use back-references you must use the dollar syntax rather than the double backslash syntax.
 *
 * A typical RegEx route might look something like this:
 * $route['products/([a-z]+)/(\d+)'] = '$1/id_$2';
 * In the above example, a URI similar to products/shirts/123 would instead call the “shirts” controller class
 * and the “id_123” method.
 *
 * NOTE:
 * 1) Routes will run in the order they are defined. Higher routes will always take precedence over lower ones.
 * 2) Route rules are not filters! Setting a rule of e.g. ‘foo/bar/(:num)’ will not prevent controller Foo and method bar
 * to be called with a non-numeric value if that is a valid route.
 *
 * IMPORTANT!
 * Do not use leading/trailing slashes.
 *
 * EXAMPLES:
 * $route['journals'] = 'blogs';
 * A URL containing the word “journals” in the first segment will be remapped to the “blogs” controller.
 *
 * $route['product/(:any)'] = 'catalog/product_lookup';
 * A URL with “product” as the first segment, and anything in the second will be remapped to the “catalog” class
 * and the “product_lookup” method.
 *
 * $route['product/(:num)'] = 'catalog/product_lookup_by_id/$1';
 * A URL with “product” as the first segment, and a number in the second will be remapped to the “catalog” class and
 * the “product_lookup_by_id” method passing in the match as a variable to the method.
 *
 */
$route['default'] = 'home';

$route['user/([a-z]+)/([a-z]+)'] = 'home/user/$1/$2';
$route['page/([a-z]+)/([a-z]+)'] = 'admin/my-admin/page/$1/$2';
$route['anything/:any/:any'] = 'home/index/$1/$2';
$route['number/:num/:num'] = 'home/index/$1/$2';

