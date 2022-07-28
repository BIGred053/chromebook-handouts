# chromebook-handouts
A "bubble gum and duct tape" implementation of a PHP/MySQL custom system to facilitate tracking Chromebooks as they were handed out to students fot the 2017-2018 academic year.

## Outline/Background Info
This system was developed originally to meet the challenge of accurately tracking the assingment of district-owned Chromebooks to each student in grades 7-12 (roughly 1200 devices). In the absence of another system, my boss asked me to attempt to generate a system to meet this need. Project requirements included:

* Intuitive UX - this application was used by volunteers with varying technical expertise, and data accuracy was important
* Quick delivery - (Based on memory as of 7/2022) This project was assigned to me on a Thursday, with the first student pick-ups scheduled the following Tuesday
  * Due to this short time window, I worked on this for a significant amount of time at home on MacOS devices, in addition to work on Windows devices. To ensure a consistent development experience, I was challenged to learn to develop this within a virtualized environment using Vagrant, which was a brand-new technology to me at the time.
* Accurate and Reliable - this system was core to accurately associating a student with a device, which had implications for dealing with lost and broken devices, collection of devices at the end of the year, and more
* A system for confirming student identity - Due to the importance of data accuracy and the potential for students with similar names, we needed to be able to verify each student to whom we were assigning a device.
  * This requirement involved matching manually-entered data from parents, which often contained nicknames (e.g. Jack for Johnathan, Beth for Elizabeth), to standardized data from the district's Student Information System.
    * This ultimately necessitated the creation of an additional tool to perform fuzzy matching and facilitate a process for manually associating sign-up records with SIS records


While not an exemplar for code quality, as this was a system that I built prior to officially launching my full-time career in software development, I believe that this project showcases a good variety of my mindset around tackling challenging problems, learning new technologies quickly, working to a deadline, and dealing with unexpected challenges.

In all, this was a challenging project that went beyond any software product that I had previously built, and it was very rewarding to see it succeed. At the end of the day, I credit this project with re-lighting the spark of passion for software development that ultimately led me to working full time as a software engineer.

## Using this application

As it exists, student appointments and pertinent information were imported manually via CSV into the database. Appointments were scheduled using SignUpGenius and contained data that aligned poorly with structure of the database, so it involed quite a bit of cleaning. To assist with data cleaning, I built a tool into the website to find student information to verify student identities, group students together as families, clarify pickup times, and attach important student and family data from our Student Information System (SIS).

At the scheduled pick-up time, volunteers verified student's identity with an address or student ID (assisted by a column that displayed student details), then entered the Chromebook's serial number and the student's ID to check out each device to a particular student.

If you wish to try out this system, the database structure is provided below to help you set up initial appointment scheduling to provide easily transferrable data.

## Note
To protect district and student privacy, and to comply with FERPA regulations, I had to omit and remove all databased contents and any information within the app that referenced sensitive district information. As such, the app currently lacks the data to successfully demonstrate full functionality.

## Credits
I utilized Vagrant with the incredibly helpful Stotch Box pre-made configuration (https://box.scotch.io/) to develop and test this locally. All vagrant files are set up to work with Scotch Box, so all you need to do is navigate to this project folder in your Terminal/command prompt, run "vagrant destroy" to get rid of my current configuration, and then run "vagrant up" to get the project up and running! Then visit 192.168.33.10 to check out the app in action!

To speed development, I used Bootstrap to develop the front-end of the app. https://getbootstrap.com/

## Front-End
The front-end of this app is built on Bootstrap 4, making particular use of it's Grid system to make the interface responsive and reduce workload on page layout. The front-end also includes some simple Javascript validation to ensure necessary data is entered.
