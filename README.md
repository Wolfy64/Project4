# Develop a back end for a client
## Context

The Louvre museum has commissioned you for an ambitious project: to create a new system for booking and managing tickets online to reduce long queues and take advantage of the growing use of smartphones.

**Specifications**

The interface must be accessible on desktop as well as tablets and smartphones, and use a responsive design.

The interface must be functional, clear and fast above all else. The customer does not want to overload the website information useful: the goal is to allow visitors to buy a ticket quickly.

There are 2 types of tickets: the "Day" ticket and the "Half-day" ticket (it can only be returned from 14:00). The museum is open every day except Tuesdays (and closed on May 1st, November 1st and December 25th).

The museum offers several types of rates:

A "**normal**" rate from 12 years old to 16 €
A "**child**" rate from 4 years old and up to 12 years old, at 8 € (admission is free for children under 4 years old)
A "**senior**" rate from 60 years old for 12 €
A "**reduced**" rate of € 10 granted under certain conditions (student, museum employee, Ministry of Culture service, military ...)
To order, we must select:

The day of the visit
The type of ticket *(day, half day ...)*. You can order a ticket for the same day but you can no longer order a "Day" ticket after 14:00.
The number of tickets desired
The client specifies that it is not possible to book for past days (!), Sundays, holidays and days when more than 1000 tickets have been sold in all not to exceed the capacity of the museum.

For each ticket, the user must indicate his name, first name, country and date of birth. It will determine the fare of the ticket.

If the person has the discounted rate, they must simply check the "Reduced rate" box. The site must indicate that it will be necessary to present his student card, military or equivalent at the entrance to prove that we benefit from the reduced rate.

The site will also retrieve the e-mail of the visitor to send the tickets. It will not require creating an account to order.

The visitor must be able to pay with the Stripe solution by credit card.

The site must manage the return of the payment. In case of error, it prompts to restart the operation. If everything went well, the order is recorded and the tickets are sent to the visitor.

You will use the test environments provided by Stripe to simulate the transaction, so you do not have to enter your own credit card.

**The ticket**

A confirmation email will be sent to the user and will act as a ticket.

The email must indicate:

 - The name and logo of the museum 
 - The date of the reservation
 - The price
 - The name of each visitor
 - The code of the reservation *(a set of letters and numbers)*
 
### Skills to validate
- Take charge of the Symfony framework
- Create a website, from conception to delivery
- Develop the different elements of a project
