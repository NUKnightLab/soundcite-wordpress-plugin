We welcome contributions to this code. It's probably a good idea to start a discussion around new features or bugs in [GitHub issues](https://github.com/NUKnightLab/soundcite/issues) before investing too much time, just to make sure that your approach is in line with the team's intentions for the project.

To keep the code tidy, we use PHP code linting tools.

### Install PEAR

If you haven't already done so, install PECL and PEAR for installing PHP libraries and tools. [This Stack Overflow post](https://stackoverflow.com/questions/32893056/installing-pecl-and-pear-on-os-x-10-11-el-capitan-or-macos-10-12-sierra) contains instructions for macOS users.)
```sudo php /usr/lib/php/install-pear-nozlib.phar -d /usr/local/lib/php -b /usr/local/bin```

### Set your PHP `include_path`

If `php.ini` doesn't exist, create it:

    sudo cp /etc/php.ini.default /etc/php.ini

Edit `php.ini` so `include_path` contains PEAR's path:

    include_path=".:/usr/local/lib/php"

### Install PHP CodeSniffer

    sudo pear install PHP_CodeSniffer

### Install coding standards

Clone the [WordPress-Coding-Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/) git repository somewhere on your computer.

Then run these two commands, editing the first as appropriate:

    sudo phpcs --config-set installed_paths /PATH/TO/CHECKEDOUT/REPOSITORY/WordPress-Coding-Standards
    sudo phpcs --config-set colors true

Then, to make it easy to run the cleanup tools consistently with how our development team runs them, create these `aliases` in your .bash_rc or similar setup script.

```
alias vipcs='phpcs -p -s -v --colors --standard=WordPress-VIP'
alias vipcbf='phpcbf --standard=WordPress-VIP'
```

Before you commit code, use these two commands to check it and fix it. Remember you must specify the target file or directory for each command.
