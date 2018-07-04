# Lab-Bot
---
A lab assistance system which will be deployed in University of Nottingham CS building A32.

# Introduction
---
The idea behind ‘Lab Bot’ is to provide a system by which students can request support easily and discreetly from their computer, and that directs staff to the relevant students. **The system is still under developing. We aim to put the system in A32 next term.**

# Current Functions and Usages
---
Only the web service is currently available (locally).

### For Students
1. Using local account to log in the system.
2. Make a request. (Requests can only be made when there is a lab in A32 and the student is enrolled in the corresponding module)
3. Can see how many waiting requests are in the queue.
4. Can see the position of my request.
5. Can see the request history.
6. Cancel the request.

### For Assistants
1. Using local account to log in the system.
2. When there is a waiting request available, you can choose to help when get ready. (Assistants can only handle requests from the modules they enrolled in)
3. After deciding to help, you can see which student to help.
4. Finish the request.
5. Suspend the request.
6. Can see the requests you have handled before.
7. Can see all the suspended requests and choose one to help.

# Future Development
---
We will continue developing the web service and try to build software for A32 lab PC in the future.

# How to Set up
---
Because all the files are running locally, you should install and run the MySQL system in your computer before testing.

1. Import 'Lab-bot.qsl' file into your MySQL, which includes all the databases and some testing data.
2. Make sure credentials are correct in 'credentials.php', matching your own database.
3. 'studentLogin.php' is for student users.
4. 'adminLogin.php' is for assistant users.

# Authors
---
- Buzhen Ye
- Steven Bagley (Supervisor)

# Contributing
---
If you want to understand the code, develop or contribute, please inform [Buzhen Ye]( mailto:psyby3@nottingham.ac.uk).