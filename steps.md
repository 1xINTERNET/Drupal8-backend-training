### 3.1 Using Drupal services

We will use Movie API to show movies on our landingpage. 

Observations:
 - The Drupal global service (dependency injection)
 - \Drupal::httpClient, \Drupal::logger
 - Usage of inline tags
 
The following implementations works. It is however not aligned with clean coding.

Improvements needed are:
 - Using dependency injection over the global Drupal wherever possible
 - Having a module as dynamic as possible. E.g. we could use a form to set the URL.
 - We need to make sure developers/themers can always override our output.
 - Remove usage of html in code.