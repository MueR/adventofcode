# Advent of Code

## Usage
```shell
bin/console solve solve [-y|--year YEAR] [-d|--day DAY] [-t|--test]
```

### Optional command line parameters
```text
Option      Default         Description
--test      false           Run your code against test values, rather than your puzzle input.
--year      current year    Run year X.
--day       all days        Run day X, default value is all days.
                            Note: only days that return actual values will be displayed.
```

## Other commands
```shell
# Prepares all classes for the entire edition.
# Default value: current year
bin/console add:year --year YEAR
# Adds the class for day X.
# Year parameter is optional. Default is current year.
bin/console add:day --day DAY [-y|--year YEAR]
```
