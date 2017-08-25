We welcome contributions to this code. It's probably a good idea to start a
discussion around new features or bugs in [GitHub issues](https://github.com/NUKnightLab/soundcite/issues) before investing too
much time, just to make sure that your approach is in line with the team's
intentions for the project.

To keep the code tidy, we use PHP code linting formatting tools. (details could use improvement)

You need to install PHP_CodeSniffer. (You may also need to install PECL and PEAR for installing PHP libraries and tools. If you do, [This Stack Overflow post](sudo php /usr/lib/php/install-pear-nozlib.phar -d /usr/local/lib/php -b /usr/local/bin) may be helpful for macOS users.)

(Depending on details, you may need to create `/etc/php.ini` or edit it to add
```include_path=".:/usr/local/lib/php" ```

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

Then later, when you want to make sure your code is sticking with the plan, use these two commands to check it and fix it. Remember you must specify the target directory for each command.
