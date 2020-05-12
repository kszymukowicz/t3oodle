#!/bin/bash

ddev composer config repositories.t3oodle path ../t3oodle
ddev composer req t3/t3oodle:'*@dev' --no-progress --no-suggest -n

ddev exec vendor/bin/typo3cms install:setup -n
ddev exec vendor/bin/typo3cms configuration:set BE/debug 1
ddev exec vendor/bin/typo3cms configuration:set FE/debug 1
ddev exec vendor/bin/typo3cms configuration:set SYS/devIPmask '\*'
ddev exec vendor/bin/typo3cms configuration:set SYS/displayErrors 1
ddev exec vendor/bin/typo3cms configuration:set SYS/systemLogLevel 0
ddev exec vendor/bin/typo3cms configuration:set SYS/trustedHostsPattern '.\*.\*'
ddev exec vendor/bin/typo3cms configuration:set MAIL/transport smtp
ddev exec vendor/bin/typo3cms configuration:set MAIL/transport_smtp_server localhost:1025
ddev exec vendor/bin/typo3cms configuration:set GFX/processor ImageMagick
ddev exec vendor/bin/typo3cms configuration:set GFX/processor_path /usr/bin/
ddev exec vendor/bin/typo3cms configuration:set GFX/processor_path_lzw /usr/bin/
ddev exec vendor/bin/typo3cms install:generatepackagestates
ddev exec vendor/bin/typo3cms cache:flush
