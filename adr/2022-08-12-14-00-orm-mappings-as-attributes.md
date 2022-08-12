## Use ORM mappings as attributes 

For sake of simplicity and readability of entities, I decided to store ORM mappings as attributes (in Domain layer).

With more puristic approach, mappings should be part of Infrastructure layer, instead of Domain. 
For example: `Tidio/Payroll/Infrastructure/ORM/Mapping/Employee.xml`

### Useful resources
* https://bernardng.hashnode.dev/ddd-with-symfony-how-to-configure-doctrine-xml-mapping
