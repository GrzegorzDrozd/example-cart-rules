# Example project: cart rules

Code is separated into 4 main parts:
1. Rules - when things should happen
2. Actions - what should happen
3. Service - Service wrapping everything around 
4. Boiler plate code - cart and product abstract representation 
                                                               
### Rules

Code in the rules is checking IF either product OR cart as a whole matches given parameters. All rules are extending `BaseRule` class and should implement one method: `validate`.
This method gets `Cart` object instance. It should return array of `Product` objects that will have `Action` applied.  
                                               
### Actions

When we have a list of `Products` matching or we know that `Cart` as whole matches we can now apply actions. Actions are changing `finalPrice` of a product or properties of a `Cart`. Using `finalPrice` only allows UI to show `price` vs `finalPrice`. 

### Service

Service code orchestrates everyting. It can be used as a dependency or exposed as a microservice.

### Boilerplate code

Boilerplate code is used mostly for this demo. It mainly consists of representation of `Product` and a `Cart`. 


## Installation
Run composer install --dev

## Usage
All demos are in `demos` directory.

| File name | Description |
| --- | :--- |
| demo_from_readme.php | Demo showing cart configurations from task specification |
| demo_storage.php | Shows how to store rules in persistent storage like a database |
| demo_get_1_and_then_next_is_50_off.php | Demo showing n-th product logic | 
| demo_expressions.php | Shows advanced usage with Symfony\ExpressionLanguage | 
| demo_products_in_categories.php | Shows rules related to categories |


