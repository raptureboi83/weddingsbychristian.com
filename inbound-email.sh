#!/bin/bash
LOG=/tmp/wbc-inbound-email.log

echo "[$(date)] Starting" >> $LOG

if [ -x /opt/cpanel/ea-php83/root/usr/bin/php ]; then
    PHP=/opt/cpanel/ea-php83/root/usr/bin/php
else
    PHP=$(which php 2>/dev/null)
fi

echo "[$(date)] Using PHP: $PHP" >> $LOG

$PHP /home/weddi133/weddingsbychristian.com/artisan inbox:receive 2>> $LOG

echo "[$(date)] Exit code: $?" >> $LOG