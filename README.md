# What is this?

GrainesE3 is a student organization at ENSE3 - Grenoble INP. One of their missions is to supply students woth easy-access and cheap
biological products. Right now the organization distribute bio fruit in a stand that is open when volunteers are available. Our group 
worked on automating this process by creating a fruit dispensing machine.

Because this is a low budget and student project, we did not use "traditional" banking services, which charge per transaction and require
costly terminals. Instead we used woocommerce and an external database (which relies on woocommerce's native database) that stores all of
the users balances.

In this repository you will find the PHP scripts that allow us to update our external database up to date and to remove balance when a user
buys a piece of fruit, the instructions for setting up your website and the arduino code (and pinout) that allows the fruit machine to 
work smoothly.

This project contains the code necessary for the beck-end of a fruit vending machine. It relates to the arduino electronics 
and to the website responsible for the payments to charge costumer wallets.

You can see how we constructed the mechanical part of the fruit machine in the following image:
![This is a CAD prototype of the machine](https://github.com/UniversalOverlord/FruVendMach/Documentation/Machine-Prototype.png)

Project for the GrainesE3 student organization! Come and visit us at [our website](https://e3fruits.space "our website")!
