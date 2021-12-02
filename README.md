# Advent of Code

## Usage
```shell
bin/console solve solve [-y|--year YEAR] [-d|--day DAY]
```

### Optional command line parameters
```text
Option      Default         Description
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
