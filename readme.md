# HLS - Store Operating Times

## Task Outline

This task is focused around finding opening times for a shop.

You’ll find two configuration files in the attached zip folder:

- holidays.config.php : Lists national holidays observed by the shop

- times.config.php: Lists opening hours for the shop.

You’ll also find shop.php which contains the core methods you must utilise in the scripts you produce. You can augment this file as you see fit but you should not modify the core methods beyond adding method bodies (i.e. do not alter method signatures).  You may create any supporting application files as you please.


### Task Requirements

Using the two configuration files and shop.php complete the following tasks.

1. List the shop’s opening times for any day of the week.
    - You should include the full range of opening/closing times (e.g. open, closed for lunch, back from lunch, closed for the day) for a given day.

2. Lists all public holidays in which the shop will be closed for the whole day. 
    - This might be a single day or an uninterrupted date range. 

3. When queried with a date time, the program should be able to tell us:
    - Whether the shop is open or closed
    - When the shop will be closed next
    - When the shop will be open next

---