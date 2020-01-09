Youtube video: https://youtu.be/pQg5Ik3gF04

<html>
  <h2> Admin Order Page</h2>
  <img src="Annotation 2020-01-09 173224.png">
    <h2> Admin Product Page</h2>
  <img src="Annotation 2020-01-09 173225.png">
   <h2> Customer Product Page</h2>
    <img src="Annotation 2020-01-09 173226.png">
      <h2> Customer Basket Page</h2>
  <img src="Annotation 2020-01-09 173227.png">
    <h2> Customer Order Page</h2>
  <img src="Annotation 2020-01-09 173228.png">


  <h2> Application Overview </h2>
  <p>
  My application allows multiple users to place orders with a restaurant, and for Kitchen staff and admin to interact with customers orders and the products they sell.

A customer can place an order by adding items to their basket. Clicking on the button at the bottom of the product card shoes the action that will happen when the customer next clicks the button. Products in the basket are stored in an array in the session, and the customer can change the quantities of items in their basket, or remove them completely, affecting the quantity of the item in the session array.
If a customer hasn’t placed an order before, they can only place their order by clicking the ‘add to new order’ button. A limitation of my application is that a customer can place as many orders as they like, but the orders page only shows the most recent order the customer placed. 
A customer is automatically taken to the orders page. They can amend the order, changing the quantities of products in their order. Changes to quantities are made to items stored in another array in the session. The customer must press the ‘save changes’ button to commit the changes to the database. At this point, the new quantities are ‘requests’. If the quantity of a product is more than the amount they have ordered and the amount left in stock, then their new amount is calculated. For example, if the customer ordered 24 ciders, and submitted a change for 32 ciders, and there are 3 in stock; the maximum they can order is what they have reserved (24) + what is left in stock (3) which equals 27. As they have requested more than what they can order, the new quantity in their order becomes 27 (taking the three from in stock). 
If a customer removes all the items from their order, they still have to save the changes for the order to be cancelled. This removes the order from the Database.
Changes to the order are only committed to the database if the admin has not changed the status of the order to ‘Cooking’.

The admin has 2 pages specifically for them: a page to view orders, and a page to view/modify and add new products. 
The view orders page automatically refreshes every 3 seconds. Orders shown are the orders created that day only. Pending orders are shown in red, and the admin can change the status of the order by clicking the ‘Cooking’ button on an order. When the page refreshes, the order now shows as Cooking, and is green. The order card also shows other useful information about the order, such as the date placed, and the order id. 
The admin can also view the products being sold. Here, the product cards are almost identical to what the customer would see, except the text is now in input areas as placeholders. The admin can change the fields and save the changes. The inputs are sanitized in php. 
The admin can also press a second button to change the status of the product, marking it as withdrawn or selling. Products marked as withdrawn don’t show up in the customers product page, same as products with their quantities set to 0. 
The admin may wish to create a new product. An empty card is created when the page is loaded, which the admin can populate as they wish. If they save the changes, and refresh the page, the new product is shown, and also appears in the customers product page. 

  </p>
</html>


