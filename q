[0;1;32m●[0m cron.service - Regular background program processing daemon
   Loaded: loaded (/lib/systemd/system/cron.service; enabled; vendor preset: enabled)
   Active: [0;1;32mactive (running)[0m since Tue 2020-09-08 06:58:37 UTC; 22min ago
     Docs: man:cron(8)
 Main PID: 950 (cron)
    Tasks: 1 (limit: 4659)
   CGroup: /system.slice/cron.service
           └─950 /usr/sbin/cron -f

Sep 08 07:05:01 homestead CRON[2948]: (root) CMD (command -v debian-sa1 > /dev/null && debian-sa1 1 1)
Sep 08 07:05:01 homestead CRON[2944]: pam_unix(cron:session): session closed for user root
Sep 08 07:09:01 homestead CRON[2979]: pam_unix(cron:session): session opened for user root by (uid=0)
Sep 08 07:09:01 homestead CRON[2983]: (root) CMD (  [ -x /usr/lib/php/sessionclean ] && if [ ! -d /run/systemd/system ]; then /usr/lib/php/sessionclean; fi)
Sep 08 07:09:01 homestead CRON[2979]: pam_unix(cron:session): session closed for user root
Sep 08 07:15:01 homestead CRON[3419]: pam_unix(cron:session): session opened for user root by (uid=0)
Sep 08 07:15:01 homestead CRON[3419]: pam_unix(cron:session): session closed for user root
Sep 08 07:17:01 homestead CRON[3434]: pam_unix(cron:session): session opened for user root by (uid=0)
Sep 08 07:17:01 homestead CRON[3435]: (root) CMD (   cd / && run-parts --report /etc/cron.hourly)
Sep 08 07:17:01 homestead CRON[3434]: pam_unix(cron:session): session closed for user root
