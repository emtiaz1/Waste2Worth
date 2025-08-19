INSERT INTO products (name, image, stock, eco_coin_value, description, created_at, updated_at) VALUES
('Eco-Friendly Water Bottle', 'frontend/image/products/water-bottle.jpg', 50, 100, 'Reusable stainless steel water bottle, perfect for daily use', NOW(), NOW()),
('Bamboo Utensil Set', 'frontend/image/products/bamboo-utensils.jpg', 30, 75, 'Sustainable bamboo cutlery set with carrying case', NOW(), NOW()),
('Recycled Notebook', 'frontend/image/products/notebook.jpg', 100, 50, 'Notebook made from 100% recycled paper', NOW(), NOW()),
('Canvas Shopping Bag', 'frontend/image/products/canvas-bag.jpg', 80, 60, 'Durable canvas shopping bag, say no to plastic', NOW(), NOW()),
('Solar-Powered Charger', 'frontend/image/products/solar-charger.jpg', 25, 200, 'Portable solar power bank for eco-friendly charging', NOW(), NOW()),
('Bamboo Toothbrush Set', 'frontend/image/products/toothbrush.jpg', 150, 40, 'Pack of 4 biodegradable bamboo toothbrushes', NOW(), NOW()),
('Compost Bin', 'frontend/image/products/compost-bin.jpg', 20, 150, 'Kitchen compost bin with charcoal filter', NOW(), NOW()),
('Metal Straw Set', 'frontend/image/products/metal-straws.jpg', 200, 45, 'Set of 4 reusable metal straws with cleaning brush', NOW(), NOW());

-- Sample purchase data (you'll need to replace 'user@example.com' with actual user emails from your system)
INSERT INTO purchases (email, product_id, name, address, mobile, eco_coins_spent, status, created_at, updated_at) VALUES
('user@example.com', 1, 'John Doe', '123 Green Street', '+8801712345678', 100, 'delivered', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
('user@example.com', 3, 'John Doe', '123 Green Street', '+8801712345678', 50, 'delivered', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),
('jane@example.com', 2, 'Jane Smith', '456 Eco Avenue', '+8801787654321', 75, 'processing', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY);
