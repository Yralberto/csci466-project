CREATE DATABASE IF NOT EXISTS CandyStore;
USE CandyStore;


CREATE TABLE USER (
    USER_ID INT PRIMARY KEY,
    USERNAME VARCHAR(50),
    PASSWORD VARCHAR(50),
    ROLE VARCHAR(20)
);

CREATE TABLE PRODUCT (
    PRODUCT_ID INT PRIMARY KEY,
    NAME VARCHAR(200),
    PRICE DECIMAL(10, 2),
    STOCK_QUANTITY INT,
    DESCRIPTION TEXT
);

CREATE TABLE ORDERS (
    ORDER_ID INT PRIMARY KEY,
    USER_ID INT,
    ORDER_DATE DATE,
    STATUS VARCHAR(50),
    TOTAL_AMOUNT DECIMAL(10, 2),
    SHIPPING_ADDRESS TEXT,
    FOREIGN KEY (USER_ID) REFERENCES USER(USER_ID)
);

CREATE TABLE ORDER_DETAILS (
    ORDER_ID INT,
    PRODUCT_ID INT,
    QUANTITY INT,
    PURCHASE_PRICE DECIMAL(10,2),
    PRIMARY KEY (ORDER_ID, PRODUCT_ID),
    FOREIGN KEY (ORDER_ID) REFERENCES ORDERS(ORDER_ID),
    FOREIGN KEY (PRODUCT_ID) REFERENCES PRODUCT(PRODUCT_ID)
);

INSERT INTO USER (USER_ID, USERNAME, PASSWORD, ROLE) VALUES
(1, 'bob', 'pass123', 'customer'),
(2, 'angieflower', 'an000', 'customer'),
(3, 'sugarcube93', 'moon29', 'customer'),
(4, 'purpledaisy324', 'redisthebest123', 'customer'),
(5, 'TimTim', 'Tm1234', 'customer'),
(6, 'Admin', 'Password4', 'Admin');


INSERT INTO PRODUCT (PRODUCT_ID, NAME, PRICE, STOCK_QUANTITY, DESCRIPTION) VALUES
(1, 'Pop Rocks', 0.75, 200, 'Popping candy that crackles and bursts in your mouth'),
(2, 'Skittles Pack', 1.00, 50, 'Fruit flavored colorful chewy candy'),
(3, 'Chocolate Milk', 2.50, 110, 'Classic milk chocolate flavored drink'),
(4, 'Peanut Butter Cups', .50, 50, 'Chocolate cups filled with peanut butter'),
(5, 'Flamin'' Hot Cheetos', 1.00, 100, 'Crispy corn chips coated with spicy hot seasoning'),
(6, 'Sour Patch Kids', 1.25, 50, 'Sweet and sour gummy candy'),
(7, 'Bubblegum Pack', 1.00, 36, 'Assorted flavored bubblegum pieces'),
(8, 'Jawbreakers', 3.00, 7, 'Hard layered candy spheres'),
(9, 'Jelly Beans', 1.00, 10, 'Assorted flavored jelly beans'),
(10, 'Candy Canes', 0.75, 20, 'Peppermint candy canes'),
(11, 'Gummy Worms', 1.00, 57, 'Fruit flavored gummy worms'),
(12, 'Lollipops', 0.25, 500, 'Assorted fruit flavored lollipops'),
(13, 'Almond Chocolate Bar', 1.00, 74, 'Milk chocolate with almonds'),
(14, 'Dark Chocolate Bar', 1.00, 80, '95% cocoa dark chocolate'),
(15, 'Milk Chocolate Bar', 1.00, 200, 'Classic milk chocolate bar'),
(16, 'Gummy Bears', 1.00, 89, 'Fruit flavored gummy bears'),
(17, 'Fruit Snacks Pack', 0.50, 50, 'Assorted fruit flavored snacks'),
(18, 'Blueberry Muffin', 1.00, 110, 'Individually packed blueberry muffin'),
(19, 'Ice Cream Cup', 2.25, 20, 'Strawberry, vanilla, and chocolate flavors'),
(20, 'Protein Bar', 2.00, 10, 'High protein bar (tastes bad but works)');

INSERT INTO ORDERS (ORDER_ID, USER_ID, ORDER_DATE, STATUS, TOTAL_AMOUNT, SHIPPING_ADDRESS) VALUES
(1, 1, '2026-04-20', 'Processing', 22.50, '123 Main St'),
(2, 2, '2026-04-20', 'Processing', 7.50, '55th Oak Pl'),
(3, 4, '2026-04-21', 'Processing', 9.00, '456 S Maplewood St'),
(4, 4, '2026-04-23', 'Processing', 14.00, '1001 N Purple Lane'),
(5, 3, '2026-04-23', 'Processing', 5.50, '879 W Peanut Dr'),
(6, 5, '2026-04-25', 'Processing', 14.00, '1001 N Purple Lane'),
(7, 5, '2026-04-25', 'Processing', 20.00, '879 W Peanut Dr 2nd floor');

INSERT INTO ORDER_DETAILS (ORDER_ID, PRODUCT_ID, QUANTITY, PURCHASE_PRICE) VALUES
-- Make sure the numbers match on the website. did the math already so there shouldnt be any confuscion and just need to check


-- Format:
-- (order id, product id, quantity, and purchase Price)

-- numbers are pre-tax
-- |
-- V
-- ORDER 1 (total = 22.50)
(1,3,3,2.50), -- Chocolate Milk: 3 x 2.50 = 7.50
(1,8,5,3.00), -- Jawbreackers: 5 x 3.00 = 15.00

-- ORDER 2 (total = 7.50)
(2, 5, 3, 1.00), -- Flamin Hot Cheetos: 3 x 1.00 = 3.00
(2, 6, 2, 1.25), -- Sour Patch Kids: 2 x 1.25 = 2.50
(2, 1, 2, 0.75), -- Pop Rocks: 2 x 0.75 = 1.50 

-- ORDER 3 (total = 9.00)
(3, 8, 3, 3.00), -- Jawbreakers: 3 x 3.00 = 9.00

-- ORDER 4 (total = 14.00)
(4, 19, 4, 2.25), -- Ice Cream Cup: 4 x 2.25 = 9.00
(4, 10, 4, 0.75), -- Candy Canes: 4 x 0.75 = 3.00
(4, 12, 8, 0.25), -- Lollipops: 8 x 0.25 = 2.00

-- ORDER 5 (total = 5.50)
(5, 6, 2, 1.25), -- Sour Patch Kids: 2 x 1.25 = 2.50
(5, 5, 3, 1.00), --Flamin Hot Cheetos: 3 x 1.00 = 3.00

-- ORDER 6 (total = 14.00)
(6, 19, 4, 2.25), -- Ice Cream Cups: 4 x 2.25 = 9.00
(6, 10, 4, 0.75), -- Candy Canes: 4 x 0.75 = 3.00
(6, 12, 8, 0.25), -- Lollipops: 8 x 0.25 = 2.00

-- ORDER 7 (total = 20.00)
(7, 3, 4, 2.50), -- Chocolate Milk: 4 x 2.50 = 10.00
(7, 8, 3, 3.00), -- Jawbreakers: 3 x 3.00 = 9.00
(7, 17, 2, 0.50);  -- Fruit Snacks Pack: 2 x .50 = 1.00