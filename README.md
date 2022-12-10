# Advent of Code

If you clone or fork my repository, be a fair player and remove all puzzle solutions.
A quick method to get you started:

```shell
YEAR=2021
rm -rf "src/AdventOfCode$YEAR/Day*/*"
bin/console add:year -y $YEAR
```

## Recommended PECL packages
Install the [Data Structures](https://www.php.net/manual/en/book.ds.php) package from PECL.
These provide a massive performance increase over the composer package `php-ds/php-ds`.
```shell
# For Debian/Ubuntu:
sudo apt install php-dev php-pear build-essential
sudo pecl install ds
```

## Usage
```shell
bin/console [solve] [-y|--year YEAR] [-d|--day DAY] [-t|--test]
```

### Optional command line parameters
```text
Option      Default         Description
-t,--test   false           Run your code against test values, rather than your puzzle input.
-y,--year   current year    Run year X.
-d,--day    all days        Run day X, default value is all days.
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
