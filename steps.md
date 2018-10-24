### 2.1 Generate a custom page with the drupal console

`drupal generate:controller`

![Module generation](step-2.1.png)

Let us now go to /landingpage

Observations:
 - A new /src folder structure
 - PSR4 and namespacing
 - A new landingpage.routing.yml file
 - Route mapping of a route url to a controller
 - Caching difference for authenticated/anonymous users