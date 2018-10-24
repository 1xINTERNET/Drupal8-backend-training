### 9 Creating a block

`drupal generate:plugin:block`

![](step-8.1.png)
![](step-8.2.png)

Observations:
 - A new block plugin file under Plugin/Block/MovieListBlock.php
 - You can create an instance in the block layout and choose a movie name

As final points we:
 - Abstract the templates a create a movie-list.html.twig and theme hook implementation
 - Inject the movie service in to the newly created block
 - Adjust the LandingpageController to use the correct theme implementation

![](step-9.1.png)