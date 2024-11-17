
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

SELECT coupons.discount_price, orders.total_price, order_details.total_price from coupons, orders, order_details
where orders.id = order_details.order_id
and coupons.id = orders.coupon_id
and orders.id = 10

SELECT * from cart_details

select * from users


SELECT users.id as uid, firstName, lastName, address,  phone
from users,


desc features
SELECT * FROM information_schema.columns WHERE table_schema = 'lensart_eyewear';




