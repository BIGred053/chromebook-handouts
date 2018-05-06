# chromebook-handouts
A PHP/MySQL custom system to facilitate tracking Chromebooks as they were handed out to students fot the 17-18 academic year.

# Credits
I utilized Vagrant with the incredibly helpful Stotch Box pre-made configuration (https://box.scotch.io/) to develop and test this locally, which was a huge help! All vagrant files are set up to work with Scotch Box, so all you need to do is navigate to this project folder in your Terminal/command prompt, run "vagrant destroy" to get rid of my current configuration, and then run "vagrant up" to get the project up and running! Then visit 192.168.33.10 to check out the app in action!

To speed development, I used Bootstrap to develop the front-end of the app. https://getbootstrap.com/

# Outline/Background Info
Developed over about 2 weeks on a simulated Vagrant LAMP stack, then refined as we used this system live to remove bugs, improve usability, and to help improve data accuracy. The front-end of this app utilizes Bootstrap with grid 

As it exists, student appointments and pertinent information were imported manually via CSV into the database. Appointments were scheduled using SignUpGenius and contained very muddled data as it pertained to the structure of the database, so it involed quite a bit of cleaning. To assist with data cleaning, I built a tool into the website to find student information to verify student identities, group students together as families, clarify pickup times, and attach important student and family data from our Student Information System (SIS).

At the scheduled pick-up time, volunteers verified student's identity with an address or student ID (assisted by a column that displayed student details), then entered the Chromebook's serial number and the student's ID to check out each device to a particular student.

If you wish to try out this system, the database structure is provided below to help you set up initial appointment scheduling to provide easily transferrable data.

# Front-End
The front-end is built on Bootstrap 4, making particular use of it's Grid system to make the interface responsive and reduce workload on page layout. The front-end also includes some simple Javascript validation to ensure necessary data is entered.

# Database Structure
