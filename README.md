# Payroll app

### System requirements
* PHP 8.1
* Composer

### Setup instructions

Just run the following command in your favorite CLI: `make init`

SQLite database will be automatically created and filled with sample data. 

### Demo

In order to generate payroll, run:
`bin/console xyz:payroll:generate`

For filtered results, use the following option:
`--filter=needle`

### Automated tests
Unit, integration and application tests are available:
`make test`

## Possible improvements
Things that could be improved if there is more time:
* Separate Doctrine mappings from entity classes (ie. place XMLs in `Infrastructure` namespace instead of attributes in `Domain`)
* Dockerize application
* Optimize performance for larger data sets, e.g. thousands of employees (change the way how data is filtered and sorted)
* Split filtering into multiple columns 
* Implement more value objects and add more sophisticated validation rules
* Add cs-fixer
* Add behat tests
* Add mutation tests
* Implement shared kernel
