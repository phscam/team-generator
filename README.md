# team-generator [v1.0.1]
Command Line Application to generate random teams.

## Requirements
- PHP ^7.4
- composer ^2 (most likely to work with composer ^1 too)

## Production
### Setup
- run ```composer install --no-dev```

### Available commands
- run ```php TeamGenerator.php``` to start the application

## Development
### Setup
- run ```composer install```

### Available Commands
- run ```php TeamGenerator.php``` to start the application
- run ```./vendor/bin/phpunit tests``` to execute the unit tests (only after development setup)
- run ```php ./distribution-plot/GeneratorDistributionPlot.php >> ./distribution-plot/graph.png``` to generate a distribution graph (edit the const parameters in ```GeneratorDistributionPlot.php``` to fix a certain test case) - be aware that the graph generation can take quite a time


[v1.0.1]: https://github.com/phscam/team-generator/releases/tag/v1.0.1