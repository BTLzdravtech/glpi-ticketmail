# Ticket Mail

This plugin allows you to send ticket tracking and task information to a person via email from a tab displayed on the ticket's description page.

## Compatibility

| Plugin | GLPI | Branch |
|---|---|---|
| 5.x | 12.0 | [`main`](../../tree/main) |
| 4.x | 11.0 | `glpi-11` |

## Install

```
cd /var/www/glpi/plugins          # or the marketplace directory
git clone https://github.com/BTLzdravtech/glpi-ticketmail.git ticketmail
# GLPI 11: git clone -b glpi-11 https://github.com/BTLzdravtech/glpi-ticketmail.git ticketmail
```

The directory name must be `ticketmail`. Then Setup → Plugins → install and enable *Ticket Mail*.
