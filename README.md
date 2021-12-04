# Advent of Code

If you clone or fork my repository, be a fair player and remove all puzzle solutions.
A quick method to get you started:

```shell
YEAR=2021
rm -rf "src/AdventOfCode$YEAR/Day*/*"
bin/console add:year -y $YEAR
```

## Usage
```shell
bin/console [solve] [-y|--year YEAR] [-d|--day DAY] [-t|--test]
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
Prepares all classes for the entire edition.
Default value: current year
```shell
bin/console add:year --year YEAR
```
Adds the class for day X.
Year parameter is optional. Default is current year.
```shell
bin/console add:day --day DAY [-y|--year YEAR]
```
