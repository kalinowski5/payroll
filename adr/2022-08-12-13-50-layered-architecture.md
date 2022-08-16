## Use Layered Architecture
System will be split into multiple subdomains (modules) - ie. `Payroll`.

Each of them will consist of 4 layers:

* `Domain` (business logic)
* `Infrastructure` (generic technical capabilities that support higher layers)
* `Application` (provides communication with Domain layer)
* `UI` (user interface: controllers, CLI commands, etc.)
