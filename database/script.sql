
--mk: 123456 for all users
select * from product_details where product_id=4 and branch_id=2 and color="Hồng Cánh Tím"
select * from shapes ORDER BY product_id

select products.name, branches.name, users.id, users.firstName, users.lastName, color
from carts, cart_details, products, branches, users
where cart_details.cart_id = carts.id
and cart_details.product_id = products.id
and cart_details.branch_id = branches.id
and carts.user_id = users.id
ORDER BY users.id

SELECT DISTINCT users.id AS userid,
       COUNT(DISTINCT CONCAT(cart_details.product_id, '-', cart_details.branch_id, '-', cart_details.color)) AS detailcart_count
FROM users
INNER JOIN carts ON carts.user_id = users.id
INNER JOIN cart_details ON cart_details.cart_id = carts.id
GROUP BY users.id
ORDER BY users.id;


SELECT
    orders.id AS order_id,
    orders.coupon_code,
    orders.total_price AS order_total_price,
    SUM(order_details.total_price) AS total_order_details_price
FROM
    orders
JOIN
    order_details ON orders.id = order_details.order_id
WHERE
    orders.branch_id = 1
GROUP BY
    orders.id, orders.coupon_code, orders.total_price;


desc features
SELECT * FROM information_schema.columns WHERE table_schema = 'lensart_eyewear';




